<?php

namespace App\Cron;

use App\Controllers\BaseController;
use App\Models\Common_model;
use Xendit\Xendit;

class Recap extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
        $this->common_model = new Common_model();
        $this->power_leg_member_id = FALSE;
    }

    public function recap_country()
    {
        $this->db->transBegin();
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://country.io/phone.json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $return = json_decode($response);
            foreach ($return as $country_code => $country_phone_code) {
                $this->db->table("ref_country")->insert([
                    "country_iso_code" => $country_code,
                    "country_phone_code" => isset($country_phone_code[0]) && $country_phone_code[0] == "+" ? $country_phone_code : "+" . $country_phone_code,
                ]);
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://country.io/names.json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $return = json_decode($response);
            foreach ($return as $country_code => $country_name) {
                $this->db->table("ref_country")->update([
                    "country_name" => $country_name,
                ], [
                    "country_iso_code" => $country_code,
                ]);
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_flag()
    {
        $this->db->transBegin();
        try {
            $countries = $this->db->table("ref_country")->get()->getResult();
            foreach ($countries as $country) {
                $this->db->table("ref_country")->update([
                    "country_flag" => "https://flagcdn.com/h20/" . strtolower($country->country_iso_code) . ".png",
                ], [
                    "country_iso_code" => $country->country_iso_code,
                ]);
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_city()
    {
        $this->db->transBegin();
        try {
            $provinces = $this->db->table("ref_province")->get()->getResult();
            foreach ($provinces as $key => $province) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => DELIVERY_URL . "city?province=" . $province->province_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "key: " . DELIVERY_KEY
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $return = json_decode($response);
                if ($return) {
                    foreach ($return->rajaongkir->results as $key => $value) {
                        if ($value->province_id != $province->province_id) {
                            throw new \Exception("Error Processing Request", 1);
                        }
                        $this->db->table("ref_city")->insert([
                            "city_id" => $value->city_id,
                            "city_province_id" => $province->province_id,
                            "city_name" => $value->city_name,
                            "city_type" => $value->type,
                        ]);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Error Processing Request", 1);
                        }
                    }
                }
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_network_sponsor()
    {
        $this->db->transBegin();
        try {
            $this->db->query("TRUNCATE sys_network_sponsor;");
            $networks = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->get()->getResult();
            foreach ($networks as $network) {
                if (!$network->network_sponsor_member_id) {
                    $arr_data['network_sponsor_member_id'] = $network->network_member_id;
                    $arr_data['network_sponsor_arr_data'] = json_encode([]);
                    if ($this->common_model->insertData('sys_network_sponsor', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data jaringan sponsor.", 1);
                    }
                    continue;
                }

                $this->arr_network['network_sponsor_member_id'] = $network->network_sponsor_member_id;
                $this->arr_network['network_sponsor_leg_position'] = $network->network_sponsor_leg_position;
                $this->arr_network['network_member_id'] = $network->network_member_id;

                // -------------------------
                //ambil data jaringan sponsor
                $arr_network_sponsor = (array)json_decode($this->common_model->getOne('sys_network_sponsor', 'network_sponsor_arr_data', ['network_sponsor_member_id' => $this->arr_network['network_sponsor_member_id']]));

                //urutkan ulang berdasarkan level
                usort($arr_network_sponsor, function ($a, $b) {
                    return $a->level <=> $b->level;
                });

                //tambahkan masing-masing level +1 karena level 1 akan disisipkan sponsor langsung
                foreach ($arr_network_sponsor as $key => $value) {
                    $arr_network_sponsor[$key]->level = $value->level + 1;
                }

                //tambahan data sponsor langsung
                $arr_network_sponsor_add = [(object)[
                    'level' => 1,
                    'id' => (int)$this->arr_network['network_sponsor_member_id'],
                    'pos' => (int)$this->arr_network['network_sponsor_leg_position'],
                ]];

                //tambahkan data sponsor langsung ke jaringan sponsor yg sudah ada
                $arr_network_sponsor_merge = array_merge($arr_network_sponsor_add, $arr_network_sponsor);

                //urutkan ulang berdasarkan level
                usort($arr_network_sponsor_merge, function ($a, $b) {
                    return $a->level <=> $b->level;
                });

                //simpan data jaringan sponsor
                $arr_data = [];
                $arr_data['network_sponsor_member_id'] = $this->arr_network['network_member_id'];
                $arr_data['network_sponsor_arr_data'] = json_encode($arr_network_sponsor_merge);
                if ($this->common_model->insertData('sys_network_sponsor', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data jaringan sponsor.", 1);
                }
                // -------------------------
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_network_upline()
    {
        $this->db->transBegin();
        try {
            $this->db->query("TRUNCATE sys_network_upline;");
            $networks = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->get()->getResult();
            foreach ($networks as $network) {
                if (!$network->network_upline_member_id) {
                    $arr_data['network_upline_member_id'] = $network->network_member_id;
                    $arr_data['network_upline_arr_data'] = json_encode([]);
                    if ($this->common_model->insertData('sys_network_upline', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data jaringan upline.", 1);
                    }
                    continue;
                }

                $this->arr_network['network_upline_member_id'] = $network->network_upline_member_id;
                $this->arr_network['network_upline_leg_position'] = $network->network_upline_leg_position;
                $this->arr_network['network_member_id'] = $network->network_member_id;

                // -------------------------
                //ambil data jaringan upline
                $arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->arr_network['network_upline_member_id']]));

                //urutkan ulang berdasarkan level
                usort($arr_network_upline, function ($a, $b) {
                    return $a->level <=> $b->level;
                });

                //tambahkan masing-masing level +1 karena level 1 akan disisipkan upline langsung
                foreach ($arr_network_upline as $key => $value) {
                    $arr_network_upline[$key]->level = $value->level + 1;
                }

                //tambahan data upline langsung
                $arr_network_upline_add = [(object)[
                    'level' => 1,
                    'id' => (int)$this->arr_network['network_upline_member_id'],
                    'pos' => (int)$this->arr_network['network_upline_leg_position'],
                ]];

                //tambahkan data upline langsung ke jaringan upline yg sudah ada
                $arr_network_upline_merge = array_merge($arr_network_upline_add, $arr_network_upline);

                //urutkan ulang berdasarkan level
                usort($arr_network_upline_merge, function ($a, $b) {
                    return $a->level <=> $b->level;
                });

                //simpan data jaringan upline
                $arr_data = [];
                $arr_data['network_upline_member_id'] = $this->arr_network['network_member_id'];
                $arr_data['network_upline_arr_data'] = json_encode($arr_network_upline_merge);
                if ($this->common_model->insertData('sys_network_upline', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data jaringan upline.", 1);
                }
                // -------------------------
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_gen_node()
    {
        $this->db->transBegin();
        try {
            $this->db->query("TRUNCATE sys_netgrow_gen_node;");
            $networks = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->get()->getResult();
            foreach ($networks as $network) {
                $this->member_id = $network->member_id;
                $this->date = date("Y-m-d", strtotime($network->network_activation_datetime));
                $this->datetime = $network->network_activation_datetime;

                // -------------------------
                $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member_id]));
                $arr_gen_node_bonus_value = ARR_CONFIG_BONUS_GEN_NODE_VALUE;

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
                    $arr_data['netgrow_gen_node_bonus'] = $arr_gen_node_bonus_value[$arr_upline_data->level];
                    $arr_data['netgrow_gen_node_point'] = CONFIG_BONUS_GEN_NODE_POINT;
                    $arr_data['netgrow_gen_node_date'] = $this->date;
                    $arr_data['netgrow_gen_node_datetime'] = $this->datetime;

                    if ($this->common_model->insertData('sys_netgrow_gen_node', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data perkembangan jaringan generasi titik.", 1);
                    }

                    $line_member_id = $arr_upline_data->id;
                }
                // -------------------------
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_power_leg()
    {
        $this->db->transBegin();
        try {
            $this->db->query("TRUNCATE sys_netgrow_power_leg;");
            $networks = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->get()->getResult();
            foreach ($networks as $network) {
                $this->member_id = $network->member_id;
                $this->date = date("Y-m-d", strtotime($network->network_activation_datetime));
                $this->datetime = $network->network_activation_datetime;

                // -------------------------
                $this->arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->member_id]));
                $power_leg_bonus_value = CONFIG_BONUS_POWER_LEG_VALUE;

                $line_member_id = $this->member_id;
                $line_pos = $this->db->table("sys_network")->getWhere(["network_member_id" => $this->member_id])->getRow("network_upline_leg_position");
                foreach ($this->arr_network_upline as $arr_upline_data) {
                    $netgrow_node_downline_leg_position = $this->db->table("sys_netgrow_node")->getWhere(["netgrow_node_member_id" => $arr_upline_data->id, "netgrow_node_downline_member_id" => $this->member_id])->getRow("netgrow_node_downline_leg_position");
                    if (($arr_upline_data->level == 1 && $arr_upline_data->pos >= 2) || ($arr_upline_data->level >= 2 && $netgrow_node_downline_leg_position >= 2)) {
                        $arr_upline_data = (object) $arr_upline_data;

                        $arr_data = [];
                        $arr_data['netgrow_power_leg_member_id'] = $arr_upline_data->id;
                        $arr_data['netgrow_power_leg_line_member_id'] = $line_member_id;
                        $arr_data['netgrow_power_leg_line_leg_position'] = $line_pos;
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
                    $line_pos = $arr_upline_data->pos;
                }
                // -------------------------
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_matching_leg()
    {
        $this->db->transBegin();
        try {
            $this->db->query("TRUNCATE sys_netgrow_matching_leg;");
            $power_legs = $this->db->table("sys_netgrow_power_leg")->join("sys_network", "network_member_id = netgrow_power_leg_member_id")->get()->getResult();
            foreach ($power_legs as $power_leg) {
                $this->date = date("Y-m-d", strtotime($power_leg->netgrow_power_leg_date));
                $this->datetime = $power_leg->netgrow_power_leg_date;
                $this->power_leg_member_id = $power_leg->netgrow_power_leg_member_id;
                $this->member_id = $power_leg->netgrow_power_leg_trigger_member_id;

                // -------------------------
                $matching_leg_bonus_value = CONFIG_BONUS_MATCHING_LEG_VALUE;

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
                // -------------------------
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        echo $result;
    }

    public function recap_bank()
    {
        $this->db->transBegin();
        try {
            $this->db->query("TRUNCATE ref_bank;");

            Xendit::setApiKey(PAYMENT_SECRET_KEY);

            $response = \Xendit\Disbursements::getAvailableBanks();
            foreach ($response as $key => $value) {
                $this->db->table("ref_bank")->insert([
                    "bank_name" => $value["name"],
                    "bank_code" => $value["code"],
                    "bank_can_disburse" => $value["can_disburse"],
                    "bank_can_name_validate" => $value["can_name_validate"],
                    "bank_is_active" => 1,
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Error Processing Request", 1);
                }
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }
    }

    public function recap_bonus()
    {
        $date = date("Y-m-d");
        $this->db->transBegin();
        try {
            $this->db->query("UPDATE sys_bonus SET bonus_matching_leg_acc = 0;");
            $this->db->query("UPDATE sys_bonus_log SET bonus_log_matching_leg = 0;");

            $netgrow_matching_leg = $this->db->table("sys_netgrow_matching_leg")->getWhere(["netgrow_matching_leg_date <" => $date])->getResult();
            foreach ($netgrow_matching_leg as $key => $netgrow) {
                $this->db->table("sys_bonus")->set("bonus_matching_leg_acc", "bonus_matching_leg_acc+" . $netgrow->netgrow_matching_leg_bonus, FALSE)->where(["bonus_member_id" => $netgrow->netgrow_matching_leg_member_id])->update();
                $log = $this->db->table("sys_bonus_log")->getWhere(["bonus_log_member_id" => $netgrow->netgrow_matching_leg_member_id, "bonus_log_date" => $netgrow->netgrow_matching_leg_date])->getRow();
                if ($log) {
                    $this->db->table("sys_bonus_log")->set("bonus_log_matching_leg", "bonus_log_matching_leg+" . $netgrow->netgrow_matching_leg_bonus, FALSE)
                        ->where(["bonus_log_member_id" => $netgrow->netgrow_matching_leg_member_id, "bonus_log_date" => $netgrow->netgrow_matching_leg_date])->update();
                    // if ($this->db->affectedRows() <= 0) {
                    //     throw new \Exception("Error update {$netgrow->netgrow_matching_leg_bonus} id {$netgrow->netgrow_matching_leg_member_id} tanggal {$netgrow->netgrow_matching_leg_date}", 1);
                    // }
                } else {
                    $this->db->table("sys_bonus_log")->insert([
                        "bonus_log_member_id" => $netgrow->netgrow_matching_leg_member_id,
                        "bonus_log_sponsor" => 0,
                        "bonus_log_gen_node" => 0,
                        "bonus_log_power_leg" => 0,
                        "bonus_log_matching_leg" => $netgrow->netgrow_matching_leg_bonus,
                        "bonus_log_cash_reward" => 0,
                        "bonus_log_date" => $netgrow->netgrow_matching_leg_date,
                        "bonus_log_datetime" => $netgrow->netgrow_matching_leg_date . " 00:00:00",
                    ]);
                    // if ($this->db->affectedRows() <= 0) {
                    //     throw new \Exception("Error insert {$netgrow->netgrow_matching_leg_bonus} id {$netgrow->netgrow_matching_leg_member_id} tanggal {$netgrow->netgrow_matching_leg_date}", 1);
                    // }
                }
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        echo $result;
    }

    public function recap_point()
    {
        $date = date("Y-m-d");
        $this->db->transBegin();
        try {
            $this->db->query("UPDATE sys_reward_point SET reward_point_acc = 0;");
            $this->db->query("TRUNCATE sys_reward_point_log;");

            $netgrow_gen_node = $this->db->query(
                "SELECT
                netgrow_gen_node_member_id AS member_id,
                SUM(netgrow_gen_node_point) AS netgrow_point,
                netgrow_gen_node_date AS netgrow_date
                FROM sys_netgrow_gen_node
                WHERE netgrow_gen_node_date < '{$date}'
                GROUP BY netgrow_gen_node_date, netgrow_gen_node_member_id
                ORDER BY netgrow_gen_node_date, netgrow_gen_node_member_id;"
            )->getResult();
            foreach ($netgrow_gen_node as $key => $netgrow) {
                $this->db->table("sys_reward_point")->set("reward_point_acc", "reward_point_acc+" . $netgrow->netgrow_point, FALSE)->where(["reward_point_member_id" => $netgrow->member_id])->update();
                $this->db->table("sys_reward_point_log")->insert([
                    "reward_point_log_member_id" => $netgrow->member_id,
                    "reward_point_log_type" => "in",
                    "reward_point_log_value" => $netgrow->netgrow_point,
                    "reward_point_log_note" => "Poin Reward dari Komisi Generasi ({$netgrow->netgrow_point})",
                    "reward_point_log_datetime" => $netgrow->netgrow_date . " 00:00:00",
                ]);
            }

            $netgrow_power_leg = $this->db->query(
                "SELECT
                netgrow_power_leg_member_id AS member_id,
                SUM(netgrow_power_leg_point) AS netgrow_point,
                netgrow_power_leg_date AS netgrow_date
                FROM sys_netgrow_power_leg
                WHERE netgrow_power_leg_date < '{$date}'
                GROUP BY netgrow_power_leg_date, netgrow_power_leg_member_id
                ORDER BY netgrow_power_leg_date, netgrow_power_leg_member_id;"
            )->getResult();
            foreach ($netgrow_power_leg as $key => $netgrow) {
                $this->db->table("sys_reward_point")->set("reward_point_acc", "reward_point_acc+" . $netgrow->netgrow_point, FALSE)->where(["reward_point_member_id" => $netgrow->member_id])->update();
                $this->db->table("sys_reward_point_log")->insert([
                    "reward_point_log_member_id" => $netgrow->member_id,
                    "reward_point_log_type" => "in",
                    "reward_point_log_value" => $netgrow->netgrow_point,
                    "reward_point_log_note" => "Poin Reward dari Komisi Power Leg ({$netgrow->netgrow_point})",
                    "reward_point_log_datetime" => $netgrow->netgrow_date . " 00:00:00",
                ]);
            }

            $netgrow_matching_leg = $this->db->query(
                "SELECT
                netgrow_matching_leg_member_id AS member_id,
                SUM(netgrow_matching_leg_point) AS netgrow_point,
                netgrow_matching_leg_date AS netgrow_date
                FROM sys_netgrow_matching_leg
                WHERE netgrow_matching_leg_date < '{$date}'
                GROUP BY netgrow_matching_leg_date, netgrow_matching_leg_member_id
                ORDER BY netgrow_matching_leg_date, netgrow_matching_leg_member_id;"
            )->getResult();
            foreach ($netgrow_matching_leg as $key => $netgrow) {
                $this->db->table("sys_reward_point")->set("reward_point_acc", "reward_point_acc+" . $netgrow->netgrow_point, FALSE)->where(["reward_point_member_id" => $netgrow->member_id])->update();
                $this->db->table("sys_reward_point_log")->insert([
                    "reward_point_log_member_id" => $netgrow->member_id,
                    "reward_point_log_type" => "in",
                    "reward_point_log_value" => $netgrow->netgrow_point,
                    "reward_point_log_note" => "Poin Reward dari Komisi Matching Leg ({$netgrow->netgrow_point})",
                    "reward_point_log_datetime" => $netgrow->netgrow_date . " 00:00:00",
                ]);
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        echo $result;
    }

    public function recap_limit()
    {
        $this->db->transBegin();
        try {
            $parents = $this->db->query("SELECT * FROM sys_member WHERE member_id = member_parent_member_id")->getResult();

            foreach ($parents as $key => $parent) {
                $sql = "SELECT
                netgrow_sponsor_member_id
                FROM sys_netgrow_sponsor
                WHERE netgrow_sponsor_member_id = {$parent->member_id}";
                // cek frontline 5
                $sponsoring = $this->db->query($sql)->getResult();

                $group = $this->db->query("SELECT * FROM sys_member WHERE member_parent_member_id = {$parent->member_id}")->getResult();
                foreach ($group as $key => $member) {
                    $limit = CONFIG_BONUS_LIMIT + CONFIG_BONUS_LIMIT * count($sponsoring);
                    if (count($sponsoring) > 5) {
                        $limit = CONFIG_BONUS_LIMIT_2 + CONFIG_BONUS_LIMIT_2 * count($sponsoring);
                    }
                    if ($this->db->table("sys_bonus")->getWhere(["bonus_member_id" => $member->member_id])->getRow("bonus_limit") != $limit) {
                        if ($this->common_model->updateData('sys_bonus', 'bonus_member_id', $member->member_id, ['bonus_limit' => $limit]) == FALSE) {
                            throw new \Exception("Gagal mengubah data bonus limit.", 1);
                        }
                    }
                }
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        echo $result;
    }

    public function recap_transaction_code()
    {
        $this->transaction_service = service('Transaction');
        $this->db->transBegin();
        try {
            $transaction_stockist = $this->db->query("SELECT * FROM inv_stockist_transaction ORDER BY stockist_transaction_datetime ASC")->getResult();

            $i = 1;
            $current_date = "";
            foreach ($transaction_stockist as $key => $transaction) {
                $date = date("Y-m-d", strtotime($transaction->stockist_transaction_datetime));
                $prefix = "TRX/AC";
                if ($date != $current_date) {
                    $i = 1;
                }
                $new_sort = str_pad($i, 4, "0", STR_PAD_LEFT);
                $transaction_code = $prefix . '/' . str_replace('-', '', $date) . '/' . $new_sort;

                $this->db->query("UPDATE inv_stockist_transaction SET stockist_transaction_code = '{$transaction_code}' WHERE stockist_transaction_id = {$transaction->stockist_transaction_id}");
                $this->db->query("UPDATE sys_ewallet_log SET ewallet_log_note = 'Penerimaan transaksi kode : {$transaction_code}' WHERE ewallet_log_datetime = '{$transaction->stockist_transaction_datetime}'");

                $current_date = $date;
                $i++;
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        echo $result;
    }

    public function recap_trx()
    {
        $this->db->transBegin();
        try {
            $member_registration = $this->db->query("SELECT * FROM sys_member_registration")->getResult();

            foreach ($member_registration as $key => $registration) {
                if ($registration->member_id) {
                    if ($registration->member_registration_transaction_type == "warehouse") {
                        $this->db->query("UPDATE inv_warehouse_transaction SET warehouse_transaction_buyer_member_id = {$registration->member_id} WHERE warehouse_transaction_id = {$registration->member_registration_transaction_id}");
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Error Processing Request WH " . $registration->member_id . " " . $registration->member_registration_transaction_id, 1);
                        }
                    }
                    if ($registration->member_registration_transaction_type == "stockist") {
                        $this->db->query("UPDATE inv_stockist_transaction SET stockist_transaction_buyer_member_id = {$registration->member_id} WHERE stockist_transaction_id = {$registration->member_registration_transaction_id}");
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Error Processing Request ST " . $registration->member_id . " " . $registration->member_registration_transaction_id, 1);
                        }
                    }
                }
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        echo $result;
    }
}
