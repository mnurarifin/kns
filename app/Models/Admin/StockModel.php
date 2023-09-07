<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class StockModel extends Model
{
    
    function __construct()
    {
        parent::__construct();
        // Fix Error live
        $this->table = 'inv_warehouse_product_stock';
    }

    function getReceivementByID($receivement_id){
        $sql = "
            SELECT 
            warehouse_receipt_detail_receipt_id,
            warehouse_receipt_detail_product_id,
            warehouse_receipt_detail_quantity,
            product_name
            FROM inv_warehouse_receipt_detail
            JOIN inv_product ON product_id = warehouse_receipt_detail_product_id
            WHERE warehouse_receipt_detail_receipt_id = '$receivement_id'
        ";

        return $this->query($sql)->getResult();
    }

    public function getStockByCode($stock_code)
    {
        $sql_stock = "
            SELECT
                stock_id,
                stock_code,
                stock_name,
                stock_price,
                stock_member_price,
                stock_weight,
                stock_description,
                stock_is_active,
                stock_image,
                stock_input_datetime
            FROM inv_stock 
            WHERE stock_is_active = 1
            AND stock_code = '$stock_code'
        ";

        return $this->db->query($sql_stock)->getRow();
    }

    function getStockist($network_code)
    {
        $sql = "
            SELECT 
                network_member_id AS stockist_member_id,
                member_ref_network_code AS stockist_member_code,
                stockist_code,
                stockist_name,
                stockist_member_name,
                stockist_email,
                stockist_address,
                stockist_mobilephone
            FROM bin_network
            JOIN bin_member_ref ON member_ref_network_id = member_ref_member_id
            JOIN sys_stockist ON network_member_id = stockist_member_id
            WHERE network_is_stockist = '1'
            AND network_code = '$network_code'
        "; 

        $data = $this->db->query($sql)->getRow();
        
        return $data;
    }

    function getStockStockByMember($stock_id,$member_id)
    {
        $sql = "
            SELECT 
                stock_stockist_value
            FROM inv_stock_stockist
            WHERE stock_stockist_stock_id = '$stock_id'
            AND stock_stockist_member_id = '$member_id'
        ";

        $count = $this->db->query($sql)->getRow('stock_stockist_value');

        return $count ? $count : 0;
    }

    function getAllStock()
    {
        $sql = "
        SELECT
            stock_id,
            stock_code,
            stock_name,
            stock_member_price,
            stock_price
        FROM inv_stock
        ";

        $stock = $this->db->query($sql)->getResult();

        return $stock;
    }

    function insertStock($data)
    {
        $this->db->table('inv_stock')->insert($data);
        return $this->insertID();
    }

    function updateStock($update,$where)
    {
        $this->db->table('inv_stock')->update($update, $where);
        return $this->db->affectedRows() > 0 ? TRUE : FALSE;
    }

    function deleteStock($id)
    {
        $this->db->table('inv_stock')->delete(['stock_id' => $id]);
        return $this->db->affectedRows() > 0 ? TRUE : FALSE;
    }

    public function log($warehouse_id, $product_id, $type, $qty, $old, $note, $datetime)
    {
        $product = $this->db->table("inv_product")->getWhere(["product_id" => $product_id])->getRow();
        $this->db->table('inv_warehouse_product_stock_log')->insert([
            "warehouse_product_stock_log_warehouse_id" => $warehouse_id,
            "warehouse_product_stock_log_product_id" => $product_id,
            "warehouse_product_stock_log_type" => $type,
            "warehouse_product_stock_log_quantity" => $qty,
            "warehouse_product_stock_log_unit_price" => $product->product_price,
            "warehouse_product_stock_log_balance" => $type == "in" ? $old + $qty : $old - $qty,
            "warehouse_product_stock_log_note" => $note,
            "warehouse_product_stock_log_datetime" => $datetime,
        ]);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal update data stock", 1);
        }
    }

    public function logMember($member_id, $product_id, $type, $qty, $old, $note, $datetime)
    {
        $product = $this->db->table("inv_product")->getWhere(["product_id" => $product_id])->getRow();
        $this->db->table('inv_member_product_stock_log')->insert([
            "member_product_stock_log_member_id" => $member_id,
            "member_product_stock_log_product_id" => $product_id,
            "member_product_stock_log_type" => $type,
            "member_product_stock_log_quantity" => $qty,
            "member_product_stock_log_unit_price" => $product->product_price,
            "member_product_stock_log_balance" => $type == "in" ? $old + $qty : $old - $qty,
            "member_product_stock_log_note" => $note,
            "member_product_stock_log_datetime" => $datetime,
        ]);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal update data stock", 1);
        }
    }

    public function logStockist($member_id, $product_id, $type, $qty, $old, $note, $datetime)
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