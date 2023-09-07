<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Network_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->arr_network = [];
        $this->arr_network_upline = []; //array dari JSON pada tabel sys_network_upline
    }

    public function save_network($arr_data)
    {
        $this->arr_network = $arr_data;

        //insert data jaringan
        $this->insert_network();

        //insert data jaringan sponsor dimulai dari sponsor langsung sampai ke root (dalam bentuk json)
        $this->insert_network_sponsor();

        //insert data jaringan upline dimulai dari upline langsung sampai ke root (dalam bentuk json)
        $this->insert_network_upline();

        //update data penambahan titik/point jaringan sponsor
        $this->update_sponsor();
    }

    public function upgrade_network($arr_data)
    {
        $this->arr_network = $arr_data;

        //simpan paket upgrade ke sys_network
        $arr_data = [];
        // $arr_data['network_product_package_id'] = $this->arr_network['network_product_package_id'];
        // $arr_data['network_product_package_name'] = $this->arr_network['network_product_package_name'];
        if ($this->common_model->updateData('sys_network', 'network_member_id', $this->arr_network['network_member_id'], $arr_data) == FALSE) {
            throw new \Exception("Gagal update data paket upgrade pada jaringan.", 1);
        }
    }

    private function insert_network()
    {
        if ($this->common_model->insertData('sys_network', $this->arr_network) == FALSE) {
            throw new \Exception("Gagal menyimpan data jaringan.", 1);
        }
    }

    private function insert_network_sponsor()
    {
        //ambil data jaringan sponsor
        $arr_network_sponsor = (array)json_decode($this->common_model->getOne('sys_network_sponsor', 'network_sponsor_arr_data', ['network_sponsor_member_id' => $this->arr_network['network_sponsor_member_id']]));

        //urutkan ulang berdasarkan level
        usort($arr_network_sponsor, function ($a, $b) {
            return $a->level <=> $b->level;
        });

        //tambahkan masing-masing level +1 karena level 1 akan disisipkan sponsor langsung
        foreach ($arr_network_sponsor as $key => $value) {
            $arr_network_sponsor[$key]->level = $value->level + 1;
        }

        //tambahan data sponsor langsung
        $arr_network_sponsor_add = [(object)[
            'level' => 1,
            'id' => (int)$this->arr_network['network_sponsor_member_id'],
            'pos' => (int)$this->arr_network['network_sponsor_leg_position'],
        ]];

        //tambahkan data sponsor langsung ke jaringan sponsor yg sudah ada
        $arr_network_sponsor_merge = array_merge($arr_network_sponsor_add, $arr_network_sponsor);

        //urutkan ulang berdasarkan level
        usort($arr_network_sponsor_merge, function ($a, $b) {
            return $a->level <=> $b->level;
        });

        //simpan data jaringan sponsor
        $arr_data = [];
        $arr_data['network_sponsor_member_id'] = $this->arr_network['network_member_id'];
        $arr_data['network_sponsor_arr_data'] = json_encode($arr_network_sponsor_merge);
        if ($this->common_model->insertData('sys_network_sponsor', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data jaringan sponsor.", 1);
        }
    }

    private function insert_network_upline()
    {
        //ambil data jaringan upline
        $arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->arr_network['network_upline_member_id']]));

        //urutkan ulang berdasarkan level
        usort($arr_network_upline, function ($a, $b) {
            return $a->level <=> $b->level;
        });

        //tambahkan masing-masing level +1 karena level 1 akan disisipkan upline langsung
        foreach ($arr_network_upline as $key => $value) {
            $arr_network_upline[$key]->level = $value->level + 1;
        }

        //tambahan data upline langsung
        $arr_network_upline_add = [(object)[
            'level' => 1,
            'id' => (int)$this->arr_network['network_upline_member_id'],
            'pos' => (int)$this->arr_network['network_upline_leg_position'],
        ]];

        //tambahkan data upline langsung ke jaringan upline yg sudah ada
        $arr_network_upline_merge = array_merge($arr_network_upline_add, $arr_network_upline);

        //urutkan ulang berdasarkan level
        usort($arr_network_upline_merge, function ($a, $b) {
            return $a->level <=> $b->level;
        });

        //simpan data jaringan upline
        $arr_data = [];
        $arr_data['network_upline_member_id'] = $this->arr_network['network_member_id'];
        $arr_data['network_upline_arr_data'] = json_encode($arr_network_upline_merge);
        if ($this->common_model->insertData('sys_network_upline', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data jaringan upline.", 1);
        }
    }

    private function update_sponsor()
    {
        if ($this->arr_network['network_sponsor_member_id']) {
            $sql = "
                UPDATE sys_network
                SET network_total_sponsoring = network_total_sponsoring + 1
                WHERE network_member_id = {$this->arr_network['network_sponsor_member_id']}
            ";
            $this->db->query($sql);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah sponsor.", 1);
            }
        }
    }

    public function get_network_code()
    {
        $sql_select = "SELECT stock_network_code_value FROM sys_stock_network_code ORDER BY stock_network_code_id ASC LIMIT 1";
        $row = $this->db->query($sql_select)->getRow();
        if (is_null($row)) {
            throw new \Exception("Stok kode member habis.", 1);
        }
        $network_code = $row->stock_network_code_value;

        //delete stock network mid
        $sql_delete = "DELETE FROM sys_stock_network_code WHERE stock_network_code_value = '" . $network_code . "'";
        $this->db->query($sql_delete);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menyimpan mendapatkam kode jaringan.", 1);
        }

        return $network_code;
    }

    public function generate_stock_network_code($stock_min = 10000)
    {
        //cek jumlah stok network_code, jika kurang dari minimal, maka baru di generate
        $stock = $this->db->table('sys_stock_network_code')->selectCount('stock_network_code_id')->countAllResults();
        if ($stock < $stock_min) {
            $is_start_new = FALSE;
            //ambil kode terakhir dari stok kode
            $last_stock_network_code = $this->db->query("SELECT MAX(stock_network_code_value) AS stock_network_code_value FROM sys_stock_network_code")->getRow('stock_network_code_value');
            if (empty($last_stock_network_code) || is_null($last_stock_network_code) || $last_stock_network_code == '') {
                //jika stok kode tidak ada / habis, maka ambil kode terakhir dari network_code
                $last_stock_network_code = $this->db->query("SELECT MAX(network_code) AS network_code FROM sys_network")->getRow('network_code');
                if (empty($last_stock_network_code) || is_null($last_stock_network_code) || $last_stock_network_code == '') {
                    //jika di network_code juga tidak ada, maka buat kode dari awal
                    $last_stock_network_code = str_pad(NETWORK_CODE_PREFIX . "1", (NETWORK_CODE_LENGTH), "0");
                    $is_start_new = TRUE;
                }
            }

            $new_stock_network_code = $last_stock_network_code;
            $new_stock_network_code++;
            $quantity = $stock_min - $stock;
            for ($c = 1; $c <= $quantity; $c++) {
                $this->db->table('sys_stock_network_code')->insert(['stock_network_code_value' => $new_stock_network_code]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah kode jaringan.", 1);
                }
                $new_stock_network_code++;
            }

            //jika buat kode dari awal, hapus kode 001 yang akan dipakai oleh titik root
            if ($is_start_new) {
                $this->get_network_code();
            }
        }
    }

    public function getUsernameByMemberId(int $id)
    {
        return $this->db->table("sys_member_account")->getWhere(["member_account_member_id" => $id])->getRow("member_account_username");
    }

    public function getMemberIdByUsername(string $username)
    {
        return $this->db->table("sys_member_account")->getWhere(["member_account_username" => $username])->getRow("member_account_member_id");
    }
}
