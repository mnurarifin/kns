<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;

class Bonus_services extends BaseController
{
    public $is_potency = FALSE;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->arr_bonus = [];
        $this->arr_point = [];
        $this->notification_lib = new Notification();
    }

    public function init_bonus($member_id, $sponsor_member_id)
    {
        if ($this->common_model->insertData('sys_bonus', ['bonus_member_id' => $member_id, 'bonus_limit' => CONFIG_BONUS_LIMIT]) == FALSE) {
            throw new \Exception("Gagal menambah data bonus.", 1);
        }

        $this->update_bonus_sponsor($sponsor_member_id);
    }

    public function update_bonus_sponsor($sponsor_member_id)
    {
        $member_parent_member_id = $this->db->table("sys_member")->getWhere(["member_id" => $sponsor_member_id])->getRow("member_parent_member_id");
        $groups = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $member_parent_member_id])->getResult();

        $sql = "SELECT
        netgrow_sponsor_member_id
        FROM sys_netgrow_sponsor
        WHERE netgrow_sponsor_member_id = {$member_parent_member_id}";
        $sponsoring = $this->db->query($sql)->getResult();

        $limit = CONFIG_BONUS_LIMIT + CONFIG_BONUS_LIMIT * count($sponsoring);
        // setelah 5x sponsoring (clone dan regist)
        if (count($sponsoring) > 5) {
            $limit = CONFIG_BONUS_LIMIT_2 + CONFIG_BONUS_LIMIT_2 * count($sponsoring);
        }

        foreach ($groups as $key => $value) {
            if ($this->common_model->updateData('sys_bonus', 'bonus_member_id', $value->member_id, ['bonus_limit' => $limit]) == FALSE) {
                throw new \Exception("Gagal mengubah data bonus limit.", 1);
            }
        }
    }

    public function init_reward($member_id)
    {
        if ($this->common_model->insertData('sys_reward_point', ['reward_point_member_id' => $member_id]) == FALSE) {
            throw new \Exception("Gagal menambah data cash reward.", 1);
        }

        if ($this->common_model->insertData('sys_reward_trip_point', ['reward_point_member_id' => $member_id]) == FALSE) {
            throw new \Exception("Gagal menambah data trip reward.", 1);
        }
    }

    public function set_potency($potency)
    {
        $this->is_potency = $potency;
    }

    public function calculateDaily($bonus_date, $run_datetime)
    {
        $this->calculate_sponsor($bonus_date);
        $this->calculate_gen_node($bonus_date);
        $this->calculate_power_leg($bonus_date);
        $this->calculate_matching_leg($bonus_date);

        foreach ($this->arr_point as $member_id => $arr_point) {
            $point_gen_node = isset($arr_point['gen_node']) ? $arr_point['gen_node'] : 0;
            $point_power_leg = isset($arr_point['power_leg']) ? $arr_point['power_leg'] : 0;
            $point_matching_leg = isset($arr_point['matching_leg']) ? $arr_point['matching_leg'] : 0;
            $point_total = $point_gen_node + $point_power_leg + $point_matching_leg;

            if ($point_total == 0) {
                continue;
            }

            $arr_data = [];
            $note = "Poin Reward dari ";
            if ($point_gen_node > 0) {
                $arr_data = [
                    'reward_point_log_member_id' => $member_id,
                    'reward_point_log_type' => 'in',
                    'reward_point_log_value' => $point_gen_node,
                    'reward_point_log_note' => $note . "Komisi Generasi ({$point_gen_node})",
                    'reward_point_log_datetime' => $bonus_date . " 00:00:00"
                ];

                if ($this->common_model->insertData('sys_reward_point_log', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambahkan data log poin reward.", 1);
                }
            }
            if ($point_power_leg > 0) {
                $arr_data = [
                    'reward_point_log_member_id' => $member_id,
                    'reward_point_log_type' => 'in',
                    'reward_point_log_value' => $point_power_leg,
                    'reward_point_log_note' => $note . "Komisi Power Leg ({$point_power_leg})",
                    'reward_point_log_datetime' => $bonus_date . " 00:00:00"
                ];

                if ($this->common_model->insertData('sys_reward_point_log', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambahkan data log poin reward.", 1);
                }
            }
            if ($point_matching_leg > 0) {
                $arr_data = [
                    'reward_point_log_member_id' => $member_id,
                    'reward_point_log_type' => 'in',
                    'reward_point_log_value' => $point_matching_leg,
                    'reward_point_log_note' => $note . "Komisi Matching Leg ({$point_matching_leg})",
                    'reward_point_log_datetime' => $bonus_date . " 00:00:00"
                ];

                if ($this->common_model->insertData('sys_reward_point_log', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambahkan data log poin reward.", 1);
                }
            }

            //update ke tabel point
            $builder = $this->db->table('sys_reward_point');
            $builder->set("reward_point_acc", "reward_point_acc + {$point_total}", FALSE);
            $builder->where('reward_point_member_id', $member_id);
            $builder->update();
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal update data poin reward.", 1);
            }
        }

        $this->calculate_cash_reward($run_datetime);

        foreach ($this->arr_bonus as $member_id => $arr_bonus) {
            $bonus_sponsor = isset($arr_bonus['sponsor']) ? $arr_bonus['sponsor'] : 0;
            $bonus_gen_node = isset($arr_bonus['gen_node']) ? $arr_bonus['gen_node'] : 0;
            $bonus_power_leg = isset($arr_bonus['power_leg']) ? $arr_bonus['power_leg'] : 0;
            $bonus_matching_leg = isset($arr_bonus['matching_leg']) ? $arr_bonus['matching_leg'] : 0;
            $bonus_cash_reward = isset($arr_bonus['cash_reward']) ? $arr_bonus['cash_reward'] : 0;
            $bonus_total = $bonus_sponsor + $bonus_gen_node + $bonus_power_leg + $bonus_matching_leg + $bonus_cash_reward;

            if ($bonus_total == 0) {
                continue;
            }

            //insert ke tabel bonus log
            $arr_data = [];
            $arr_data['bonus_log_member_id'] = $member_id;
            $arr_data['bonus_log_sponsor'] = $bonus_sponsor;
            $arr_data['bonus_log_gen_node'] = $bonus_gen_node;
            $arr_data['bonus_log_power_leg'] = $bonus_power_leg;
            $arr_data['bonus_log_matching_leg'] = $bonus_matching_leg;
            $arr_data['bonus_log_cash_reward'] = $bonus_cash_reward;
            $arr_data['bonus_log_date'] = $bonus_date;
            $arr_data['bonus_log_datetime'] = $bonus_date . " 00:00:00";
            //cek data bonus log, jika ada maka update, jika tidak maka insert
            $exist = $this->db->table("sys_bonus_log")->getWhere(["bonus_log_member_id" => $member_id, "bonus_log_date" => $bonus_date])->getRow();
            if ($exist) {
                $this->db->table("sys_bonus_log")->update([
                    "bonus_log_sponsor" => $bonus_sponsor,
                    "bonus_log_gen_node" => $bonus_gen_node,
                    "bonus_log_power_leg" => $bonus_power_leg,
                    "bonus_log_matching_leg" => $bonus_matching_leg,
                    "bonus_log_cash_reward" => $bonus_cash_reward,
                ], ["bonus_log_member_id" => $member_id, "bonus_log_date" => $bonus_date]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambahkan data log bonus.", 1);
                }
            } else {
                if ($this->common_model->insertData('sys_bonus_log', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambahkan data log bonus.", 1);
                }
            }

            //update ke tabel bonus
            $builder = $this->db->table('sys_bonus');
            $builder->set("bonus_sponsor_acc", "bonus_sponsor_acc + {$bonus_sponsor}", FALSE);
            $builder->set("bonus_gen_node_acc", "bonus_gen_node_acc + {$bonus_gen_node}", FALSE);
            $builder->set("bonus_power_leg_acc", "bonus_power_leg_acc + {$bonus_power_leg}", FALSE);
            $builder->set("bonus_matching_leg_acc", "bonus_matching_leg_acc + {$bonus_matching_leg}", FALSE);
            $builder->set("bonus_cash_reward_acc", "bonus_cash_reward_acc + {$bonus_cash_reward}", FALSE);
            $builder->where('bonus_member_id', $member_id);
            $builder->update();
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal update data bonus.", 1);
            }

            $client_url = URL_PRODUCTION;
            $client_name = COMPANY_NAME;
            $comission = setNumberFormat($bonus_total);
            $member = $this->db->table("sys_member")
                ->select("member_mobilephone, network_code")
                ->join('sys_network', 'network_member_id = member_id')
                ->getWhere(["member_id" => $member_id])
                ->getRow();
            if (WA_NOTIFICATION_IS_ACTIVE) {
                $content = "Update Komisi!

Selamat! Komisi Terbaru Anda *{$member->network_code}* setelah tutup buku hari ini dari {$client_name} sebesar *Rp {$comission}*

Terima kasih atas kepercayaan anda bersama kami.

Tingkatkan komisi Anda dengan sering bercerita ke banyak orang tentang manfaat produk dari {$client_name}.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";

                $this->notification_lib->send_waone($content, phoneNumberFilter($member->member_mobilephone));
            }
        }
    }

    public function calculateMonthly($bonus_date, $run_datetime)
    {
        // $this->calculate_welcome($bonus_date);
        // $this->calculate_unilevel($bonus_date);

        foreach ($this->arr_bonus as $member_id => $arr_bonus) {
            $bonus_welcome = isset($arr_bonus['welcome']) ? $arr_bonus['welcome'] : 0;
            $bonus_unilevel = isset($arr_bonus['unilevel']) ? $arr_bonus['unilevel'] : 0;
            $bonus_total = $bonus_welcome + $bonus_unilevel;

            if ($bonus_total == 0) {
                continue;
            }

            //insert ke tabel bonus log
            $arr_data = [];
            $arr_data['bonus_log_member_id'] = $member_id;
            $arr_data['bonus_log_welcome'] = $bonus_welcome;
            $arr_data['bonus_log_unilevel'] = $bonus_unilevel;
            $arr_data['bonus_log_date'] = $bonus_date;
            //cek data bonus log, jika ada maka update, jika tidak maka insert
            $exist = $this->db->table("sys_bonus_log")->getWhere(["bonus_log_member_id" => $member_id, "bonus_log_date" => $bonus_date])->getRow();
            if ($exist) {
                $this->db->table("sys_bonus_log")->update([
                    "bonus_log_welcome" => $bonus_welcome,
                    "bonus_log_unilevel" => $bonus_unilevel,
                ], ["bonus_log_member_id" => $member_id, "bonus_log_date" => $bonus_date]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambahkan data log bonus.", 1);
                }
            } else {
                if ($this->common_model->insertData('sys_bonus_log', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambahkan data log bonus.", 1);
                }
            }

            //update ke tabel bonus
            $builder = $this->db->table('sys_bonus');
            $builder->set("bonus_welcome_acc", "bonus_welcome_acc + {$bonus_welcome}", FALSE);
            $builder->set("bonus_unilevel_acc", "bonus_unilevel_acc + {$bonus_unilevel}", FALSE);
            $builder->where('bonus_member_id', $member_id);
            $builder->update();
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal update data bonus.", 1);
            }
        }
    }

    public function calculateAnnually($bonus_date, $run_datetime)
    {
        // $this->calculate_annually($bonus_date);
        // $this->calculate_reward($bonus_date);

        foreach ($this->arr_bonus as $member_id => $arr_bonus) {
            $bonus_annually = isset($arr_bonus['annually']) ? $arr_bonus['annually'] : 0;
            $bonus_reward = isset($arr_bonus['reward']) ? $arr_bonus['reward'] : 0;
            $bonus_total = $bonus_annually + $bonus_reward;

            if ($bonus_total == 0) {
                continue;
            }

            //insert ke tabel bonus log
            $arr_data = [];
            $arr_data['bonus_log_member_id'] = $member_id;
            $arr_data['bonus_log_annually'] = $bonus_annually;
            $arr_data['bonus_log_reward'] = $bonus_reward;
            $arr_data['bonus_log_date'] = $bonus_date;
            //cek data bonus log, jika ada maka update, jika tidak maka insert
            $exist = $this->db->table("sys_bonus_log")->getWhere(["bonus_log_member_id" => $member_id, "bonus_log_date" => $bonus_date])->getRow();
            if ($exist) {
                $this->db->table("sys_bonus_log")->update([
                    "bonus_log_annually" => $bonus_annually,
                    "bonus_log_reward" => $bonus_reward,
                ], ["bonus_log_member_id" => $member_id, "bonus_log_date" => $bonus_date]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambahkan data log bonus.", 1);
                }
            } else {
                if ($this->common_model->insertData('sys_bonus_log', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambahkan data log bonus.", 1);
                }
            }

            //update ke tabel bonus
            $builder = $this->db->table('sys_bonus');
            $builder->set("bonus_annually_acc", "bonus_annually_acc + {$bonus_annually}", FALSE);
            $builder->set("bonus_reward_acc", "bonus_reward_acc + {$bonus_reward}", FALSE);
            $builder->where('bonus_member_id', $member_id);
            $builder->update();
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal update data bonus.", 1);
            }
        }
    }

    public function calculate_sponsor($bonus_date, $member_id = 1)
    {
        $sql = "SELECT
            netgrow_sponsor_member_id AS member_id,
            SUM(netgrow_sponsor_bonus) AS bonus_value
            FROM sys_netgrow_sponsor
            WHERE netgrow_sponsor_date = '{$bonus_date}'
            GROUP BY netgrow_sponsor_member_id
            ORDER BY netgrow_sponsor_member_id ASC
        ";
        $query = $this->db->query($sql)->getResult();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $this->arr_bonus[$row->member_id]['sponsor'] = $row->bonus_value;
            }
            return $this->arr_bonus[$member_id]["sponsor"] ?? 0;
        }
    }

    public function calculate_gen_node($bonus_date, $member_id = 1)
    {
        $sql = "SELECT
            netgrow_gen_node_member_id AS member_id,
            SUM(netgrow_gen_node_bonus) AS bonus_value,
            COUNT(netgrow_gen_node_member_id) AS total
            FROM sys_netgrow_gen_node
            WHERE netgrow_gen_node_date = '{$bonus_date}'
            GROUP BY netgrow_gen_node_member_id
            ORDER BY netgrow_gen_node_member_id ASC
        ";
        $query = $this->db->query($sql)->getResult();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $this->arr_bonus[$row->member_id]['gen_node'] = $row->bonus_value;
                $this->arr_point[$row->member_id]['gen_node'] = CONFIG_BONUS_GEN_NODE_POINT * $row->total;
            }
            return $this->arr_bonus[$member_id]["gen_node"] ?? 0;
        }
    }

    public function calculate_power_leg($bonus_date, $member_id = 1)
    {
        $sql = "SELECT
            netgrow_power_leg_member_id AS member_id,
            SUM(netgrow_power_leg_bonus) AS bonus_value,
            COUNT(netgrow_power_leg_member_id) AS total
            FROM sys_netgrow_power_leg
            WHERE netgrow_power_leg_date = '{$bonus_date}'
            GROUP BY netgrow_power_leg_member_id
            ORDER BY netgrow_power_leg_member_id ASC
        ";
        $query = $this->db->query($sql)->getResult();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $this->arr_bonus[$row->member_id]['power_leg'] = $row->bonus_value;
                $this->arr_point[$row->member_id]['power_leg'] = CONFIG_BONUS_POWER_LEG_POINT * $row->total;
            }
            return $this->arr_bonus[$member_id]["power_leg"] ?? 0;
        }
    }

    public function calculate_matching_leg($bonus_date, $member_id = 1)
    {
        $sql = "SELECT
            netgrow_matching_leg_member_id AS member_id,
            SUM(netgrow_matching_leg_bonus) AS bonus_value,
            COUNT(netgrow_matching_leg_member_id) AS total
            FROM sys_netgrow_matching_leg
            WHERE netgrow_matching_leg_date = '{$bonus_date}'
            GROUP BY netgrow_matching_leg_member_id
            ORDER BY netgrow_matching_leg_member_id ASC
        ";
        $query = $this->db->query($sql)->getResult();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $this->arr_bonus[$row->member_id]['matching_leg'] = $row->bonus_value;
                $this->arr_point[$row->member_id]['matching_leg'] = CONFIG_BONUS_MATCHING_LEG_POINT * $row->total;
            }
            return $this->arr_bonus[$member_id]["matching_leg"] ?? 0;
        }
    }

    public function calculate_cash_reward($bonus_date, $member_id = 1)
    {
        $datetime = date("Y-m-d H:i:s", strtotime($bonus_date . '-1 day'));
        $sql = "SELECT * FROM sys_reward";
        $query = $this->db->query($sql);
        foreach ($query->getResult() as $row) {
            $sql_achievement = "
                SELECT *
                FROM sys_reward_point
                JOIN sys_network ON network_member_id = reward_point_member_id
                WHERE reward_point_acc%1000000 >= {$row->reward_condition_point}
            ";
            $query_achievement = $this->db->query($sql_achievement);
            foreach ($query_achievement->getResult() as $row_achievement) {
                $arr_data = [];
                if ($row->reward_value <= 100000000 && $row_achievement->network_rank_id <= 6) {
                    if ($row_achievement->network_rank_id < $row->reward_id) {
                        $arr_data['reward_qualified_member_id'] = $row_achievement->network_member_id;
                        $arr_data['reward_qualified_reward_id'] = $row->reward_id;
                        $arr_data['reward_qualified_reward_title'] = $row->reward_title;
                        $arr_data['reward_qualified_reward_value'] = $row->reward_value;
                        $arr_data['reward_qualified_condition_sponsor'] = $row->reward_condition_sponsor;
                        $arr_data['reward_qualified_condition_point_left'] = $row->reward_condition_point_left;
                        $arr_data['reward_qualified_condition_point_right'] = $row->reward_condition_point_right;
                        $arr_data['reward_qualified_condition_point'] = $row->reward_condition_point;
                        $arr_data['reward_qualified_condition_rank_id'] = $row->reward_id;
                        $arr_data['reward_qualified_datetime'] = $datetime;
                        $arr_data['reward_qualified_status'] = 'approved';
                        $arr_data['reward_qualified_status_datetime'] = $datetime;
                        $arr_data['reward_qualified_claim'] = 'claimed';
                        $arr_data['reward_qualified_claim_datetime'] = $datetime;

                        if ($this->common_model->insertData('sys_reward_qualified', $arr_data) == FALSE) {
                            throw new \Exception("Gagal menambah data kualifikasi reward.", 1);
                        }

                        $this->db->table('sys_network')->where('network_member_id', $row_achievement->network_member_id)->update(['network_rank_id' => $row->reward_id]);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update peringkat reward", 1);
                        }

                        // if (array_key_exists($row_achievement->network_member_id, $this->arr_bonus)) {
                        if (isset($this->arr_bonus[$row_achievement->network_member_id]['cash_reward'])) {
                            $this->arr_bonus[$row_achievement->network_member_id]['cash_reward'] += $row->reward_value;
                        } else {
                            $this->arr_bonus[$row_achievement->network_member_id]['cash_reward'] = $row->reward_value;
                        }

                        $arr_log_reward_point = [
                            'reward_point_log_member_id' => $row_achievement->network_member_id,
                            'reward_point_log_type' => 'out',
                            'reward_point_log_value' => $row->reward_condition_point - $row_achievement->reward_point_paid,
                            'reward_point_log_note' => 'Qualified reward cash ' . $row->reward_title . " (" . (int)$row->reward_condition_point . ")",
                            'reward_point_log_datetime' => $datetime
                        ];

                        $this->db->table("sys_reward_point_log")->insert($arr_log_reward_point);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal menambahkan log reward poin", 1);
                        }

                        $this->db->table("sys_reward_point")->where("reward_point_member_id", $row_achievement->network_member_id)->update(["reward_point_paid" => $row->reward_condition_point]);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update reward point", 1);
                        }

                        $last_point_trip_acc = $this->db->table("sys_reward_trip_point")->select("reward_point_acc")->getWhere(["reward_point_member_id" => $row_achievement->network_member_id])->getRow('reward_point_acc');

                        $this->db->table("sys_reward_trip_point")->where("reward_point_member_id", $row_achievement->network_member_id)->update(["reward_point_acc" => $last_point_trip_acc + $row->reward_trip_point]);
                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Gagal update reward poin trip", 1);
                        }

                        $arr_log_reward_point_trip = [
                            'reward_point_log_member_id' => $row_achievement->network_member_id,
                            'reward_point_log_type' => 'in',
                            'reward_point_log_value' => $row->reward_trip_point,
                            'reward_point_log_note' => 'Reward Trip dari ' . $row->reward_title,
                            'reward_point_log_datetime' => $datetime
                        ];

                        $this->db->table("sys_reward_trip_point_log")->insert($arr_log_reward_point_trip);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal menambahkan reward poin trip", 1);
                        }
                    }
                } else {
                    $check_qualified_pending_is_exist = $this->db->table("sys_reward_qualified")
                        ->where("reward_qualified_member_id", $row_achievement->network_member_id)
                        ->where("reward_qualified_status", "pending")
                        ->where("reward_qualified_claim = 'unclaimed' OR reward_qualified_claim = 'claimed'")
                        ->where("reward_qualified_reward_id", $row->reward_id)
                        ->get()
                        ->getRow();

                    if ($row_achievement->network_rank_id < $row->reward_id && !$check_qualified_pending_is_exist) {
                        $arr_data['reward_qualified_member_id'] = $row_achievement->network_member_id;
                        $arr_data['reward_qualified_reward_id'] = $row->reward_id;
                        $arr_data['reward_qualified_reward_title'] = $row->reward_title;
                        $arr_data['reward_qualified_reward_value'] = $row->reward_value;
                        $arr_data['reward_qualified_condition_sponsor'] = $row->reward_condition_sponsor;
                        $arr_data['reward_qualified_condition_point_left'] = $row->reward_condition_point_left;
                        $arr_data['reward_qualified_condition_point_right'] = $row->reward_condition_point_right;
                        $arr_data['reward_qualified_condition_point'] = $row->reward_condition_point;
                        $arr_data['reward_qualified_condition_rank_id'] = $row->reward_id;
                        $arr_data['reward_qualified_datetime'] = $datetime;
                        $arr_data['reward_qualified_status'] = 'pending';
                        $arr_data['reward_qualified_status_datetime'] = $datetime;
                        $arr_data['reward_qualified_claim'] = 'unclaimed';
                        $arr_data['reward_qualified_claim_datetime'] = $datetime;

                        if ($this->common_model->insertData('sys_reward_qualified', $arr_data) == FALSE) {
                            throw new \Exception("Gagal menambah data kualifikasi reward.", 1);
                        }
                    }
                }
            }
        }

        return $this->arr_bonus[$member_id]["cash_reward"] ?? 0;
    }
}
