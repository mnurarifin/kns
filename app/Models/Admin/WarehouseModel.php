<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class WarehouseModel extends Model
{
    
    function __construct()
    {
        parent::__construct();
        // Fix Error live
        $this->table = 'inv_warehouse';
    }

    function getWarehouseByID($warehouse_id){
        $sql = "
            SELECT 
                warehouse_id,
                warehouse_name,
                warehouse_address,
                warehouse_is_active,
                warehouse_input_datetime,
                warehouse_input_administrator_id
            FROM inv_warehouse
            WHERE warehouse_id = '$warehouse_id'
        ";

        return $this->query($sql)->getRow();
    }

    public function getWarehouseByCode($warehouse_code)
    {
        $sql_warehouse = "
            SELECT
                warehouse_id,
                warehouse_code,
                warehouse_name,
                warehouse_price,
                warehouse_member_price,
                warehouse_weight,
                warehouse_description,
                warehouse_is_active,
                warehouse_image,
                warehouse_input_datetime
            FROM inv_warehouse 
            WHERE warehouse_is_active = 1
            AND warehouse_code = '$warehouse_code'
        ";

        return $this->db->query($sql_warehouse)->getRow();
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

    function getStockWarehouseByMember($warehouse_id,$member_id)
    {
        $sql = "
            SELECT 
                warehouse_stockist_value
            FROM inv_warehouse_stockist
            WHERE warehouse_stockist_warehouse_id = '$warehouse_id'
            AND warehouse_stockist_member_id = '$member_id'
        ";

        $count = $this->db->query($sql)->getRow('warehouse_stockist_value');

        return $count ? $count : 0;
    }

    function getAllWarehouse()
    {
        $sql = "
        SELECT
            warehouse_id,
            warehouse_code,
            warehouse_name,
            warehouse_member_price,
            warehouse_price
        FROM inv_warehouse
        ";

        $warehouse = $this->db->query($sql)->getResult();

        return $warehouse;
    }

    function insertWarehouse($data)
    {
        $this->db->table('inv_warehouse')->insert($data);
        return $this->insertID();
    }

    function updateWarehouse($update,$where)
    {
        $this->db->table('inv_warehouse')->update($update, $where);
        return $this->db->affectedRows() > 0 ? TRUE : FALSE;
    }

    function deleteWarehouse($id)
    {
        $this->db->table('inv_warehouse')->delete(['warehouse_id' => $id]);
        return $this->db->affectedRows() > 0 ? TRUE : FALSE;
    }

}