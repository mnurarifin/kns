<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;
use stdClass;

class Bonus_model extends Model
{
    public function init($request = null)
    {
        $this->request = $request;
        $this->bonus_service = service("Bonus");
    }

    //info detail member
    public function getMemberDetail($id)
    {
        $sql = "
			SELECT main_member.member_name,
			main_member.member_email,
			main_member.member_mobilephone,
			main_member.member_bank_name,
			main_member.member_bank_account_name,
			main_member.member_bank_account_no,
			main_member.member_join_datetime,
			main_member.member_activation_datetime,
			main_member_account.member_account_username,
            main_network.network_code,
            IF(main_network.network_sponsor_network_code = '', '-', main_network.network_sponsor_network_code) AS sponsor_network_code,
            IF(main_network.network_upline_network_code = '', '-', main_network.network_upline_network_code) AS upline_network_code,
            IFNULL(sponsor_member_account.member_account_username, '-') AS sponsor_member_account_username,
            IFNULL(upline_member_account.member_account_username, '-') AS upline_member_account_username
			FROM sys_member main_member
			INNER JOIN sys_network main_network ON main_network.network_member_id = main_member.member_id
			INNER JOIN sys_member_account main_member_account ON main_member_account.member_account_member_id = main_member.member_id
            LEFT JOIN sys_member_account sponsor_member_account ON sponsor_member_account.member_account_member_id = main_network.network_sponsor_member_id
            LEFT JOIN sys_member_account upline_member_account ON upline_member_account.member_account_member_id = main_network.network_upline_member_id
			WHERE member_id = {$id}
		";
        $row_result = $this->db->query($sql)->getRow();
        if (!is_null($row_result)) {
            return $row_result;
        } else {
            return false;
        }
    }

    public function getMemberChildren($id)
    {
        $sql = "SELECT
            member_parent_member_id
            FROM sys_member
			WHERE member_id = {$id}
		";
        $member_parent_member_id = $this->db->query($sql)->getRow("member_parent_member_id");
        $sql = "SELECT
            member_id,
            network_code
            FROM sys_member
            JOIN sys_network ON network_member_id = member_id
			WHERE member_parent_member_id = {$member_parent_member_id}
		";
        $row_result = $this->db->query($sql)->getResult();
        if (!is_null($row_result)) {
            return $row_result;
        } else {
            return false;
        }
    }

    //info perolehan bonus
    public function getBonus($id)
    {
        $result = new \stdClass();
        $result->children = [];
        $children = [];

        $member = $this->db->table("sys_member")->getWhere(["member_id" => $id])->getRow();
        $group = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $member->member_parent_member_id])->getResult();
        foreach ($group as $key => $value) {
            $children[] = $value->member_id;

            $sql = "SELECT
                network_code,
                bonus_limit,
                bonus_sponsor_acc,
                bonus_gen_node_acc,
                bonus_power_leg_acc,
                bonus_matching_leg_acc,
                bonus_cash_reward_acc,
                bonus_sponsor_acc + bonus_gen_node_acc + bonus_power_leg_acc + bonus_matching_leg_acc + bonus_cash_reward_acc AS bonus_total_acc
                FROM sys_bonus
                JOIN sys_network ON network_member_id = bonus_member_id
                WHERE bonus_member_id = {$value->member_id}
            ";
            $result->children[] = $this->db->query($sql)->getRow();
        }

        $children = implode(",", $children);
        $date = date("Y-m-d");
        $sql = "SELECT
            SUM(bonus_limit) AS bonus_limit,
            SUM(bonus_sponsor_acc) AS bonus_sponsor_acc,
            SUM(bonus_gen_node_acc) AS bonus_gen_node_acc,
            SUM(bonus_power_leg_acc) AS bonus_power_leg_acc,
            SUM(bonus_matching_leg_acc) AS bonus_matching_leg_acc,
            SUM(bonus_cash_reward_acc) AS bonus_cash_reward_acc,
            SUM(bonus_sponsor_paid) AS bonus_sponsor_paid,
            SUM(bonus_gen_node_paid) AS bonus_gen_node_paid,
            SUM(bonus_power_leg_paid) AS bonus_power_leg_paid,
            SUM(bonus_matching_leg_paid) AS bonus_matching_leg_paid,
            SUM(bonus_cash_reward_paid) AS bonus_cash_reward_paid,
            SUM(bonus_sponsor_acc - bonus_sponsor_paid) AS bonus_sponsor_balance,
            SUM(bonus_gen_node_acc - bonus_gen_node_paid) AS bonus_gen_node_balance,
            SUM(bonus_power_leg_acc - bonus_power_leg_paid) AS bonus_power_leg_balance,
            SUM(bonus_matching_leg_acc - bonus_matching_leg_paid) AS bonus_matching_leg_balance,
            SUM(bonus_cash_reward_acc - bonus_cash_reward_paid) AS bonus_cash_reward_balance
            FROM sys_bonus
            WHERE bonus_member_id in ({$children})
        ";
        $result->summary = $this->db->query($sql)->getRow();

        $this->bonus_service->set_potency(TRUE);
        $result->summary->bonus_sponsor_today = $this->bonus_service->calculate_sponsor($date, session("member")["member_id"]) ?? "0";
        $result->summary->bonus_gen_node_today = $this->bonus_service->calculate_gen_node($date, session("member")["member_id"]) ?? "0";
        $result->summary->bonus_power_leg_today = $this->bonus_service->calculate_power_leg($date, session("member")["member_id"]) ?? "0";
        $result->summary->bonus_matching_leg_today = $this->bonus_service->calculate_matching_leg($date, session("member")["member_id"]) ?? "0";
        // $result->summary->bonus_cash_reward_today = $this->bonus_service->calculate_cash_reward($date, session("member")["member_id"]) ?? "0";

        return $result;
    }

    //info riwayat perolehan bonus
    public function getBonusLog($id, $month, $year)
    {

        $tableName = "sys_bonus_log";
        $columns = [
            'bonus_log_id',
            'bonus_log_member_id',
            'bonus_log_date',
            'bonus_log_sponsor',
            'bonus_log_gen_node',
            'bonus_log_power_leg',
            'bonus_log_matching_leg',
            'bonus_log_cash_reward',
            'bonus_log_sponsor + bonus_log_gen_node + bonus_log_power_leg + bonus_log_matching_leg + bonus_log_cash_reward' => 'total',
        ];
        $joinTable = "";
        $whereCondition = "bonus_log_member_id = {$id} AND month(bonus_log_date) = '{$month}' AND year(bonus_log_date) = {$year}";
        $groupBy = "";

        $this->dataTable = new DataTable();

        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['bonus_log_date_formatted'] = convertDatetime($value['bonus_log_date'], 'id');
        }

        return $arr_data;
    }

    //info riwayat perolehan bonus
    public function getBonusTransfer($id, $bonus_transfer_id)
    {
        $tableName = "sys_bonus_transfer";
        $columns = [
            "bonus_transfer_id",
            "bonus_transfer_member_id",
            'bonus_transfer_sponsor',
            'bonus_transfer_gen_node',
            'bonus_transfer_power_leg',
            'bonus_transfer_matching_leg',
            'bonus_transfer_cash_reward',
            "bonus_transfer_total",
            "bonus_transfer_member_bank_id",
            "bonus_transfer_member_bank_name",
            "bonus_transfer_member_bank_account_name",
            "bonus_transfer_member_bank_account_no",
            "bonus_transfer_adm_charge_type",
            "bonus_transfer_adm_charge_percent",
            "bonus_transfer_adm_charge_value",
            "bonus_transfer_date",
            "bonus_transfer_datetime",
            "bonus_transfer_status",
            "bonus_transfer_tax_value",
            'bonus_transfer_sponsor + bonus_transfer_gen_node + bonus_transfer_power_leg + bonus_transfer_matching_leg + bonus_transfer_cash_reward' => 'total',
        ];
        $joinTable = "";
        $whereCondition = "bonus_transfer_member_id = {$id}";
        if ($bonus_transfer_id) {
            $whereCondition .= " AND bonus_transfer_id = {$bonus_transfer_id}";
        }
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['bonus_transfer_date_formatted'] = convertDatetime($value['bonus_transfer_date'], 'id');
        }

        if ($bonus_transfer_id) {
            $result = array_search($bonus_transfer_id, array_column($arr_data["results"], 'bonus_transfer_id'));
            $arr_data["results"][$result]["member_account_username"] = $this->db->table("sys_member_account")->getWhere(["member_account_member_id" => $arr_data["results"][$result]["bonus_transfer_member_id"]])->getRow("member_account_username");
            $arr_data["results"] = $arr_data["results"][$result];
            unset($arr_data["pagination"]);
        }

        return $arr_data;
    }

    public function getBonusSponsor($member_id, $date)
    {
        $this->db->query("SET @row := 0;");
        $sql = "SELECT
        @row := @row + 1 as seq,
        netgrow_sponsor_member_id AS member_id,
        network_code AS downline_username,
        netgrow_sponsor_bonus AS bonus_value,
        TIME(netgrow_sponsor_datetime) AS bonus_time
        FROM sys_netgrow_sponsor
        JOIN sys_network ON network_member_id = netgrow_sponsor_downline_member_id
        WHERE netgrow_sponsor_date = '{$date}'
        AND netgrow_sponsor_member_id = '{$member_id}'
        ORDER BY netgrow_sponsor_datetime ASC
        ";
        $result = ["results" => $this->db->query($sql)->getResult() ?? []];

        foreach ($result["results"] as $key => $value) {
            $result["results"][$key]->bonus_note = "Komisi Sponsor dari Aktivasi {$value->downline_username}.";
        }

        return $result;
    }

    public function getBonusGenNode($member_id, $date)
    {
        $this->db->query("SET @row := 0;");
        $sql = "SELECT
        @row := @row + 1 as seq,
        netgrow_gen_node_member_id AS member_id,
        network_code AS downline_username,
        netgrow_gen_node_bonus AS bonus_value,
        TIME(netgrow_gen_node_datetime) AS bonus_time
        FROM sys_netgrow_gen_node
        JOIN sys_network ON network_member_id = netgrow_gen_node_trigger_member_id
        WHERE netgrow_gen_node_date = '{$date}'
        AND netgrow_gen_node_member_id = '{$member_id}'
        ORDER BY netgrow_gen_node_datetime ASC
        ";
        $result = ["results" => $this->db->query($sql)->getResult() ?? []];

        foreach ($result["results"] as $key => $value) {
            $result["results"][$key]->bonus_note = "Komisi Generasi dari Aktivasi {$value->downline_username}.";
        }

        return $result;
    }

    public function getBonusPowerLeg($member_id, $date)
    {
        $this->db->query("SET @row := 0;");
        $sql = "SELECT
        @row := @row + 1 as seq,
        netgrow_power_leg_member_id AS member_id,
        network_code AS downline_username,
        netgrow_power_leg_bonus AS bonus_value,
        TIME(netgrow_power_leg_datetime) AS bonus_time
        FROM sys_netgrow_power_leg
        JOIN sys_network ON network_member_id = netgrow_power_leg_trigger_member_id
        WHERE netgrow_power_leg_date = '{$date}'
        AND netgrow_power_leg_member_id = '{$member_id}'
        ORDER BY netgrow_power_leg_datetime ASC
        ";
        $result = ["results" => $this->db->query($sql)->getResult() ?? []];

        foreach ($result["results"] as $key => $value) {
            $result["results"][$key]->bonus_note = "Komisi Power Leg dari Aktivasi {$value->downline_username}.";
        }

        return $result;
    }

    public function getBonusMatchingLeg($member_id, $date)
    {
        $this->db->query("SET @row := 0;");
        $sql = "SELECT
        @row := @row + 1 as seq,
        netgrow_matching_leg_member_id AS member_id,
        network_code AS downline_username,
        netgrow_matching_leg_bonus AS bonus_value,
        TIME(netgrow_matching_leg_datetime) AS bonus_time
        FROM sys_netgrow_matching_leg
        JOIN sys_network ON network_member_id = netgrow_matching_leg_trigger_member_id
        WHERE netgrow_matching_leg_date = '{$date}'
        AND netgrow_matching_leg_member_id = '{$member_id}'
        ORDER BY netgrow_matching_leg_datetime ASC
        ";
        $result = ["results" => $this->db->query($sql)->getResult() ?? []];

        foreach ($result["results"] as $key => $value) {
            $result["results"][$key]->bonus_note = "Komisi Matching Leg dari Power Leg {$value->downline_username}.";
        }

        return $result;
    }

    public function getDetailBonus($id)
    {
        $member_parent_member_id = $this->db->table("sys_member")->getWhere(["member_id" => $id])->getRow("member_parent_member_id");
        $tableName = "sys_member";
        $columns = [
            'member_id',
            'network_code',
        ];
        $joinTable = " JOIN sys_network ON network_member_id = member_id";
        $whereCondition = "member_parent_member_id = {$member_parent_member_id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $sql = "SELECT
                bonus_limit,
                bonus_sponsor_acc,
                bonus_gen_node_acc,
                bonus_power_leg_acc,
                bonus_matching_leg_acc,
                bonus_cash_reward_acc,
                bonus_sponsor_acc + bonus_gen_node_acc + bonus_power_leg_acc + bonus_matching_leg_acc AS bonus_total_bonus_acc,
                bonus_sponsor_acc + bonus_gen_node_acc + bonus_power_leg_acc + bonus_matching_leg_acc + bonus_cash_reward_acc AS bonus_total_acc,
                bonus_sponsor_paid + bonus_gen_node_paid + bonus_power_leg_paid + bonus_matching_leg_paid + bonus_cash_reward_paid AS bonus_total_paid
                FROM sys_bonus
                JOIN sys_network ON network_member_id = bonus_member_id
                WHERE bonus_member_id = {$value['member_id']}
            ";

            $detail_bonus = $this->db->query($sql)->getRow();
            $arr_data['results'][$key]['bonus_total_bonus'] = $detail_bonus->bonus_total_bonus_acc;
            $arr_data['results'][$key]['bonus_total_reward'] = $detail_bonus->bonus_cash_reward_acc;
            $arr_data['results'][$key]['bonus_total_acc'] = $detail_bonus->bonus_total_acc;
            $arr_data['results'][$key]['bonus_total_paid'] = $detail_bonus->bonus_total_paid;
            $arr_data['results'][$key]['bonus_limit'] = $detail_bonus->bonus_limit;
            $arr_data['results'][$key]['summary'] = [];
            $label = [
                "bonus_sponsor_acc" => "Sponsor",
                "bonus_gen_node_acc" => "Generasi",
                "bonus_power_leg_acc" => "Power Leg",
                "bonus_matching_leg_acc" => "Matching Leg",
                "bonus_cash_reward_acc" => "Cash Reward",
            ];
            foreach ($label as $i => $detail) {
                $arr_data['results'][$key]['summary'][] = [
                    "label" => $label[$i],
                    "value" => $detail_bonus->$i,
                ];
            }
            $date = date("Y-m-d");
            $this->bonus_service->set_potency(TRUE);
            $arr_data['results'][$key]['potency']['bonus_sponsor_today'] = [
                "label" => "Sponsor",
                "value" => $this->bonus_service->calculate_sponsor($date, session("member")["member_id"]) ?? "0",
            ];
            $arr_data['results'][$key]['potency']['bonus_gen_node_today'] = [
                "label" => "Generasi",
                "value" => $this->bonus_service->calculate_gen_node($date, session("member")["member_id"]) ?? "0",
            ];
            $arr_data['results'][$key]['potency']['bonus_power_leg_today'] = [
                "label" => "Power Leg",
                "value" => $this->bonus_service->calculate_power_leg($date, session("member")["member_id"]) ?? "0",
            ];
            $arr_data['results'][$key]['potency']['bonus_matching_leg_today'] = [
                "label" => "Matching Leg",
                "value" => $this->bonus_service->calculate_matching_leg($date, session("member")["member_id"]) ?? "0",
            ];
        }

        return $arr_data;
    }
}
