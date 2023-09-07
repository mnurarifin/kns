<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    public function getTransactionCode($type, $date = '')
    {
        if ($date == '') {
            $date = date("Y-m-d");
        }

        if ($type == "member") {
            $prefix = 'TRX/CM';
        } else if ($type == "stockist") {
            $prefix = 'TRX/CS';
        } else {
            throw new \Exception("Gagal membuat kode transaksi", 1);
        }
        $new_sort = '0001';
        $sql = "
            SELECT IFNULL(LPAD(MAX(CAST(RIGHT(warehouse_transaction_code, 4) AS SIGNED) + 1), 4, '0'), '0001') AS new_sort
            FROM inv_warehouse_transaction
            WHERE DATE(warehouse_transaction_datetime) = '" . $date . "'
        ";
        $row = $this->db->query($sql)->getRow();

        if (!is_null($row)) {
            $new_sort = $row->new_sort;
        }
        $new_code = $prefix . '/' . str_replace('-', '', $date) . '/' . $new_sort;

        return $new_code;
    }

    public function getDetailTransaction($transaction_id)
    {
        $sql_transaction = "
        SELECT
        warehouse_transaction_id,
        warehouse_transaction_warehouse_id,
        warehouse_transaction_code,
        warehouse_transaction_buyer_type,
        warehouse_transaction_buyer_member_id,
        warehouse_transaction_buyer_name,
        warehouse_transaction_buyer_address,
        warehouse_transaction_buyer_mobilephone,
        warehouse_transaction_total_price,
        warehouse_transaction_extra_discount_type,
        warehouse_transaction_extra_discount_percent,
        warehouse_transaction_extra_discount_value,
        warehouse_transaction_delivery_method,
        warehouse_transaction_delivery_courier_code,
        warehouse_transaction_delivery_courier_service,
        warehouse_transaction_delivery_cost,
        warehouse_transaction_delivery_receiver_name,
        warehouse_transaction_delivery_receiver_mobilephone,
        warehouse_transaction_delivery_receiver_province_id,
        province_name AS warehouse_transaction_delivery_receiver_province_name,
        warehouse_transaction_delivery_receiver_city_id,
        city_name AS warehouse_transaction_delivery_receiver_city_name,
        warehouse_transaction_delivery_receiver_subdistrict_id,
        subdistrict_name AS warehouse_transaction_delivery_receiver_subdistrict_name,
        warehouse_transaction_delivery_receiver_address,
        warehouse_transaction_delivery_awb,
        warehouse_transaction_delivery_status,
        warehouse_transaction_payment_image,
        warehouse_transaction_total_nett_price,
        warehouse_transaction_payment_cash,
        warehouse_transaction_payment_ewallet,
        warehouse_transaction_notes,
        warehouse_transaction_status,
        warehouse_transaction_status_datetime,
        warehouse_transaction_datetime,
        warehouse_transaction_type,
        warehouse_transaction_awb,
        courier_name,
        network_code
        FROM inv_warehouse_transaction
        LEFT JOIN ref_courier ON courier_code = warehouse_transaction_delivery_courier_code
        LEFT JOIN sys_network ON network_member_id = warehouse_transaction_buyer_member_id
        LEFT JOIN ref_subdistrict ON subdistrict_id = warehouse_transaction_delivery_receiver_subdistrict_id
        LEFT JOIN ref_city ON city_id = warehouse_transaction_delivery_receiver_city_id
        LEFT JOIN ref_province ON province_id = warehouse_transaction_delivery_receiver_province_id
        WHERE warehouse_transaction_id = '$transaction_id'
        ";

        $result_transaction = $this->db->query($sql_transaction)->getRow();

        if ($result_transaction->warehouse_transaction_payment_image != '' && file_exists(UPLOAD_PATH . URL_IMG_PAYMENT . $result_transaction->warehouse_transaction_payment_image)) {
            $result_transaction->warehouse_transaction_payment_image = UPLOAD_URL . URL_IMG_PAYMENT . $result_transaction->warehouse_transaction_payment_image;
        } else {
            $result_transaction->warehouse_transaction_payment_image =  base_url() . '/no-image.png';
        }

        if ($result_transaction->warehouse_transaction_delivery_courier_code != '') {
            $result_transaction->warehouse_transaction_delivery_courier_code = strtoupper($result_transaction->warehouse_transaction_delivery_courier_code);
        }

        if ($result_transaction) {
            $sql_transaction_detail = "
            SELECT 
            warehouse_transaction_detail_id,
            warehouse_transaction_detail_warehouse_transaction_id,
            warehouse_transaction_detail_product_id,
            warehouse_transaction_detail_product_code,
            warehouse_transaction_detail_product_name,
            warehouse_transaction_detail_unit_price,
            warehouse_transaction_detail_discount_type,
            warehouse_transaction_detail_discount_percent,
            warehouse_transaction_detail_discount_value,
            warehouse_transaction_detail_unit_nett_price,
            warehouse_transaction_detail_quantity,
            product_weight
            FROM inv_warehouse_transaction_detail
            JOIN inv_product ON product_id = warehouse_transaction_detail_product_id
            WHERE warehouse_transaction_detail_warehouse_transaction_id = '{$result_transaction->warehouse_transaction_id}'
            ";

            $result_transaction->warehouse_transaction_detail = $this->db->query($sql_transaction_detail)->getResult();
        }

        return $result_transaction;
    }
}
