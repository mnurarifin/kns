<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;
use App\Models\Common_model;

class Stockist_model extends Model
{
    public $request;
    public $dataTable;
    public $db;
    public $common_model;

    public function init($request = null)
    {
        $this->request = $request;
        $this->common_model = new Common_model();
    }

    public function getStock($id)
    {
        $tableName = "inv_stockist_product_stock";
        $columns = [
            "stockist_product_stock_id",
            "stockist_product_stock_stockist_member_id",
            "stockist_product_stock_product_id",
            "stockist_product_stock_balance",
            "product_name" => "stockist_product_stock_product_name",
            "product_code" => "stockist_product_stock_product_code",
            "product_price" => "stockist_product_stock_product_price"
        ];
        $joinTable = "JOIN inv_product ON product_id = stockist_product_stock_product_id";
        $whereCondition = " stockist_product_stock_stockist_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['stockist_product_stock_product_price_formatted'] = 'Rp ' . setNumberFormat($value['stockist_product_stock_product_price']);
        }

        return $arr_data;
    }

    public function getStockLog($id)
    {
        $tableName = "inv_stockist_product_stock_log";
        $columns = [
            "stockist_product_stock_log_id",
            "stockist_product_stock_log_stockist_member_id",
            "stockist_product_stock_log_product_id",
            "stockist_product_stock_log_type",
            "stockist_product_stock_log_quantity",
            "stockist_product_stock_log_unit_price",
            "stockist_product_stock_log_balance",
            "stockist_product_stock_log_note",
            "stockist_product_stock_log_datetime",
            "product_name" => "stockist_product_stock_log_product_name",
            "product_code" => "stockist_product_stock_log_product_code",
            "network_code",
        ];
        $joinTable = "LEFT JOIN inv_product ON product_id = stockist_product_stock_log_product_id
        LEFT JOIN inv_stockist_transaction ON stockist_transaction_datetime = stockist_product_stock_log_datetime 
        LEFT JOIN (SELECT stockist_transaction_detail_stockist_transaction_id, SUM(stockist_transaction_detail_quantity) 
        FROM inv_stockist_transaction_detail GROUP BY stockist_transaction_detail_stockist_transaction_id) t 
        ON stockist_transaction_detail_stockist_transaction_id = stockist_transaction_id
        LEFT JOIN sys_network ON network_member_id = stockist_transaction_buyer_member_id";
        $whereCondition = " stockist_product_stock_log_stockist_member_id = {$id} ";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["stockist_product_stock_log_datetime"] = convertDatetime($value["stockist_product_stock_log_datetime"], "id");
        }
        $total = $this->db->query("select sum(if(stockist_product_stock_log_type = 'in',stockist_product_stock_log_quantity,0)) as total_in,
        sum(if(stockist_product_stock_log_type = 'out',stockist_product_stock_log_quantity,0)) as total_out
        from inv_stockist_product_stock_log 
        left join (select * from inv_stockist_transaction  where stockist_transaction_status in ('paid','complete','delivered','approved'))t on stockist_transaction_datetime = stockist_product_stock_log_datetime 
        where stockist_product_stock_log_stockist_member_id = {$id} and stockist_product_stock_log_note not like '%Pengembalian%' 
       ")->getRow();
        $arr_data["total_in"] = $total->total_in;
        $arr_data["total_out"] = $total->total_out;

        return $arr_data;
    }

    public function getTransactionDelivered($id, $type = '')
    {
        if ($type == '') {
            $tableName = "inv_warehouse_transaction";
            $columns = [
                "warehouse_transaction_id",
                "warehouse_transaction_warehouse_id",
                "warehouse_transaction_code",
                "warehouse_transaction_buyer_type",
                "warehouse_transaction_buyer_member_id",
                "warehouse_transaction_buyer_name",
                "warehouse_transaction_buyer_address",
                "warehouse_transaction_buyer_mobilephone",
                "warehouse_transaction_total_price",
                "warehouse_transaction_extra_discount_type",
                "warehouse_transaction_extra_discount_percent",
                "warehouse_transaction_extra_discount_value",
                "warehouse_transaction_delivery_method",
                "warehouse_transaction_delivery_courier_code",
                "warehouse_transaction_delivery_courier_service",
                "warehouse_transaction_delivery_cost",
                "warehouse_transaction_delivery_receiver_name",
                "warehouse_transaction_delivery_receiver_mobilephone",
                "warehouse_transaction_delivery_receiver_province_id",
                "warehouse_transaction_delivery_receiver_city_id",
                "warehouse_transaction_delivery_receiver_subdistrict_id",
                "warehouse_transaction_delivery_receiver_address",
                "warehouse_transaction_delivery_awb",
                "warehouse_transaction_delivery_status",
                "warehouse_transaction_payment_image",
                "warehouse_transaction_total_nett_price",
                "warehouse_transaction_payment_cash",
                "warehouse_transaction_payment_ewallet",
                "warehouse_transaction_notes",
                "warehouse_transaction_status",
                "warehouse_transaction_status_datetime",
                "warehouse_transaction_datetime",
                "warehouse_transaction_type",
                "warehouse_transaction_awb",
                "province_name AS warehouse_transaction_delivery_receiver_province_name",
                "city_name AS warehouse_transaction_delivery_receiver_city_name",
                "subdistrict_name AS warehouse_transaction_delivery_receiver_subdistrict_name",
            ];
            $joinTable = " LEFT JOIN ref_province ON province_id = warehouse_transaction_delivery_receiver_province_id";
            $joinTable .= " LEFT JOIN ref_city ON city_id = warehouse_transaction_delivery_receiver_city_id";
            $joinTable .= " LEFT JOIN ref_subdistrict ON subdistrict_id = warehouse_transaction_delivery_receiver_subdistrict_id";
            $whereCondition = " warehouse_transaction_buyer_member_id = {$id} AND
        warehouse_transaction_buyer_type = 'stockist' AND
        case 
            when warehouse_transaction_delivery_method = 'pickup' then  warehouse_transaction_status = 'paid'
            when warehouse_transaction_delivery_method = 'courier' then  warehouse_transaction_status = 'delivered'
        end
        ";
            $groupBy = "";

            $this->dataTable = new DataTable();
            $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

            foreach ($arr_data["results"] as $key => $value) {
                $arr_data["results"][$key]["warehouse_transaction_datetime"] = convertDatetime($value["warehouse_transaction_datetime"], "id");
                $arr_data["results"][$key]["warehouse_transaction_status_datetime"] = convertDatetime($value["warehouse_transaction_status_datetime"], "id");
                if ($value["warehouse_transaction_payment_image"] && file_exists(UPLOAD_PATH . URL_IMG_PAYMENT . $value["warehouse_transaction_payment_image"])) {
                    $arr_data["results"][$key]["warehouse_transaction_payment_image"] = UPLOAD_URL . URL_IMG_PAYMENT . $value["warehouse_transaction_payment_image"];
                } else {
                    $arr_data["results"][$key]["warehouse_transaction_payment_image"] = UPLOAD_URL . URL_IMG_PAYMENT . "no-image.png";
                }
                $arr_data["results"][$key]["detail"] = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $value["warehouse_transaction_id"]])->getResult();
                $arr_data["results"][$key]["warehouse_transaction_notes"] = $value['warehouse_transaction_notes'] . ' - ' . $value['warehouse_transaction_awb'];
            }
        } else {
            $tableName = "inv_stockist_transaction";
            $columns = [
                "stockist_transaction_id",
                "stockist_transaction_stockist_member_id",
                "stockist_transaction_code",
                "stockist_transaction_buyer_type",
                "stockist_transaction_buyer_member_id",
                "stockist_transaction_buyer_name",
                "stockist_transaction_buyer_address",
                "stockist_transaction_buyer_mobilephone",
                "stockist_transaction_total_price",
                "stockist_transaction_extra_discount_type",
                "stockist_transaction_extra_discount_percent",
                "stockist_transaction_extra_discount_value",
                "stockist_transaction_delivery_method",
                "stockist_transaction_delivery_courier_code",
                "stockist_transaction_delivery_courier_service",
                "stockist_transaction_delivery_cost",
                "stockist_transaction_delivery_receiver_name",
                "stockist_transaction_delivery_receiver_mobilephone",
                "stockist_transaction_delivery_receiver_province_id",
                "stockist_transaction_delivery_receiver_city_id",
                "stockist_transaction_delivery_receiver_subdistrict_id",
                "stockist_transaction_delivery_receiver_address",
                "stockist_transaction_delivery_awb",
                "stockist_transaction_delivery_status",
                "stockist_transaction_payment_image",
                "stockist_transaction_total_nett_price",
                "stockist_transaction_payment_cash",
                "stockist_transaction_payment_ewallet",
                "stockist_transaction_notes",
                "stockist_transaction_status",
                "stockist_transaction_status_datetime",
                "stockist_transaction_datetime",
                "stockist_transaction_type",
                "stockist_transaction_awb",
                "province_name AS stockist_transaction_delivery_receiver_province_name",
                "city_name AS stockist_transaction_delivery_receiver_city_name",
                "subdistrict_name AS stockist_transaction_delivery_receiver_subdistrict_name",
            ];
            $joinTable = " LEFT JOIN ref_province ON province_id = stockist_transaction_delivery_receiver_province_id";
            $joinTable .= " LEFT JOIN ref_city ON city_id = stockist_transaction_delivery_receiver_city_id";
            $joinTable .= " LEFT JOIN ref_subdistrict ON subdistrict_id = stockist_transaction_delivery_receiver_subdistrict_id";
            $whereCondition = " stockist_transaction_buyer_member_id = {$id} AND
            stockist_transaction_buyer_type = 'stockist' AND
            case 
                when stockist_transaction_delivery_method = 'pickup' then  stockist_transaction_status = 'paid'
                when stockist_transaction_delivery_method = 'courier' then  stockist_transaction_status = 'delivered'
            end
        ";
            $groupBy = "";

            $this->dataTable = new DataTable();
            $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

            foreach ($arr_data["results"] as $key => $value) {
                $arr_data["results"][$key]["stockist_transaction_datetime"] = convertDatetime($value["stockist_transaction_datetime"], "id");
                $arr_data["results"][$key]["stockist_transaction_status_datetime"] = convertDatetime($value["stockist_transaction_status_datetime"], "id");
                if ($value["stockist_transaction_payment_image"] && file_exists(UPLOAD_PATH . URL_IMG_PAYMENT . $value["stockist_transaction_payment_image"])) {
                    $arr_data["results"][$key]["stockist_transaction_payment_image"] = UPLOAD_URL . URL_IMG_PAYMENT . $value["stockist_transaction_payment_image"];
                } else {
                    $arr_data["results"][$key]["stockist_transaction_payment_image"] = UPLOAD_URL . URL_IMG_PAYMENT . "no-image.png";
                }
                $arr_data["results"][$key]["detail"] = $this->db->table("inv_stockist_transaction_detail")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $value["stockist_transaction_id"]])->getResult();
                $arr_data["results"][$key]["stockist_transaction_notes"] = $value['stockist_transaction_notes'] . ' - ' . $value['stockist_transaction_awb'];
            }
        }


        return $arr_data;
    }

    public function getTransactionPayment($id, $transaction_id)
    {
        $tableName = "inv_stockist_transaction";
        $columns = [
            "stockist_transaction_id",
            "stockist_transaction_datetime",
            "stockist_transaction_code",
            "stockist_transaction_buyer_name",
            "stockist_transaction_total_nett_price",
            "stockist_transaction_payment_image",
            "stockist_transaction_notes",
            "stockist_transaction_type",
            "stockist_transaction_status",
            "stockist_transaction_delivery_method",
            "stockist_transaction_delivery_cost",
            "stockist_transaction_delivery_receiver_name",
            "stockist_transaction_delivery_receiver_mobilephone",
            "stockist_transaction_delivery_receiver_address",
            "stockist_transaction_delivery_courier_code",
            "stockist_transaction_payment_invoice_url",
            "stockist_transaction_delivery_courier_service",
            "province_name AS stockist_transaction_delivery_receiver_province_name",
            "city_name AS stockist_transaction_delivery_receiver_city_name",
            "subdistrict_name AS stockist_transaction_delivery_receiver_subdistrict_name",
        ];
        $joinTable = " LEFT JOIN ref_province ON province_id = stockist_transaction_delivery_receiver_province_id";
        $joinTable .= " LEFT JOIN ref_city ON city_id = stockist_transaction_delivery_receiver_city_id";
        $joinTable .= " LEFT JOIN ref_subdistrict ON subdistrict_id = stockist_transaction_delivery_receiver_subdistrict_id";
        $joinTable .= " JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id";
        $whereCondition = " stockist_transaction_buyer_member_id = {$id} AND stockist_transaction_status IN ('pending')";
        $whereCondition .= $transaction_id ? " AND stockist_transaction_id = {$transaction_id}" : "";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["stockist_transaction_datetime_formatted"] = convertDatetime($value["stockist_transaction_datetime"], "id");

            if ($arr_data["results"][$key]["stockist_transaction_payment_image"]) {
                $arr_data["results"][$key]["stockist_transaction_payment_image"] = UPLOAD_URL . URL_IMG_PAYMENT . $value["stockist_transaction_payment_image"];
            }

            $arr_data["results"][$key]["detail"] = $this->db->table("inv_stockist_transaction_detail")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $value["stockist_transaction_id"]])->getResult();
        }

        return $arr_data;
    }

    public function edit($update, $where)
    {
        $this->db->table("inv_warehouse_transaction")->update($update, $where);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }

        $transaction = $this->db->table("inv_warehouse_transaction")->getWhere($where)->getRow();
        $transaction->detail = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $where["warehouse_transaction_id"]])->getResult();

        return $transaction;
    }

    public function edit_stockist($update, $where)
    {
        $this->db->table("inv_stockist_transaction")->update($update, $where);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }

        $transaction = $this->db->table("inv_stockist_transaction")->getWhere($where)->getRow();
        $transaction->detail = $this->db->table("inv_stockist_transaction_detail")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $where["stockist_transaction_id"]])->getResult();

        return $transaction;
    }

    public function getList()
    {
        $tableName = "inv_stockist";
        $columns = [
            "stockist_name",
            "stockist_mobilephone",
            "stockist_type",
            "city_name" => "stockist_city_name",
        ];
        $joinTable = "JOIN ref_city ON city_id = stockist_city_id";
        $whereCondition = "stockist_is_active = 1";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        return $arr_data;
    }

    public function getMemberTransaction($stockist_id, $status)
    {
        $whereCondition = "stockist_transaction_stockist_member_id = {$stockist_id}";

        if ($status == "paid") {
            $whereCondition .= " AND stockist_transaction_status = 'paid'";
        } else if ($status == "delivered") {
            $whereCondition .= " AND stockist_transaction_status = 'delivered'";
        }

        $tableName = "inv_stockist_transaction";
        $columns = [
            "stockist_transaction_id",
            "stockist_transaction_stockist_member_id",
            "stockist_transaction_code",
            "stockist_transaction_buyer_type",
            "stockist_transaction_buyer_member_id",
            "stockist_transaction_buyer_name",
            "stockist_transaction_buyer_address",
            "stockist_transaction_buyer_mobilephone",
            "stockist_transaction_total_price",
            "stockist_transaction_delivery_receiver_name",
            "stockist_transaction_delivery_receiver_mobilephone",
            "stockist_transaction_delivery_receiver_province_id",
            "stockist_transaction_delivery_receiver_city_id",
            "stockist_transaction_delivery_receiver_subdistrict_id",
            "stockist_transaction_delivery_receiver_address",
            "stockist_transaction_delivery_awb",
            "stockist_transaction_extra_discount_type",
            "stockist_transaction_extra_discount_percent",
            "stockist_transaction_extra_discount_value",
            "stockist_transaction_total_nett_price",
            "stockist_transaction_payment_cash",
            "stockist_transaction_payment_ewallet",
            "stockist_transaction_status",
            "stockist_transaction_notes",
            "stockist_transaction_delivery_method",
            "stockist_transaction_awb",
            "stockist_transaction_datetime",
            "stockist_transaction_delivery_cost",
            "stockist_transaction_delivery_courier_code",
            "stockist_transaction_delivery_courier_service",
            "province_name" => "stockist_transaction_delivery_receiver_province_name",
            "city_name" => "stockist_transaction_delivery_receiver_city_name",
            "subdistrict_name" => "stockist_transaction_delivery_receiver_subdistrict_name",
        ];
        $joinTable = "
        LEFT JOIN ref_province ON province_id = stockist_transaction_delivery_receiver_province_id
        LEFT JOIN ref_city ON city_id = stockist_transaction_delivery_receiver_city_id
        LEFT JOIN ref_subdistrict ON subdistrict_id = stockist_transaction_delivery_receiver_subdistrict_id";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['stockist_transaction_total_nett_price_formatted'] = 'Rp ' . setNumberFormat($value['stockist_transaction_total_nett_price']);
            $data['results'][$key]['stockist_transaction_delivery_cost_formatted'] = 'Rp ' . setNumberFormat($value['stockist_transaction_delivery_cost']);
            $data["results"][$key]["stockist_transaction_datetime_formatted"] = convertDatetime($value["stockist_transaction_datetime"], "id");

            if (!empty($value['stockist_transaction_status'])) {
                foreach (TRANSACTION_STATUS as $status_key => $val) {
                    if ($status_key ==  $value['stockist_transaction_status']) {
                        $data['results'][$key]['stockist_transaction_status_formatted'] = $val;
                    }
                }
            }

            if (empty($value['stockist_transaction_delivery_awb'])) {
                $data['results'][$key]['stockist_transaction_delivery_awb'] = '-';
            }

            $data["results"][$key]["stockist_transaction_detail"] = $this->db->table("inv_stockist_transaction_detail")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $value['stockist_transaction_id']])->getResult();

            foreach ($data["results"][$key]["stockist_transaction_detail"] as $key_ => $value_) {
                $data["results"][$key]["stockist_transaction_detail"][$key_]->stockist_transaction_detail_unit_nett_price_formatted = setNumberFormat($value_->stockist_transaction_detail_unit_nett_price);
                $data["results"][$key]["stockist_transaction_detail"][$key_]->stockist_transaction_detail_unit_price_formatted = setNumberFormat($value_->stockist_transaction_detail_unit_price);
            }
        }

        return $data;
    }

    public function getTransactionHistory($id, $transaction_id)
    {
        $tableName = "inv_warehouse_transaction";
        $columns = [
            "warehouse_transaction_id",
            "warehouse_transaction_datetime",
            "warehouse_transaction_code",
            "warehouse_transaction_buyer_name",
            "warehouse_transaction_total_nett_price",
            "warehouse_transaction_payment_image",
            "warehouse_transaction_notes",
            "warehouse_transaction_type",
            "warehouse_transaction_status",
            "warehouse_transaction_delivery_method",
            "warehouse_transaction_delivery_cost",
            "warehouse_transaction_delivery_receiver_name",
            "warehouse_transaction_delivery_receiver_mobilephone",
            "warehouse_transaction_delivery_receiver_address",
            "warehouse_transaction_delivery_courier_code",
            "warehouse_transaction_delivery_courier_service",
            "province_name AS warehouse_transaction_delivery_receiver_province_name",
            "city_name AS warehouse_transaction_delivery_receiver_city_name",
            "subdistrict_name AS warehouse_transaction_delivery_receiver_subdistrict_name",
        ];
        $joinTable = " LEFT JOIN ref_province ON province_id = warehouse_transaction_delivery_receiver_province_id";
        $joinTable .= " LEFT JOIN ref_city ON city_id = warehouse_transaction_delivery_receiver_city_id";
        $joinTable .= " LEFT JOIN ref_subdistrict ON subdistrict_id = warehouse_transaction_delivery_receiver_subdistrict_id";
        $whereCondition = " warehouse_transaction_buyer_member_id = {$id} AND warehouse_transaction_buyer_type = 'stockist' ";
        $whereCondition .= $transaction_id ? " AND warehouse_transaction_id = {$transaction_id}" : "";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["warehouse_transaction_datetime_formatted"] = convertDatetime($value["warehouse_transaction_datetime"], "id");

            if ($arr_data["results"][$key]["warehouse_transaction_payment_image"]) {
                $arr_data["results"][$key]["warehouse_transaction_payment_image"] = UPLOAD_URL . URL_IMG_PAYMENT . $value["warehouse_transaction_payment_image"];
            }

            $arr_data["results"][$key]["detail"] = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $value["warehouse_transaction_id"]])->getResult();
        }

        return $arr_data;
    }

    public function getSaldo($stockist_id)
    {
        $data = [];

        $ewallet = $this->db->table("sys_ewallet")->getWhere(["ewallet_member_id" => $stockist_id])->getRow();

        $data['saldo'] = $ewallet->ewallet_acc - $ewallet->ewallet_paid;
        $data['saldo_formatted'] = setNumberFormat($data['saldo']);
        $data['bank_account_name'] = $this->db->table('sys_member')->select('member_bank_account_name')->getWhere(['member_id' => $stockist_id])->getRow('member_bank_account_name');
        $data['bank_account_no'] = $this->db->table('sys_member')->select('member_bank_account_no')->getWhere(['member_id' => $stockist_id])->getRow('member_bank_account_no');
        $data['bank_name'] = $this->db->table('sys_member')->select('bank_name')->join('ref_bank', 'bank_id = member_bank_id')->getWhere(['member_id' => $stockist_id])->getRow('bank_name');

        return $data;
    }

    public function getSaldoSummary($id)
    {
        $tableName = "sys_ewallet_log";
        $columns = [
            "ewallet_log_id",
            "ewallet_log_member_id",
            "ewallet_log_value",
            "ewallet_log_type",
            "ewallet_log_note",
            "ewallet_log_datetime",
        ];
        $joinTable = "";
        $whereCondition = " ewallet_log_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['ewallet_log_value_formatted'] = 'Rp ' . setNumberFormat($value['ewallet_log_value']);
            $arr_data['results'][$key]['ewallet_log_datetime_formatted'] = convertDatetime($value['ewallet_log_datetime'], 'id');
        }

        return $arr_data;
    }
}
