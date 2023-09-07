<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Membership_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function save_member($arr_data)
    {
        $member_id = $this->insert_member($arr_data['member']);

        $arr_data['account']['member_account_member_id'] = $member_id;
        $this->insert_member_account($arr_data['account']);

        return $member_id;
    }

    private function insert_member($arr_data)
    {
        $this->db->table('sys_member')->insert($arr_data);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menyimpan member.", 1);
        }
        $member_id = $this->db->insertID();

        //jika parent, maka update parent menjadi ID dirinya sendiri
        if($arr_data['member_parent_member_id'] == 0) {
            $arr_update = [];
            $arr_update['member_parent_member_id'] = $member_id;
            if($this->common_model->updateData('sys_member', 'member_id', $member_id, $arr_update) == FALSE) {
                throw new \Exception("Gagal update member parent.", 1);
            }
        }

        return $member_id;
    }

    private function insert_member_account($arr_data)
    {
        if($this->common_model->insertData('sys_member_account', $arr_data) == FALSE) {
            throw new \Exception("Gagal menyimpan akun.", 1);
        }
    }

    public function init_inv_member_product_stock($member_id)
    {
        $sql = "SELECT product_id FROM inv_product WHERE product_is_active = 1";
        $query = $this->db->query($sql);
        foreach($query->getResult() as $row) {
            $arr_data = [];
            $arr_data['member_product_stock_member_id'] = $member_id;
            $arr_data['member_product_stock_product_id'] = $row->product_id;
            if($this->common_model->insertData('inv_member_product_stock', $arr_data) == FALSE) {
                throw new \Exception("Gagal menyimpan inisialisasi data stok produk member.", 1);
            }
        }

    }

}
