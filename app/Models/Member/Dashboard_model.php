<?php

namespace App\Models\Member;

use CodeIgniter\Model;

class Dashboard_model extends Model
{
    //info detail member
    public function getMemberDetail($id)
    {
        $sql = "
			SELECT main_member.member_name,
			main_member.member_email,
			main_member.member_mobilephone,
			main_member.member_image,
			main_member.member_join_datetime,
			main_member.member_activation_datetime,
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

    //info jml sponsorisasi
    public function getSponsoringCount($id)
    {
        $sql = "
            SELECT COUNT(1) AS sponsoring_count
            FROM sys_network
            WHERE network_sponsor_member_id = {$id}
        ";
        $row_result = $this->db->query($sql)->getRow();
        if (!is_null($row_result)) {
            return $row_result->sponsoring_count;
        } else {
            return 0;
        }
    }

    //info point kiri kanan
    public function getMemberPoint($id)
    {
        $sql = "
            SELECT network_total_point_left,
            network_total_point_right
            FROM sys_network
            WHERE network_member_id = {$id}
        ";
        $row_result = $this->db->query($sql)->getRow();
        if (!is_null($row_result)) {
            return $row_result;
        } else {
            return false;
        }
    }

    //info saldo ewallet
    public function getMemberEwalletBalance($id)
    {
        $sql = "
            SELECT (ewallet_acc - ewallet_paid) AS ewallet_balance
            FROM sys_ewallet
            WHERE ewallet_member_id = {$id}
        ";
        $row_result = $this->db->query($sql)->getRow();
        if (!is_null($row_result)) {
            return $row_result->ewallet_balance;
        } else {
            return 0;
        }
    }

    //info perolehan bonus
    public function getMemberBonus($id)
    {
        $sql = "
            SELECT *,
            (bonus_sponsor_acc - bonus_sponsor_paid) AS bonus_sponsor_balance,
            (bonus_welcome_acc - bonus_welcome_paid) AS bonus_welcome_balance,
            (bonus_unilevel_acc - bonus_unilevel_paid) AS bonus_unilevel_balance,
            (bonus_annually_acc - bonus_annually_paid) AS bonus_annually_balance
            FROM sys_bonus
            WHERE bonus_member_id = {$id}
        ";
        $row_result = $this->db->query($sql)->getRow();
        if (!is_null($row_result)) {
            return $row_result;
        } else {
            return false;
        }
    }

    //info pertumbuhan downline terakhir
    public function getMemberLastDownline($id, $limit = 10)
    {
        $sql = "
            SELECT network_code AS downline_network_code,
            network_rank_name AS downline_network_rank_name,
            IF(netgrow_node_position = 'L', 'Kiri', 'Kanan') AS downline_position,
            netgrow_node_level AS downline_level,
            netgrow_node_date AS downline_join_date
            FROM sys_netgrow_node
            INNER JOIN sys_network ON network_member_id = netgrow_node_downline_member_id
            WHERE netgrow_node_member_id = {$id}
            ORDER BY netgrow_node_date DESC, netgrow_node_id DESC
            LIMIT {$limit}
        ";
        return $this->db->query($sql)->getResult();
    }

    //info paket saat ini
    //pertumbuhan jaringan 7 hari terakhir
    //pertumbuhan bonus 7 hari terakhir


    public function get_member_growing_per_week($member_id)
    {
        $data = [];
        $date = date("Y-m-d");
        $date = date("Y-m-d", strtotime("{$date} -6 day"));

        for ($index = 0; $index < 7; $index++) {

            $sql_member_join_count = "
                SELECT COUNT(member_id) AS member_join_count
                FROM sys_member
                JOIN sys_network ON network_member_id = member_id
                WHERE DATE(member_join_datetime) = '{$date}'
                AND network_sponsor_member_id = {$member_id}
            ";
            $member_join_count = $this->db->query($sql_member_join_count)->getRow('member_join_count');

            $sql_member_activation_count = "
                SELECT COUNT(network_member_id) AS member_activation_count
                FROM sys_network
                WHERE DATE(network_activation_datetime) = '{$date}'
                AND network_sponsor_member_id = {$member_id}
            ";
            $member_activation_count = $this->db->query($sql_member_activation_count)->getRow('member_activation_count');

            $data_graph = new \stdClass;
            $data_graph->member_date = $date;
            $data_graph->member_join_count = $member_join_count ? $member_join_count : 0;
            $data_graph->member_activation_count = $member_activation_count ? $member_activation_count : 0;
            $data[] = $data_graph;

            $date = date("Y-m-d", strtotime("{$date} +1 day"));
        }

        return $data;
    }

    public function get_ten_downline($member_id)
    {
        $sql = "
            SELECT member_id,
            member_name,
            member_gender,
            member_image,
            member_account_username,
            network_code,
            IFNULL(city_name, '-') AS member_city,
            IFNULL(province_name, '-') AS member_province,
            netgrow_node_date AS member_netgrow_node_date,
            network_position
            FROM sys_member
            JOIN sys_member_account ON member_account_member_id = member_id
            JOIN sys_network ON network_member_id = member_id
            JOIN sys_netgrow_node ON netgrow_node_downline_member_id = member_id
            LEFT JOIN ref_city ON city_id = member_city_id
            LEFT JOIN ref_province ON province_id = member_province_id
            WHERE netgrow_node_member_id = '$member_id'
            ORDER BY member_netgrow_node_date DESC
            LIMIT 10
        ";
        $data = $this->db->query($sql)->getResult("array");

        return $data;
    }

    public function get_total_downline($network_id)
    {
        $sql = "
        SELECT
            COUNT(CASE WHEN netgrow_node_position = 'L' THEN 1 END) AS total_kiri,
            COUNT(CASE WHEN netgrow_node_position = 'R' THEN 1 END) AS total_kanan
        FROM uni_netgrow_node where netgrow_node_network_id = '{$network_id}'
        ";

        $data = $this->db->query($sql)->getRow();
        $data->total_downline = $data->total_kiri + $data->total_kanan;

        return $data;
    }

    public function get_commission($member_id)
    {
        $sql = "SELECT 
            (bonus_sponsor_acc - bonus_sponsor_paid) + (bonus_annually_acc - bonus_annually_paid) as sponsor,
            (bonus_welcome_acc - bonus_welcome_paid) as welcome,
            (bonus_unilevel_acc - bonus_unilevel_paid) as unilevel,
            (bonus_annually_acc - bonus_annually_paid) as annually
        FROM sys_bonus
        WHERE bonus_member_id = {$member_id}
        ";

        return $this->db->query($sql)->getRow();
    }

    function get_total_bonus($member_id)
    {
        $sql = "SELECT 
            SUM(bonus_sponsor_acc + bonus_gen_node_acc + bonus_power_leg_acc + bonus_matching_leg_acc + bonus_cash_reward_acc) as total_bonus
        FROM sys_bonus
        LEFT JOIN sys_member ON member_id = bonus_member_id
        WHERE member_parent_member_id = {$member_id}
        ";

        return $this->db->query($sql)->getRow('total_bonus');
    }

    function get_top_reward($member_id)
    {
        $sql = "SELECT reward_qualified_reward_title
        FROM sys_reward_qualified
        LEFT JOIN sys_member ON member_id = reward_qualified_member_id
        WHERE member_parent_member_id = {$member_id}
        ORDER BY reward_qualified_condition_point DESC
        LIMIT 1
        ";

        return $this->db->query($sql)->getRow('reward_qualified_reward_title');
    }
}
