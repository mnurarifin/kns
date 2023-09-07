<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ProductModel extends Model
{

    function __construct()
    {
        parent::__construct();
        // Fix Error live
        $this->table = 'inv_product';
    }

    function getProductCode($product_name)
    {
        $words = preg_split("/[\s,_-]+/", $product_name);


        $prefix = "";

        foreach ($words as $w) {
            $prefix .= mb_substr($w, 0, 1);
        }

        $sql = "
        SELECT 
        MAX(product_code) as max_code
        FROM inv_product
        WHERE product_code LIKE '%PD%'
        ";
        $max_code = $this->db->query($sql)->getRow('max_code');

        if ($max_code) {
            $max_int = (int) substr($max_code, 3, 3) + 1;
            $product_code = $prefix . sprintf("%04s", $max_int);
        } else {
            $product_code = $prefix . sprintf('%04s', 1);
        }

        return $product_code;
    }

    function getProductByID($product_id)
    {
        $sql = "
            SELECT 
                product_id,
                product_code,
                product_name,
                product_price,
                product_master_stockist_price,
                product_mobile_stockist_price,
                product_member_price,
                product_weight,
                product_description,
                product_is_active,
                product_image,
                product_input_datetime
            FROM inv_product
            WHERE product_id = '$product_id'
        ";

        return $this->query($sql)->getRow();
    }

    public function getProductByCode($product_code)
    {
        $sql_product = "
            SELECT
                product_id,
                product_code,
                product_name,
                product_type,
                product_price,
                product_member_price,
                product_weight,
                product_description,
                product_is_active,
                product_image,
                product_input_datetime
            FROM inv_product 
            WHERE product_is_active = 1
            AND product_code = '$product_code'
        ";

        return $this->db->query($sql_product)->getRow();
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

    function getStockProductByMember($product_id, $member_id)
    {
        $sql = "
            SELECT 
                product_stockist_value
            FROM inv_product_stockist
            WHERE product_stockist_product_id = '$product_id'
            AND product_stockist_member_id = '$member_id'
        ";

        $count = $this->db->query($sql)->getRow('product_stockist_value');

        return $count ? $count : 0;
    }

    function getAllProduct()
    {
        $sql = "
        SELECT
            product_id,
            product_code,
            product_name,
            product_member_price,
            product_price
        FROM inv_product
        ";

        $product = $this->db->query($sql)->getResult();

        return $product;
    }

    function insertProduct($data)
    {
        $this->db->table('inv_product')->insert($data);
        return $this->insertID();
    }

    function updateProduct($update, $where)
    {
        $this->db->table('inv_product')->update($update, $where);
        return $this->db->affectedRows() > 0 ? TRUE : FALSE;
    }

    function deleteProduct($id)
    {
        $this->db->table('inv_product')->delete(['product_id' => $id]);
        return $this->db->affectedRows() > 0 ? TRUE : FALSE;
    }
}
