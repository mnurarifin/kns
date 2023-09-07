<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Repeatorder_services extends BaseController
{
    private $quantity;
    private $point;
    private $point_group;
    private $arr_network_upline = [];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function insert_ro($member_id, $note, $datetime, $product_package_id, $quantity)
    {
        $this->quantity = $quantity;
        $this->datetime = $datetime;
        $this->point = $this->db->table("inv_product_package")->getWhere(["product_package_id" => $product_package_id])->getRow("product_package_point") * $quantity;
        $this->point_group = $this->db->table("inv_product_package")->getWhere(["product_package_id" => $product_package_id])->getRow("product_package_group_point") * $quantity;
        $this->insert_ro_personal($member_id, $datetime, $product_package_id, $quantity, $note);
        $this->add_network_point($member_id, $datetime, $this->point);
        $this->add_netgrow_free($member_id, $datetime, $product_package_id, $quantity, $note);
        $this->insertPointLog($member_id, $this->point, "in", $note, $datetime);
        $this->insertRewardPointLog($member_id, $this->point + $this->point_group, "in", $note, $datetime);
        $this->insert_netgrow_welcome($member_id, $datetime);
    }

    private function insert_ro_personal($member_id, $datetime, $product_package_id, $quantity, $note)
    {
        $package = $this->db->table("inv_product_package")->getWhere(["product_package_id" => $product_package_id])->getRow();
        $arr_data = [];
        $arr_data['ro_personal_member_id'] = $member_id;
        $arr_data['ro_personal_product_package_id'] = $product_package_id;
        $arr_data['ro_personal_quantity'] = $quantity;
        $arr_data['ro_personal_point'] = $package->product_package_point;
        $arr_data['ro_personal_total_point'] = $package->product_package_point * $quantity;
        $arr_data['ro_personal_note'] = $note;
        $arr_data['ro_personal_datetime'] = $datetime;
        if ($this->common_model->insertData('sys_ro_personal', $arr_data) == FALSE) {
            throw new \Exception("Gagal menyimpan data ro personal.", 1);
        }

        if (empty($this->arr_network_upline)) {
            $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $member_id]));
        }
        foreach ($this->arr_network_upline as $arr_upline_data) {
            $arr_data = [];
            $arr_data['ro_group_member_id'] = $arr_upline_data->id;
            $arr_data['ro_group_downline_member_id'] = $member_id;
            $arr_data['ro_group_level'] = $arr_upline_data->level;
            $arr_data['ro_group_product_package_id'] = $product_package_id;
            $arr_data['ro_group_quantity'] = $this->quantity;
            $arr_data['ro_group_point'] = $package->product_package_group_point;
            $arr_data['ro_group_total_point'] = $package->product_package_group_point * $this->quantity;
            $arr_data['ro_group_note'] = $note;
            $arr_data['ro_group_datetime'] = $this->datetime;
            if ($this->common_model->insertData('sys_ro_group', $arr_data) == FALSE) {
                throw new \Exception("Gagal menyimpan data ro group.", 1);
            }
            $ro_group_id = $this->db->insertID();
            $products = $this->db->table("inv_product_package_detail")->join("inv_product", "product_id = product_package_detail_product_id")->getWhere(["product_package_detail_product_package_id" => $product_package_id])->getResult();
            foreach ($products as $product) {
                $arr_data = [];
                $arr_data['ro_group_detail_ro_group_id'] = $ro_group_id;
                $arr_data['ro_group_detail_product_id'] = $product->product_id;
                $arr_data['ro_group_detail_quantity'] = $product->product_package_detail_quantity;
                if ($this->common_model->insertData('sys_ro_group_detail', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menyimpan data detail ro group.", 1);
                }
            }
        }
    }

    private function add_netgrow_free($member_id, $datetime, $product_package_id, $quantity, $note)
    {
        $date = date("Y-m-d", strtotime($datetime));
        $sql = "UPDATE
        sys_netgrow_free
        SET
        netgrow_free_point = netgrow_free_point + {$this->point},
        netgrow_free_last_update_date = '{$date}',
        netgrow_free_last_update_datetime = '{$datetime}'
        WHERE netgrow_free_member_id = '{$member_id}'
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah data netgrow free.", 1);
        }
    }

    public function substract_netgrow_free($member_id, $datetime, $point, $note)
    {
        $date = date("Y-m-d", strtotime($datetime));
        $sql = "UPDATE
        sys_netgrow_free
        SET
        netgrow_free_point = netgrow_free_point - {$point},
        netgrow_free_last_update_date = '{$date}',
        netgrow_free_last_update_datetime = '{$datetime}'
        WHERE netgrow_free_member_id = '{$member_id}'
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data netgrow free.", 1);
        }

        $this->insertPointLog($member_id, $point, "out", $note, $datetime);
    }

    private function add_network_point($member_id, $datetime, $point)
    {
        $sql = "UPDATE
        sys_network
        SET
        network_point = network_point + {$point}
        WHERE network_member_id = '{$member_id}'
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data point.", 1);
        }
    }

    public function add_network_reward_point($member_id, $datetime, $point)
    {
        $sql = "UPDATE
        sys_network
        SET
        network_reward_point = network_reward_point + {$point}
        WHERE network_member_id = '{$member_id}'
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data point reward.", 1);
        }
    }

    public function insertPointLog($member_id, $point, $type, $note, $datetime)
    {
        $this->db->table("sys_network_point_log")->insert([
            "network_point_log_member_id" => $member_id,
            "network_point_log_value" => $point,
            "network_point_log_type" => $type,
            "network_point_log_note" => $note,
            "network_point_log_datetime" => $datetime,
        ]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data riwayat point.", 1);
        }
    }

    public function insertRewardPointLog($member_id, $point, $type, $note, $datetime)
    {
        $this->db->table("sys_network_reward_point_log")->insert([
            "network_reward_point_log_member_id" => $member_id,
            "network_reward_point_log_value" => $point,
            "network_reward_point_log_type" => $type,
            "network_reward_point_log_note" => $note,
            "network_reward_point_log_datetime" => $datetime,
        ]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data riwayat point reward.", 1);
        }
    }

    private function insert_netgrow_welcome($member_id, $datetime)
    {
        $month = date("m", strtotime($datetime));

        $sql = "SELECT
        SUM(ro_personal_total_point) AS ro_point
        FROM sys_ro_personal
        WHERE ro_personal_member_id = '{$member_id}'
        AND MONTH(ro_personal_datetime) = '{$month}'
        ";
        $ro_point_month = $this->db->query($sql)->getRow("ro_point");

        $sql = "SELECT
        *
        FROM sys_netgrow_welcome
        WHERE netgrow_welcome_member_id = '{$member_id}'
        AND MONTH(netgrow_welcome_date) = '{$month}'
        ";
        $netgrow_welcome = $this->db->query($sql)->getRow();

        $join_datetime = $this->db->table("sys_network")->getWhere(["network_member_id" => $member_id])->getRow("network_activation_datetime");
        $diff = $this->diffMonth($join_datetime, $datetime);

        if ($ro_point_month >= 0.5 && !$netgrow_welcome && in_array($diff, [1, 2, 3])) {
            $config_welcome = ARR_CONFIG_BONUS_WELCOME_VALUE;
            $arr_data = [];
            $arr_data["netgrow_welcome_member_id"] = $member_id;
            $arr_data["netgrow_welcome_bonus"] = $config_welcome[$diff];
            $arr_data["netgrow_welcome_date"] = date("Y-m-d", strtotime($datetime));
            $arr_data["netgrow_welcome_datetime"] = $datetime;
            if ($this->common_model->insertData("sys_netgrow_welcome", $arr_data) == FALSE) {
                throw new \Exception("Gagal menyimpan data netgrow welcome.", 1);
            }
        }
    }

    private function diffMonth(string $date1, string $date2): int
    {
        $year1 = date('Y', strtotime($date1));
        $year2 = date('Y', strtotime($date2));
        $month1 = date('m', strtotime($date1));
        $month2 = date('m', strtotime($date2));
        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        return $diff;
    }
}
