<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Repeatorder_model extends Model
{
    public $request;

    public function init($request = null)
    {
        $this->request = $request;
    }

    public function getRepeatOrder($id)
    {
        $tableName = "sys_b_ro_personal";
        $columns = [
            "ro_personal_id",
            "ro_personal_serial_ro_id",
            "ro_personal_member_id",
            "ro_personal_datetime",
            "ro_personal_type",
        ];
        $joinTable = "";
        $whereCondition = " ro_personal_member_id = {$id} AND ro_personal_serial_ro_id IS NOT NULL AND ro_personal_type = 'repeatorder'";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["ro_personal_datetime_formatted"] = convertDatetime($value["ro_personal_datetime"], "id");
        }

        return $arr_data;
    }

    public function getRepeatOrderLog($id)
    {
        $tableName = "sys_ro_personal";
        $columns = [
            "ro_personal_id",
            "ro_personal_member_id",
            "ro_personal_bv",
            "ro_personal_note",
            "ro_personal_datetime",
        ];
        $joinTable = "";
        $whereCondition = " ro_personal_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["ro_personal_datetime_formatted"] = convertDatetime($value["ro_personal_datetime"], "id");
        }

        return $arr_data;
    }

    public function getRepeatOrderGroup($id)
    {
        $tableName = "sys_ro_group";
        $columns = [
            "ro_group_id",
            "ro_group_member_id",
            "ro_group_downline_member_id",
            "ro_group_level",
            "ro_group_position",
            "ro_group_bv",
            "ro_group_note",
            "ro_group_datetime",
            "member_account_username" => "ro_group_downline_username",
            "network_left_node_member_id",
            "network_right_node_member_id",
        ];
        $joinTable = " JOIN sys_member_account ON member_account_member_id = ro_group_downline_member_id";
        $joinTable .= " JOIN sys_network ON network_member_id = ro_group_member_id";
        $whereCondition = " ro_group_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["ro_group_datetime_formatted"] = convertDatetime($value["ro_group_datetime"], "id");
            if ($value["ro_group_position"] == "L") {
                $arr_data["results"][$key]["ro_group_trigger_username"] = $this->db->table("sys_member_account")->getWhere(["member_account_member_id" => $value["network_left_node_member_id"]])->getRow("member_account_username");
            } else if ($value["ro_group_position"] == "R") {
                $arr_data["results"][$key]["ro_group_trigger_username"] = $this->db->table("sys_member_account")->getWhere(["member_account_member_id" => $value["network_right_node_member_id"]])->getRow("member_account_username");
            }
        }

        return $arr_data;
    }

    public function getRepeatOrderPersonal($id)
    {
        $tableName = "sys_ro_personal";
        $columns = [
            "ro_personal_id",
            "ro_personal_member_id",
            "ro_personal_bv",
            "ro_personal_note",
            "ro_personal_datetime",
        ];
        $joinTable = "";
        $whereCondition = " ro_personal_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["ro_personal_datetime_formatted"] = convertDatetime($value["ro_personal_datetime"], "id");
        }

        return $arr_data;
    }

    public function getRank()
    {
        return $this->db->table("sys_rank")->get()->getResult();
    }

    public function getSummaryPoint($member_id)
    {
        $sql_total = "SELECT
        IFNULL (SUM(ro_balance_log_bv), 0) AS total
        FROM sys_ro_balance_log
        WHERE ro_balance_log_member_id = '{$member_id}' AND ro_balance_log_type = 'in'
        ";
        $sql_used = "SELECT
        IFNULL (SUM(ro_balance_log_bv), 0) AS total
        FROM sys_ro_balance_log
        WHERE ro_balance_log_member_id = '{$member_id}' AND ro_balance_log_type = 'out'
        ";
        $sql_balance = "SELECT
        IFNULL(ro_balance_bv, 0) AS total
        FROM sys_ro_balance
        WHERE ro_balance_member_id = '{$member_id}'
        ";
        return [
            "point_total" => (int)$this->db->query($sql_total)->getRow("total"),
            "point_used" => (int)$this->db->query($sql_used)->getRow("total"),
            "point_balance" => (int)$this->db->query($sql_balance)->getRow("total"),
        ];
    }
}
