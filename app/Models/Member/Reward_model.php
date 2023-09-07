<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Reward_model extends Model
{
    public function init($request = null)
    {
        $this->request = $request;
    }

    public function getReward($id)
    {
        $return = $this->db->table("sys_reward")->get()->getResult();
        foreach ($return as $key => $value) {
            $reward_qualified = $this->db->table("sys_reward_qualified")->getWhere(["reward_qualified_member_id" => $id, "reward_qualified_reward_id" => $value->reward_id])->getRow();
            $return[$key]->reward_qualified_id = is_null($reward_qualified) ? NULL : $reward_qualified->reward_qualified_id;
            $return[$key]->reward_qualified_claim = is_null($reward_qualified) ? NULL : $reward_qualified->reward_qualified_claim;
            $return[$key]->reward_qualified_status = is_null($reward_qualified) ? NULL : $reward_qualified->reward_qualified_status;
            $return[$key]->reward_condition_point = (int) $return[$key]->reward_condition_point;

            if ($value->reward_image_filename && file_exists(UPLOAD_PATH . URL_IMG_REWARD . $value->reward_image_filename)) {
                $return[$key]->reward_image_filename = UPLOAD_URL . URL_IMG_REWARD . $value->reward_image_filename;
            } else {
                $return[$key]->reward_image_filename = BASEURL . '/app-assets/images/icon/cup.png';
            }
        }
        return $return;
    }

    public function getSummaryPoint($id)
    {
        $network_point = $this->db->table("sys_reward_point")->select("SUM(reward_point_acc) as network_point")->getWhere(["reward_point_member_id" => $id])->getRow("network_point");
        $netgrow_gen_node_point = $this->db->table("sys_netgrow_gen_node")->select("SUM(netgrow_gen_node_point) AS total")->getWhere(["netgrow_gen_node_member_id" => $id, "netgrow_gen_node_date" => date("Y-m-d")])->getRow("total");
        $netgrow_power_leg_point = $this->db->table("sys_netgrow_power_leg")->select("SUM(netgrow_power_leg_point) AS total")->getWhere(["netgrow_power_leg_member_id" => $id, "netgrow_power_leg_date" => date("Y-m-d")])->getRow("total");
        $netgrow_matching_leg_point = $this->db->table("sys_netgrow_matching_leg")->select("SUM(netgrow_matching_leg_point) AS total")->getWhere(["netgrow_matching_leg_member_id" => $id, "netgrow_matching_leg_date" => date("Y-m-d")])->getRow("total");
        $potency_point = $netgrow_gen_node_point + $netgrow_power_leg_point + $netgrow_matching_leg_point;

        $member_parent_member_id = $this->db->table("sys_member")->getWhere(["member_id" => $id])->getRow("member_parent_member_id");
        $children = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $member_parent_member_id])->getResult();
        $children_ids = [];
        foreach ($children as $child) {
            $children_ids[] = $child->member_id;
        }

        $network_point_all = $this->db->table("sys_reward_point")->select("SUM(reward_point_acc) as network_point")->whereIn("reward_point_member_id", $children_ids)->get()->getRow("network_point");
        $netgrow_gen_node_point_all = $this->db->table("sys_netgrow_gen_node")->select("SUM(netgrow_gen_node_point) AS total")->whereIn("netgrow_gen_node_member_id", $children_ids)->getWhere(["netgrow_gen_node_date" => date("Y-m-d")])->getRow("total");
        $netgrow_power_leg_point_all = $this->db->table("sys_netgrow_power_leg")->select("SUM(netgrow_power_leg_point) AS total")->whereIn("netgrow_power_leg_member_id", $children_ids)->getWhere(["netgrow_power_leg_date" => date("Y-m-d")])->getRow("total");
        $netgrow_matching_leg_point_all = $this->db->table("sys_netgrow_matching_leg")->select("SUM(netgrow_matching_leg_point) AS total")->whereIn("netgrow_matching_leg_member_id", $children_ids)->getWhere(["netgrow_matching_leg_date" => date("Y-m-d")])->getRow("total");
        $potency_point_all = $netgrow_gen_node_point_all + $netgrow_power_leg_point_all + $netgrow_matching_leg_point_all;

        return (object)[
            "network_point" => $network_point,
            "potency_point" => $potency_point,
            "network_point_all" => $network_point_all,
            "potency_point_all" => $potency_point_all,
        ];
    }

    public function getRewardLog($id)
    {
        $tableName = "sys_reward_point_log";
        $columns = [
            "reward_point_log_id",
            "reward_point_log_member_id",
            "reward_point_log_type",
            "reward_point_log_value",
            "reward_point_log_note",
            "reward_point_log_datetime",
        ];
        $joinTable = "";
        $whereCondition = " reward_point_log_member_id = {$id} AND reward_point_log_type = 'in'";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value["reward_point_log_datetime"]) {
                $arr_data["results"][$key]["reward_point_log_datetime_formatted"] = convertDatetime($value["reward_point_log_datetime"], "id");
            }
        }

        return $arr_data;
    }

    public function getRewardLogQualified($id)
    {
        $tableName = "sys_reward_qualified";
        $columns = [
            "reward_qualified_id",
            "reward_qualified_member_id",
            "reward_qualified_reward_id",
            "reward_qualified_reward_title",
            "reward_qualified_reward_value",
            "reward_qualified_condition_sponsor",
            "reward_qualified_condition_point_left",
            "reward_qualified_condition_point_right",
            "reward_qualified_condition_rank_id",
            "reward_qualified_datetime",
            "reward_qualified_status",
            "reward_qualified_status_administrator_id",
            "reward_qualified_status_datetime",
            "reward_qualified_claim",
            "reward_qualified_claim_datetime",
            "reward_trip_point"
        ];
        $joinTable = "JOIN sys_reward ON reward_id = reward_qualified_condition_rank_id";
        $whereCondition = " reward_qualified_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value["reward_qualified_datetime"]) {
                $arr_data["results"][$key]["reward_qualified_datetime_formatted"] = convertDatetime($value["reward_qualified_datetime"], "id");
            }
            if ($value["reward_trip_point"]) {
                $arr_data["results"][$key]["reward_trip_point_formatted"] = setNumberFormat($value['reward_trip_point']);
            }
            if ($value["reward_qualified_status_datetime"]) {
                $arr_data["results"][$key]["reward_qualified_status_datetime_formatted"] = convertDatetime($value["reward_qualified_status_datetime"], "id");
            }
            if ($value["reward_qualified_claim_datetime"]) {
                $arr_data["results"][$key]["reward_qualified_claim_datetime_formatted"] = convertDatetime($value["reward_qualified_claim_datetime"], "id");
            }
        }

        return $arr_data;
    }

    public function getRewardTripLog($id)
    {
        $tableName = "sys_reward_trip_point_log";
        $columns = [
            "reward_point_log_id",
            "reward_point_log_member_id",
            "reward_point_log_type",
            "reward_point_log_value",
            "reward_point_log_note",
            "reward_point_log_datetime",
        ];
        $joinTable = "";
        $whereCondition = " reward_point_log_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value["reward_point_log_datetime"]) {
                $arr_data["results"][$key]["reward_point_log_datetime_formatted"] = convertDatetime($value["reward_point_log_datetime"], "id");
            }
        }

        return $arr_data;
    }

    public function getRewardTripLogQualified($id)
    {
        $tableName = "sys_reward_trip_qualified";
        $columns = [
            "reward_qualified_id",
            "reward_qualified_member_id",
            "reward_qualified_reward_id",
            "reward_qualified_reward_title",
            "reward_qualified_reward_value",
            "reward_qualified_condition_sponsor",
            "reward_qualified_condition_point_left",
            "reward_qualified_condition_point_right",
            "reward_qualified_condition_rank_id",
            "reward_qualified_datetime",
            "reward_qualified_status",
            "reward_qualified_status_administrator_id",
            "reward_qualified_status_datetime",
            "reward_qualified_claim",
            "reward_qualified_claim_datetime",
        ];
        $joinTable = "";
        $whereCondition = " reward_qualified_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value["reward_qualified_datetime"]) {
                $arr_data["results"][$key]["reward_qualified_datetime_formatted"] = convertDatetime($value["reward_qualified_datetime"], "id");
            }
            if ($value["reward_qualified_status_datetime"]) {
                $arr_data["results"][$key]["reward_qualified_status_datetime_formatted"] = convertDatetime($value["reward_qualified_status_datetime"], "id");
            }
            if ($value["reward_qualified_claim_datetime"]) {
                $arr_data["results"][$key]["reward_qualified_claim_datetime_formatted"] = convertDatetime($value["reward_qualified_claim_datetime"], "id");
            }

            $arr_data["results"][$key]["reward_qualified_reward_value_formatted"] = 'Rp ' . setNumberFormat($value["reward_qualified_reward_value"]);
        }

        return $arr_data;
    }

    public function claimReward($update, $where)
    {
        $this->db->table("sys_reward_qualified")->update($update, $where);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }
    }
}
