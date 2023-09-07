<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;
use App\Models\Common_model;

class Transaction_model extends Model
{
    public $request;

    public function init($request = null)
    {
        $this->request = $request;
        $this->common_model = new Common_model();
    }

    public function getTransaction($id, $transaction_id)
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
            "warehouse_transaction_delivery_method",
            "warehouse_transaction_delivery_cost",
            "warehouse_transaction_delivery_awb",
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
        $whereCondition = " warehouse_transaction_buyer_member_id = {$id} AND warehouse_transaction_status IN ('complete')";
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
        $whereCondition = " warehouse_transaction_buyer_member_id = {$id}";
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

    public function getTransactionSellerHistory($id, $transaction_id)
    {
        $tableName = "inv_stockist_transaction";
        $columns = [
            "stockist_transaction_id",
            "stockist_transaction_stockist_member_id",
            "stockist_transaction_datetime",
            "stockist_transaction_code",
            "stockist_transaction_buyer_member_id",
            "stockist_transaction_buyer_name",
            "stockist_transaction_total_price",
            "stockist_transaction_notes",
            "stockist_transaction_buyer_type",
            "stockist_transaction_status",
            "stockist_transaction_buyer_name",
            "stockist_transaction_buyer_mobilephone",
            "stockist_transaction_buyer_address",
            "stockist_transaction_delivery_receiver_name",
            "stockist_transaction_delivery_receiver_mobilephone",
            "stockist_transaction_delivery_receiver_province_id",
            "stockist_transaction_delivery_receiver_city_id",
            "stockist_transaction_delivery_receiver_subdistrict_id",
            "stockist_transaction_delivery_receiver_address",
            "province_name AS stockist_transaction_delivery_receiver_province_name",
            "stockist_transaction_delivery_receiver_city_id",
            "city_name AS stockist_transaction_delivery_receiver_city_name",
            "stockist_transaction_delivery_receiver_subdistrict_id",
            "subdistrict_name AS stockist_transaction_delivery_receiver_subdistrict_name",
        ];
        $joinTable = "
            LEFT JOIN ref_subdistrict ON subdistrict_id = stockist_transaction_delivery_receiver_subdistrict_id
            LEFT JOIN ref_city ON city_id = stockist_transaction_delivery_receiver_city_id
            LEFT JOIN ref_province ON province_id = stockist_transaction_delivery_receiver_province_id
        ";
        $whereCondition = " stockist_transaction_stockist_member_id = {$id}";
        $whereCondition .= $transaction_id ? " AND stockist_transaction_id = {$transaction_id}" : "";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["stockist_transaction_datetime_formatted"] = convertDatetime($value['stockist_transaction_datetime'], 'id');
            $arr_data["results"][$key]["detail"] = $this->db->table("inv_stockist_transaction_detail")->join("inv_product", "product_id = stockist_transaction_detail_product_id")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $value["stockist_transaction_id"]])->getResult();
        }

        return $arr_data;
    }

    public function getTransactionPayment($id, $transaction_id)
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
            "warehouse_transaction_payment_invoice_url",
            "warehouse_transaction_delivery_courier_service",
            "province_name AS warehouse_transaction_delivery_receiver_province_name",
            "city_name AS warehouse_transaction_delivery_receiver_city_name",
            "subdistrict_name AS warehouse_transaction_delivery_receiver_subdistrict_name",
        ];
        $joinTable = " LEFT JOIN ref_province ON province_id = warehouse_transaction_delivery_receiver_province_id";
        $joinTable .= " LEFT JOIN ref_city ON city_id = warehouse_transaction_delivery_receiver_city_id";
        $joinTable .= " LEFT JOIN ref_subdistrict ON subdistrict_id = warehouse_transaction_delivery_receiver_subdistrict_id";
        $joinTable .= " LEFT JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id";
        $whereCondition = " warehouse_transaction_buyer_member_id = {$id} AND warehouse_transaction_status IN ('pending')";
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

    public function getTransactionWarehouseDelivered($id)
    {
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
        warehouse_transaction_buyer_type = 'member' AND
        case 
            when warehouse_transaction_delivery_method = 'pickup' then  warehouse_transaction_status in ('paid','delivered')
            when warehouse_transaction_delivery_method = 'courier' then  warehouse_transaction_status in ('paid', 'delivered')
        end
        ";

        // print_r($whereCondition);
        // die();
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
        }

        return $arr_data;
    }

    public function getTransactionStockistDelivered($id)
    {
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
        stockist_transaction_buyer_type = 'member' AND
        case 
            when stockist_transaction_delivery_method = 'pickup' then  stockist_transaction_status in ('paid','delivered')
            when stockist_transaction_delivery_method = 'courier' then  stockist_transaction_status in ('paid','delivered')
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
        }

        return $arr_data;
    }

    public function getPointLog($id)
    {
        $tableName = "sys_ro_balance_log";
        $columns = [
            "ro_balance_log_id",
            "ro_balance_log_member_id",
            "ro_balance_log_value",
            "ro_balance_log_type",
            "ro_balance_log_warehouse_transaction_id",
            "ro_balance_log_datetime",
        ];
        $joinTable = "";
        $whereCondition = " ro_balance_log_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["ro_point_log_datetime_formatted"] = convertDatetime($value["ro_balance_log_datetime"], "id");
            $arr_data["results"][$key]["transaction"] = $this->db->table("inv_warehouse_transaction")->getWhere(["warehouse_transaction_id" => $value["ro_balance_log_warehouse_transaction_id"]])->getResult();
            $arr_data["results"][$key]["transaction"]["detail"] = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $value["ro_balance_log_warehouse_transaction_id"]])->getResult();
        }

        return $arr_data;
    }

    public function getPlanActivity($id)
    {
        $tableName = "sys_member_plan_activity";
        $columns = [
            "member_plan_activity_id",
            "member_plan_activity_member_id",
            "member_plan_activity_value",
            "member_plan_activity_type",
            "member_plan_activity_note",
            "member_plan_activity_datetime",
        ];
        $joinTable = "";
        $whereCondition = " member_plan_activity_member_id = {$id} AND member_plan_activity_type = 'repeatorder'";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $word = preg_split("/[\s,_-]+/", $value['member_plan_activity_note']);

            $trx_detail = $this->db->table("inv_stockist_transaction")->select("stockist_transaction_delivery_awb, stockist_transaction_delivery_courier_code, stockist_transaction_delivery_method, stockist_transaction_stockist_member_id")->getWhere(["stockist_transaction_code" => $word[5]])->getRow();

            if ($trx_detail == null) {
                $trx_detail = $this->db->table("inv_warehouse_transaction")->select("warehouse_transaction_delivery_awb, warehouse_transaction_delivery_courier_code, warehouse_transaction_delivery_method")->getWhere(["warehouse_transaction_code" => $word[5]])->getRow();

                if ($trx_detail == null) {
                    $arr_data["results"][$key]["warehouse_transaction_delivery_awb"] = '';
                    $arr_data["results"][$key]["warehouse_transaction_delivery_courier_code"] = '';
                    $arr_data["results"][$key]["warehouse_transaction_delivery_method"] = '';
                    $arr_data["results"][$key]["member_plan_activity_datetime_formatted"] = '';
                    $arr_data["results"][$key]["transaction_stockist_name"] = '';
                } else {
                    $arr_data["results"][$key]["warehouse_transaction_delivery_awb"] = $trx_detail->warehouse_transaction_delivery_awb;
                    $arr_data["results"][$key]["warehouse_transaction_delivery_courier_code"] = $trx_detail->warehouse_transaction_delivery_courier_code;
                    $arr_data["results"][$key]["warehouse_transaction_delivery_method"] = $trx_detail->warehouse_transaction_delivery_method;
                    $arr_data["results"][$key]["member_plan_activity_datetime_formatted"] = convertDatetime($value["member_plan_activity_datetime"], "id");
                    $arr_data["results"][$key]["transaction_stockist_name"] = 'Perusahaan';
                }
            } else {
                $arr_data["results"][$key]["warehouse_transaction_delivery_awb"] = $trx_detail->stockist_transaction_delivery_awb;
                $arr_data["results"][$key]["warehouse_transaction_delivery_courier_code"] = $trx_detail->stockist_transaction_delivery_courier_code;
                $arr_data["results"][$key]["warehouse_transaction_delivery_method"] = $trx_detail->stockist_transaction_delivery_method;
                $arr_data["results"][$key]["member_plan_activity_datetime_formatted"] = convertDatetime($value["member_plan_activity_datetime"], "id");
                $arr_data["results"][$key]["transaction_stockist_name"] = $this->db->table("inv_stockist")->select('stockist_name')->getWhere(["stockist_member_id" => $trx_detail->stockist_transaction_stockist_member_id])->getRow('stockist_name');
            }
        }

        return $arr_data;
    }

    public function editWarehouse($update, $where)
    {
        $this->db->table("inv_warehouse_transaction")->update($update, $where);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }

        return $this->db->table("inv_warehouse_transaction")->getWhere($where)->getRow();
    }

    public function editStockist($update, $where)
    {
        $this->db->table("inv_stockist_transaction")->update($update, $where);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }

        return $this->db->table("inv_stockist_transaction")->getWhere($where)->getRow();
    }

    public function getMemberAddress($id)
    {
        $sql = "SELECT member_name, member_mobilephone, member_address, member_province_id, member_city_id, member_subdistrict_id, ewallet_acc-ewallet_paid as ewallet_acc FROM sys_member JOIN sys_ewallet ON ewallet_member_id = member_id WHERE member_id = '{$id}'";
        return $this->db->query($sql)->getRow();
    }

    public function getFreeProduct($id)
    {
        $sql = "SELECT netgrow_free_point FROM sys_netgrow_free WHERE netgrow_free_member_id = '{$id}'";
        $point = $this->db->query($sql)->getRow("netgrow_free_point");
        $free_product = floor($point / 2);
        $free = FALSE;
        if ($free_product > 0) {
            $free = TRUE;
        }
        return [
            "free" => $free,
            "free_product" => $free_product,
        ];
    }

    public function getSummaryPoint($member_id)
    {
        $sql_total = "SELECT
        IFNULL (SUM(ro_balance_log_value), 0) AS total
        FROM sys_ro_balance_log
        WHERE ro_balance_log_member_id = '{$member_id}' AND ro_balance_log_type = 'in'
        ";
        $sql_used = "SELECT
        IFNULL (SUM(ro_balance_log_value), 0) AS total
        FROM sys_ro_balance_log
        WHERE ro_balance_log_member_id = '{$member_id}' AND ro_balance_log_type = 'out'
        ";
        $sql_balance = "SELECT
        IFNULL(ro_balance_value, 0) AS total
        FROM sys_ro_balance
        WHERE ro_balance_member_id = '{$member_id}'
        ";
        return [
            "point_total" => (int)$this->db->query($sql_total)->getRow("total"),
            "point_used" => (int)$this->db->query($sql_used)->getRow("total"),
            "point_balance" => (int)$this->db->query($sql_balance)->getRow("total"),
        ];
    }

    public function validateMinPrice($total_price)
    {
        $qualified = FALSE;
        $rank = $this->db->table("sys_rank")->orderBy("rank_bv", "ASC")->get()->getResult();

        foreach ($rank as $key => $value) {
            if ($total_price >= $value->rank_nominal_qualified) {
                $qualified = TRUE;
                break;
            }
        }

        return $qualified;
    }

    public function getDetailTransaction($transaction_id)
    {
        $sql_transaction = "
        SELECT
        stockist_transaction_id,
        stockist_transaction_stockist_member_id,
        stockist_transaction_code,
        stockist_transaction_buyer_type,
        stockist_transaction_buyer_member_id,
        stockist_transaction_buyer_name,
        stockist_transaction_buyer_address,
        stockist_transaction_buyer_mobilephone,
        stockist_transaction_total_price,
        stockist_transaction_extra_discount_type,
        stockist_transaction_extra_discount_percent,
        stockist_transaction_extra_discount_value,
        stockist_transaction_delivery_method,
        stockist_transaction_delivery_courier_code,
        stockist_transaction_delivery_courier_service,
        stockist_transaction_delivery_cost,
        stockist_transaction_delivery_receiver_name,
        stockist_transaction_delivery_receiver_mobilephone,
        stockist_transaction_delivery_receiver_province_id,
        province_name AS stockist_transaction_delivery_receiver_province_name,
        stockist_transaction_delivery_receiver_city_id,
        city_name AS stockist_transaction_delivery_receiver_city_name,
        stockist_transaction_delivery_receiver_subdistrict_id,
        subdistrict_name AS stockist_transaction_delivery_receiver_subdistrict_name,
        stockist_transaction_delivery_receiver_address,
        stockist_transaction_delivery_awb,
        stockist_transaction_payment_image,
        stockist_transaction_total_nett_price,
        stockist_transaction_payment_cash,
        stockist_transaction_payment_ewallet,
        stockist_transaction_notes,
        stockist_transaction_status,
        stockist_transaction_status_datetime,
        stockist_transaction_datetime,
        stockist_transaction_type,
        stockist_transaction_awb,
        courier_name,
        network_code
        FROM inv_stockist_transaction
        LEFT JOIN sys_network ON network_member_id = stockist_transaction_buyer_member_id
        LEFT JOIN ref_courier ON courier_code = stockist_transaction_delivery_courier_code
        LEFT JOIN ref_subdistrict ON subdistrict_id = stockist_transaction_delivery_receiver_subdistrict_id
        LEFT JOIN ref_city ON city_id = stockist_transaction_delivery_receiver_city_id
        LEFT JOIN ref_province ON province_id = stockist_transaction_delivery_receiver_province_id
        WHERE stockist_transaction_id = '$transaction_id'
        ";

        $result_transaction = $this->db->query($sql_transaction)->getRow();

        $result_transaction->stockist_transaction_delivery_cost_formatted = setNumberFormat($result_transaction->stockist_transaction_delivery_cost);
        $result_transaction->stockist_transaction_total_nett_price_formatted = setNumberFormat($result_transaction->stockist_transaction_total_nett_price);
        if ($result_transaction->stockist_transaction_delivery_courier_code != '') {
            $result_transaction->stockist_transaction_delivery_courier_code = strtoupper($result_transaction->stockist_transaction_delivery_courier_code);
        }

        if ($result_transaction->stockist_transaction_datetime) {
            $result_transaction->stockist_transaction_datetime_formatted = convertDatetime($result_transaction->stockist_transaction_datetime, 'id');
        }

        foreach (TRANSACTION_STATUS as $status_key => $val) {
            if ($status_key == $result_transaction->stockist_transaction_status) {
                $result_transaction->stockist_transaction_status_formatted = ucfirst($val);
            }
        }

        if ($result_transaction) {
            $sql_transaction_detail = "
            SELECT 
            stockist_transaction_detail_id,
            stockist_transaction_detail_stockist_transaction_id,
            stockist_transaction_detail_product_id,
            stockist_transaction_detail_product_code,
            stockist_transaction_detail_product_name,
            stockist_transaction_detail_unit_price,
            stockist_transaction_detail_discount_type,
            stockist_transaction_detail_discount_percent,
            stockist_transaction_detail_discount_value,
            stockist_transaction_detail_unit_nett_price,
            stockist_transaction_detail_quantity,
            product_weight
            FROM inv_stockist_transaction_detail
            JOIN inv_product ON product_id = stockist_transaction_detail_product_id
            WHERE stockist_transaction_detail_stockist_transaction_id = '{$result_transaction->stockist_transaction_id}'
            ";

            $result_transaction->stockist_transaction_detail = $this->db->query($sql_transaction_detail)->getResult();

            foreach ($result_transaction->stockist_transaction_detail as $key => $value) {
                $result_transaction->stockist_transaction_detail[$key]->stockist_transaction_detail_unit_nett_price_formatted = setNumberFormat($value->stockist_transaction_detail_unit_nett_price);
            }
        }

        return $result_transaction;
    }

    public function check_clone($member_id)
    {
        $member = $this->db->table("sys_member")->getWhere(["member_id" => $member_id, "member_parent_member_id" => $member_id])->getRow();
        if (is_null($member)) {
            return TRUE;
        }
        return FALSE;
    }
}
