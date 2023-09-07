<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class StockistModel extends Model
{

    public function getDetailStockist($stockist_member_id)
    {
        return $this->db
            ->table('inv_stockist')
            ->select('
            stockist_member_id,
            stockist_name,
            stockist_email,
            stockist_address,
            stockist_mobilephone,
            stockist_province_id,
            stockist_city_id,
            stockist_subdistrict_id,
            stockist_longitude,
            stockist_latitude,
            stockist_image,
            stockist_input_datetime,
            stockist_is_active,
            member_name,
            network_code,
            city_name,
            province_name,
            subdistrict_name
        ')
            ->join('ref_city', 'city_id = stockist_city_id ', 'left')
            ->join('ref_province', 'province_id = stockist_province_id ', 'left')
            ->join('ref_subdistrict', 'subdistrict_id = stockist_subdistrict_id ', 'left')
            ->join('sys_network', 'network_member_id = stockist_member_id')
            ->join('sys_member', 'member_id = stockist_member_id')
            ->getWhere([
                'stockist_member_id' => $stockist_member_id
            ])->getRow();
    }

    public function getStatus($stockist_member_id)
    {
        return $this->db
            ->table('sys_stockist')
            ->select('stockist_status')
            ->getWhere([
                'stockist_member_id' => $stockist_member_id
            ])->getRow();
    }

    public function log($member_id, $product_id, $type, $qty, $old, $note, $datetime)
    {
        $product = $this->db->table("inv_product")->getWhere(["product_id" => $product_id])->getRow();

        $this->db->table('inv_stockist_product_stock_log')->insert([
            "stockist_product_stock_log_stockist_member_id" => $member_id,
            "stockist_product_stock_log_product_id" => $product_id,
            "stockist_product_stock_log_type" => $type,
            "stockist_product_stock_log_quantity" => $qty,
            "stockist_product_stock_log_unit_price" => $product->product_price,
            "stockist_product_stock_log_balance" => $type == "in" ? $old + $qty : $old - $qty,
            "stockist_product_stock_log_note" => $note,
            "stockist_product_stock_log_datetime" => $datetime,
        ]);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal update data stock", 1);
        }
    }
}
