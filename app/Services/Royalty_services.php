<?php
namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;

class Royalty_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function calculate_royalty_leadership($datetime) {
        $this->datetime = $datetime;
        $this->date = date("Y-m-d", strtotime($datetime));
        $this->month = date("Y-m", strtotime($datetime));

        $sql = "SELECT
        SUM(income_log_value) AS income_log_value
        FROM report_income_log
        WHERE income_log_type = 'repeatorder' AND LEFT(income_log_datetime, 7) = '{$this->month}'
        ";
        $omzet_ro = $this->db->query($sql)->getRow("income_log_value");

        $sql = "SELECT
        *
        FROM sys_config_leadership
        ORDER BY leadership_id ASC
        ";
        $leaderships = $this->db->sql($sql)->getResult();
        foreach ($leaderships as $leadership) {
            $sql = "SELECT
            netgrow_leadership_member_id,
            SUM(CASE netgrow_leadership_position = 'L' THEN 1 ELSE 0 END) AS netgrow_leadership_left,
            SUM(CASE netgrow_leadership_position = 'R' THEN 1 ELSE 0 END) AS netgrow_leadership_right,
            netgrow_leadership_type
            FROM sys_netgrow_leadership
            WHERE netgrow_leadership_type = '{$leadership->leadership_id}'
            GROUP BY netgrow_leadership_member_id, netgrow_leadership_type
            ";
            $netgrow_leaderships = $this->db->sql($sql)->getResult();
            foreach ($netgrow_leaderships as $netgrow_leadership) {
                if ($netgrow_leadership->netgrow_leadership_left >= $leadership->leadership_downline_left && $netgrow_leadership->netgrow_leadership_right >= $leadership->leadership_downline_right) {
                    $bonus = $leadership->leadership_bonus_percent / 100 * $omzet_ro;

                    // untuk bonus
                    $arr_data = [
                        "netgrow_royalty_leadership_member_id" => $netgrow_leadership->netgrow_leadership_member_id,
                        "netgrow_royalty_leadership_type" => $leadership->leadership_name,
                        "netgrow_royalty_leadership_bonus" => $bonus,
                        "netgrow_royalty_leadership_date" => $this->date,
                        "netgrow_royalty_leadership_datetime" => $this->datetime,
                    ];
                    if($this->common_model->insertData('sys_netgrow_royalty_leadership', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambahkan data netgrow royalty leadership.", 1);
                    }

                    $arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member->member_id]));
                    foreach($arr_network_upline as $arr_upline_data) {
                        // untuk royalty leadership
                        $upline_rank_id = $this->db->table("sys_network")->getWhere(["network_member_id" => $arr_upline_data->id])->getRow("network_rank_id");
                        // minimal platinum
                        if ($upline_rank_id >= 3) {
                            $arr_data = [
                                "netgrow_leadership_member_id" => $arr_upline_data->id,
                                "netgrow_leadership_position" => $arr_upline_data->pos,
                                "netgrow_leadership_type" => $leadership->leadership_id + 1,
                                "netgrow_leadership_date" => $this->date,
                                "netgrow_leadership_datetime" => $this->datetime,
                            ];
                            if($this->common_model->insertData("sys_netgrow_leadership", $arr_data) == FALSE) {
                                throw new \Exception("Gagal menyimpan data netgrow leadership.", 1);
                            }
                        }
                    }
                }
            }
        }
    }

    public function calculate_royalty_qualified($datetime) {
        $this->datetime = $datetime;
        $this->date = date("Y-m-d", strtotime($datetime));
        $this->month = date("Y-m", strtotime($datetime));

        $sql = "SELECT
        SUM(income_log_value) AS income_log_value
        FROM report_income_log
        WHERE income_log_type = 'repeatorder' AND LEFT(income_log_datetime, 7) = '{$this->month}'
        ";
        $omzet_ro = $this->db->query($sql)->getRow("income_log_value");

        $sql = "SELECT
        *
        FROM sys_config_qualified
        ORDER BY qualified_id ASC
        ";
        $qualifieds = $this->db->sql($sql)->getResult();
        foreach ($qualifieds as $qualified) {
            $networks = $this->db->table("sys_network")->getWhere(["network_rank_id >=" => "1"])->getResult();

            foreach ($networks as $key => $network) {
                $sql = "SELECT
                COUNT(netgrow_sponsor_downline_member_id) AS total_sponsoring
                FROM sys_netgrow_sponsor
                JOIN sys_network ON network_id = netgrow_sponsor_downline_member_id
                WHERE netgrow_sponsor_member_id = '{$network->network_member_id}' AND network_rank_id >= '1'";
                $sponsoring = $this->db->query($sql)->getRow();

                $sql = "SELECT
                SUM(CASE ro_group_position = 'L' THEN 1 ELSE 0 END) AS ro_group_left,
                SUM(CASE ro_group_position = 'R' THEN 1 ELSE 0 END) AS ro_group_right
                FROM
                sys_ro_group
                WHERE ro_group_member_id = '{$network->network_member_id}'";
                $ro_group = $this->db->query($sql)->getRow();

                if ($sponsoring->total_sponsoring >= $qualified->qualified_sponsor && $ro_group->ro_group_left >= $qualified->qualified_downline_left && $ro_group->ro_group_right >= $qualified->qualified_downline_right) {
                    $bonus = $qualified->qualified_bonus_percent / 100 * $omzet_ro;

                    // untuk bonus
                    $arr_data = [
                        "netgrow_royalty_qualified_member_id" => $network->network_member_id,
                        "netgrow_royalty_qualified_type" => $qualified->qualified_name,
                        "netgrow_royalty_qualified_bonus" => $bonus,
                        "netgrow_royalty_qualified_date" => $this->date,
                        "netgrow_royalty_qualified_datetime" => $this->datetime,
                    ];
                    if($this->common_model->insertData('sys_netgrow_royalty_qualified', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambahkan data netgrow royalty qualified.", 1);
                    }
                }
            }
        }
    }
}
