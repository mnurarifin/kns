<?php
namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Serial_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function set_datetime($value) {
        $this->datetime = $value;
    }

    public function insert_member_plan_activity($arr_data)
    {
        if($this->common_model->insertData('sys_member_plan_activity', $arr_data) == FALSE) {
            throw new \Exception("Gagal menyimpan member plan.", 1);
        }
    }

    public function update_serial_used($serial_id, $member_id, $note)
    {
        //update data master serial
        $arr_data = [];
        $arr_data['serial_is_used'] = 1;
        $arr_data['serial_used_datetime'] = $this->datetime;
        $arr_data['serial_used_member_id'] = $member_id;
        if($this->common_model->updateData('sys_serial', 'serial_id', $serial_id, $arr_data) == FALSE) {
            throw new \Exception("Gagal update serial.", 1);
        }

        //ubah data stok serial di member
        $arr_data = [];
        $arr_data['serial_member_stock_is_used'] = 1;
        $arr_data['serial_member_stock_note'] = $note;
        if($this->common_model->updateData('sys_serial_member_stock', 'serial_member_stock_serial_id', $serial_id, $arr_data) == FALSE) {
            throw new \Exception("Gagal update stok serial member.", 1);
        }

        //catat ke tabel distribusi serial
        $this->insert_serial_distribution($serial_id, 'member', $member_id, $note);
    }

    public function insert_serial_distribution($serial_id, $serial_owner, $owner_ref_id = NULL, $note = '')
    {
        $serial_type_id = $this->common_model->getOne('sys_serial', 'serial_serial_type_id', ['serial_id' => $serial_id]);
        $arr_data = [];
        $arr_data['serial_distribution_serial_id'] = $serial_id;
        $arr_data['serial_distribution_serial_type_id'] = $serial_type_id;
        $arr_data['serial_distribution_owner_status'] = $serial_owner;
        $arr_data['serial_distribution_owner_ref_id'] = $owner_ref_id;
        $arr_data['serial_distribution_owner_datetime'] = $this->datetime;
        $arr_data['serial_distribution_note'] = $note;
        if($this->common_model->insertData('log_serial_distribution', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data distribusi serial.", 1);
        }
    }

    public function update_serial_ro_used($serial_ro_id, $member_id, $note)
    {
        //update data master serial
        $arr_data = [];
        $arr_data['serial_ro_is_used'] = 1;
        $arr_data['serial_ro_used_datetime'] = $this->datetime;
        $arr_data['serial_ro_used_member_id'] = $member_id;
        if($this->common_model->updateData('sys_serial_ro', 'serial_ro_id', $serial_ro_id, $arr_data) == FALSE) {
            throw new \Exception("Gagal update serial RO.", 1);
        }

        //ubah data stok serial di member
        $arr_data = [];
        $arr_data['serial_ro_member_stock_is_used'] = 1;
        $arr_data['serial_ro_member_stock_note'] = $note;
        if($this->common_model->updateData('sys_serial_ro_member_stock', 'serial_ro_member_stock_serial_ro_id', $serial_ro_id, $arr_data) == FALSE) {
            throw new \Exception("Gagal update stok serial RO member.", 1);
        }

        //catat ke tabel distribusi serial
        $this->insert_serial_ro_distribution($serial_ro_id, 'member', $member_id, $note);
    }

    public function insert_serial_ro_distribution($serial_ro_id, $serial_ro_owner, $owner_ref_id = NULL, $note = '')
    {
        $serial_ro_type_id = $this->common_model->getOne('sys_serial_ro', 'serial_ro_serial_ro_type_id', ['serial_ro_id' => $serial_ro_id]);
        $arr_data = [];
        $arr_data['serial_ro_distribution_serial_ro_id'] = $serial_ro_id;
        $arr_data['serial_ro_distribution_serial_ro_type_id'] = $serial_ro_type_id;
        $arr_data['serial_ro_distribution_owner_status'] = $serial_ro_owner;
        $arr_data['serial_ro_distribution_owner_ref_id'] = $owner_ref_id;
        $arr_data['serial_ro_distribution_owner_datetime'] = $this->datetime;
        $arr_data['serial_ro_distribution_note'] = $note;
        if($this->common_model->insertData('log_serial_ro_distribution', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data distribusi serial RO.", 1);
        }
    }

    public function generate_serial($serial_type_id = 1, $stock_min = 10)
    {
        //cek jumlah stok serial, jika kurang dari minimal, maka baru di generate
        $stock = $this->db->table('sys_serial')->selectCount('serial_id')->where('serial_serial_type_id', $serial_type_id)->where('serial_is_used', 0)->countAllResults();
        if($stock < $stock_min) {
            $prefix = $this->common_model->getOne('sys_serial_type', 'serial_type_prefix', ['serial_type_id' => $serial_type_id]);
            $quantity = $stock_min - $stock;

            $i = 1;
            while($i <= $quantity) {
                $serial_id = $prefix . generateLetter(1) . generateCode(7) . generateNumber(4);
                $serial_pin = generateCode(6);
                $arr_data = [];
                $arr_data['serial_id'] = $serial_id;
                $arr_data['serial_pin'] = $serial_pin;
                $arr_data['serial_serial_type_id'] = $serial_type_id;
                $arr_data['serial_create_datetime'] = $this->datetime;
                if($this->common_model->insertData('sys_serial', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data serial.", 1);
                } else {
                    $i++;
                }

                $this->insert_serial_distribution($serial_id, 'company', NULL, 'Generate serial');
            }
        }
    }

    public function generate_serial_ro($serial_ro_type_id = 1, $stock_min = 10)
    {
        //cek jumlah stok serial, jika kurang dari minimal, maka baru di generate
        $stock = $this->db->table('sys_serial_ro')->selectCount('serial_ro_id')->where('serial_ro_serial_ro_type_id', $serial_ro_type_id)->where('serial_ro_is_used', 0)->countAllResults();
        if($stock < $stock_min) {
            $prefix = $this->common_model->getOne('sys_serial_ro_type', 'serial_ro_type_prefix', ['serial_ro_type_id' => $serial_ro_type_id]);
            $quantity = $stock_min - $stock;

            $i = 1;
            while($i <= $quantity) {
                $serial_ro_id = $prefix . generateLetter(1) . generateCode(5) . generateNumber(4);
                $serial_ro_pin = generateCode(6);
                $arr_data = [];
                $arr_data['serial_ro_id'] = $serial_ro_id;
                $arr_data['serial_ro_pin'] = $serial_ro_pin;
                $arr_data['serial_ro_serial_ro_type_id'] = $serial_ro_type_id;
                $arr_data['serial_ro_create_datetime'] = $this->datetime;
                if($this->common_model->insertData('sys_serial_ro', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data serial RO.", 1);
                } else {
                    $i++;
                }

                $this->insert_serial_ro_distribution($serial_ro_id, 'company', NULL, 'Generate serial');
            }
        }
    }
}
