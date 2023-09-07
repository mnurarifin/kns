<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Netgrow_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->mlm_service = service('Mlm');
        $this->member_id = null;
        $this->product_package_id = null;
        $this->product_package_price = 0;
        $this->transaction_price = 0;
        $this->datetime = date('Y-m-d H:i:s');
        $this->date = date('Y-m-d', strtotime($this->datetime));
        $this->yesterday = date("Y-m-d", strtotime($this->date . "-1 day"));

        $this->power_leg_member_id = FALSE;
    }

    public function insert_netgrow($member_id)
    {
        $this->member_id = $member_id;

        $this->insert_netgrow_node();
        $this->insert_netgrow_sponsor();
        $this->insert_netgrow_gen_node();
        $this->insert_netgrow_power_leg();
        $this->insert_netgrow_matching_leg();
    }

    public function upgrade_netgrow($member_id)
    {
        $this->member_id = $member_id;

        $this->insert_netgrow_sponsor();
    }

    private function insert_netgrow_node()
    {
        $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member_id]));

        foreach ($this->arr_network_upline as $arr_upline_data) {
            $arr_upline_data = (object) $arr_upline_data;

            $arr_data = [];
            $arr_data['netgrow_node_member_id'] = $arr_upline_data->id;
            $arr_data['netgrow_node_downline_member_id'] = $this->member_id;
            $arr_data['netgrow_node_downline_leg_position'] = $arr_upline_data->pos;
            $arr_data['netgrow_node_point'] = 0;
            $arr_data['netgrow_node_level'] = $arr_upline_data->level;
            $arr_data['netgrow_node_date'] = $this->date;
            $arr_data['netgrow_node_datetime'] = $this->datetime;

            if ($this->common_model->insertData('sys_netgrow_node', $arr_data) == FALSE) {
                throw new \Exception("Gagal menambah data perkembangan jaringan titik.", 1);
            }
        }
    }

    private function insert_netgrow_sponsor()
    {
        $sponsor_member_id = $this->mlm_service->get_sponsor_member_id_by_member_id($this->member_id);
        // $sponsor_bonus_value = CONFIG_BONUS_SPONSOR_VALUE;
        $sponsor_bonus_value = $this->transaction_price * CONFIG_BONUS_SPONSOR_PERCENT;

        $arr_data = [];
        $arr_data['netgrow_sponsor_member_id'] = $sponsor_member_id;
        $arr_data['netgrow_sponsor_downline_member_id'] = $this->member_id;
        $arr_data['netgrow_sponsor_downline_leg_position'] = $this->db->table("sys_network")->getWhere(["network_member_id" => $this->member_id])->getRow("network_sponsor_leg_position");
        $arr_data['netgrow_sponsor_bonus'] = $sponsor_bonus_value;
        $arr_data['netgrow_sponsor_point'] = CONFIG_BONUS_SPONSOR_POINT;
        $arr_data['netgrow_sponsor_date'] = $this->date;
        $arr_data['netgrow_sponsor_datetime'] = $this->datetime;
        if ($this->common_model->insertData('sys_netgrow_sponsor', $arr_data) == FALSE) {
            throw new \Exception("Gagal update data perkembangan sponsor.", 1);
        }
    }

    public function insert_netgrow_gen_node()
    {
        $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member_id]));
        // $arr_gen_node_bonus_value = ARR_CONFIG_BONUS_GEN_NODE_VALUE;
        $arr_gen_node_bonus_value = ARR_CONFIG_BONUS_GEN_NODE_PERCENT;

        $line_member_id = $this->member_id;
        foreach ($this->arr_network_upline as $arr_upline_data) {
            if (!array_key_exists($arr_upline_data->level, $arr_gen_node_bonus_value)) {
                continue;
            }
            $arr_upline_data = (object) $arr_upline_data;

            $arr_data = [];
            $arr_data['netgrow_gen_node_member_id'] = $arr_upline_data->id;
            $arr_data['netgrow_gen_node_line_member_id'] = $line_member_id;
            $arr_data['netgrow_gen_node_trigger_member_id'] = $this->member_id;
            $arr_data['netgrow_gen_node_level'] = $arr_upline_data->level;
            // $arr_data['netgrow_gen_node_bonus'] = $arr_gen_node_bonus_value[$arr_upline_data->level];
            $arr_data['netgrow_gen_node_bonus'] = $this->transaction_price * $arr_gen_node_bonus_value[$arr_upline_data->level];
            $arr_data['netgrow_gen_node_point'] = CONFIG_BONUS_GEN_NODE_POINT;
            $arr_data['netgrow_gen_node_date'] = $this->date;
            $arr_data['netgrow_gen_node_datetime'] = $this->datetime;

            if ($this->common_model->insertData('sys_netgrow_gen_node', $arr_data) == FALSE) {
                throw new \Exception("Gagal menambah data perkembangan jaringan generasi titik.", 1);
            }

            $line_member_id = $arr_upline_data->id;
        }
    }

    public function insert_netgrow_gen_node_cr()
    {
        $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member_id]));
        // $arr_gen_node_bonus_value = ARR_CONFIG_BONUS_GEN_NODE_VALUE;
        $arr_gen_node_bonus_value = ARR_CONFIG_BONUS_GEN_NODE_PERCENT;

        $line_member_id = $this->member_id;
        $level = 1;
        foreach ($this->arr_network_upline as $arr_upline_data) {
            // jika level > 9 maka berhenti
            // if (!array_key_exists($arr_upline_data->level, $arr_gen_node_bonus_value)) {
            //     continue;
            // }

            $arr_upline_data = (object) $arr_upline_data;
            $downlining = $this->db->query("SELECT COUNT(*) AS downlining FROM sys_netgrow_node JOIN sys_member ON member_id = netgrow_node_downline_member_id WHERE netgrow_node_member_id = 15 AND netgrow_node_level = 1 AND netgrow_node_member_id != member_parent_member_id;")->getRow("downlining") ?? 0;
            // jika belum downlining kecuali cloning >= 5 dan level > 1 maka pass up
            if ($level > 1 && $downlining >= 5) {
                continue;
            }

            $arr_data = [];
            $arr_data['netgrow_gen_node_member_id'] = $arr_upline_data->id;
            $arr_data['netgrow_gen_node_line_member_id'] = $line_member_id;
            $arr_data['netgrow_gen_node_trigger_member_id'] = $this->member_id;
            $arr_data['netgrow_gen_node_level_real'] = $arr_upline_data->level;
            $arr_data['netgrow_gen_node_level'] = $level;
            // $arr_data['netgrow_gen_node_bonus'] = $arr_gen_node_bonus_value[$arr_upline_data->level];
            $arr_data['netgrow_gen_node_bonus'] = $this->transaction_price * $arr_gen_node_bonus_value[$arr_upline_data->level];
            $arr_data['netgrow_gen_node_point'] = CONFIG_BONUS_GEN_NODE_POINT;
            $arr_data['netgrow_gen_node_date'] = $this->date;
            $arr_data['netgrow_gen_node_datetime'] = $this->datetime;

            if ($this->common_model->insertData('sys_netgrow_gen_node', $arr_data) == FALSE) {
                throw new \Exception("Gagal menambah data perkembangan jaringan generasi titik.", 1);
            }

            $line_member_id = $arr_upline_data->id;
            $level++;
        }
    }

    public function insert_netgrow_power_leg()
    {
        $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member_id]));
        // $power_leg_bonus_value = CONFIG_BONUS_POWER_LEG_VALUE;
        $power_leg_bonus_value = $this->transaction_price * CONFIG_BONUS_POWER_LEG_PERCENT;

        $line_member_id = $this->member_id;
        // $line_pos = $this->db->table("sys_network")->getWhere(["network_member_id" => $this->member_id])->getRow("network_upline_leg_position");
        foreach ($this->arr_network_upline as $arr_upline_data) {
            $netgrow_node_downline_leg_position = $this->db->table("sys_netgrow_node")->getWhere(["netgrow_node_member_id" => $arr_upline_data->id, "netgrow_node_downline_member_id" => $this->member_id])->getRow("netgrow_node_downline_leg_position");
            if (($arr_upline_data->level == 1 && $arr_upline_data->pos >= 2) || ($arr_upline_data->level >= 2 && $netgrow_node_downline_leg_position >= 2)) {
                $arr_upline_data = (object) $arr_upline_data;

                $arr_data = [];
                $arr_data['netgrow_power_leg_member_id'] = $arr_upline_data->id;
                $arr_data['netgrow_power_leg_line_member_id'] = $line_member_id;
                // $arr_data['netgrow_power_leg_line_leg_position'] = $line_pos;
                $arr_data['netgrow_power_leg_line_leg_position'] = $arr_upline_data->level == 1 ? $arr_upline_data->pos : $netgrow_node_downline_leg_position;
                $arr_data['netgrow_power_leg_trigger_member_id'] = $this->member_id;
                $arr_data['netgrow_power_leg_bonus'] = $power_leg_bonus_value;
                $arr_data['netgrow_power_leg_point'] = CONFIG_BONUS_POWER_LEG_POINT;
                $arr_data['netgrow_power_leg_date'] = $this->date;
                $arr_data['netgrow_power_leg_datetime'] = $this->datetime;

                if ($this->common_model->insertData('sys_netgrow_power_leg', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data perkembangan jaringan power leg.", 1);
                }

                $this->power_leg_member_id = $arr_upline_data->id;
                break;
            } else {
                continue;
            }

            $line_member_id = $arr_upline_data->id;
            // $line_pos = $arr_upline_data->pos;
        }
    }

    public function insert_netgrow_matching_leg()
    {
        // $matching_leg_bonus_value = CONFIG_BONUS_MATCHING_LEG_VALUE;
        $matching_leg_bonus_value = $this->transaction_price * CONFIG_BONUS_MATCHING_LEG_PERCENT;

        if ($this->power_leg_member_id) {
            $arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->power_leg_member_id]));
            usort($arr_network_upline, function ($a, $b) {
                return $a->level <=> $b->level;
            });

            foreach ($arr_network_upline as $arr_upline_data) {
                $upline = $this->db->table("sys_network")->getWhere(["network_member_id" => $arr_upline_data->id])->getRow();

                // if ($upline->network_upline_leg_position >= 2) {
                if ($arr_upline_data->pos >= 2) {
                    $arr_data = [];
                    $arr_data['netgrow_matching_leg_member_id'] = $upline->network_member_id;
                    $arr_data['netgrow_matching_leg_trigger_member_id'] = $this->power_leg_member_id;
                    $arr_data['netgrow_matching_leg_join_member_id'] = $this->member_id;
                    $arr_data['netgrow_matching_leg_bonus'] = $matching_leg_bonus_value;
                    $arr_data['netgrow_matching_leg_point'] = CONFIG_BONUS_MATCHING_LEG_POINT;
                    $arr_data['netgrow_matching_leg_date'] = $this->date;
                    $arr_data['netgrow_matching_leg_datetime'] = $this->datetime;

                    if ($this->common_model->insertData('sys_netgrow_matching_leg', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data perkembangan jaringan matching leg.", 1);
                    }

                    break;
                }
            }
        }
    }

    public function set_product_package_id($value)
    {
        $this->product_package_id = $value;
    }

    public function set_product_package_price($value)
    {
        $this->product_package_price = $value;
    }

    public function set_transaction_price($value)
    {
        $this->transaction_price = $value;
    }

    public function set_datetime($value)
    {
        $this->datetime = $value;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function set_yesterday($value)
    {
        $this->yesterday = $value;
    }
}
