<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Reward_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function insert_reward($member_id, $point, $class, $datetime)
    {
        $this->insert_reward_achievement($member_id, $point, $class, $datetime);

        //jika poin lebih dari nol, maka jalankan insert_reward_netgrow & calculate_reward_qualified
        if ($point > 0) {
            //jika member bukan id root, maka jalankan insert_reward_netgrow
            if ($member_id != 1) {
                $this->insert_reward_netgrow($member_id, $point, $class, $datetime);
            }
            $this->calculate_reward_qualified($datetime);
        }
    }

    private function insert_reward_achievement($member_id, $point, $class, $datetime)
    {
        //cek apakah class tsb sudah ada
        $reward_achievement_id = $this->common_model->getOne('sys_b_reward_achievement', 'reward_achievement_id', ['reward_achievement_member_id' => $member_id, 'reward_achievement_class' => $class]);

        //jika sudah ada class tsb, maka insert
        //jika belum ada, maka update
        if ($reward_achievement_id == '') {
            $arr_data = [];
            $arr_data['reward_achievement_member_id'] = $member_id;
            $arr_data['reward_achievement_point_personal'] = $point;
            $arr_data['reward_achievement_class'] = $class;
            $arr_data['reward_achievement_last_update_datetime'] = $datetime;

            if ($this->common_model->insertData('sys_b_reward_achievement', $arr_data) == FALSE) {
                throw new \Exception("Gagal menambah data pencapaian reward.", 1);
            }
        } else {
            $builder = $this->db->table('sys_b_reward_achievement');
            $builder->set("reward_achievement_point_personal", "reward_achievement_point_personal + {$point}", FALSE);
            $builder->where('reward_achievement_id', $reward_achievement_id);
            $builder->update();
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambah data pencapaian reward.", 1);
            }
        }
    }

    private function insert_reward_netgrow($member_id, $point, $class, $datetime)
    {
        $arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $member_id]));
        $date = date('Y-m-d', strtotime($datetime));

        foreach ($arr_network_upline as $arr_upline_data) {
            $arr_upline_data = (object) $arr_upline_data;

            $arr_data = [];
            $arr_data['reward_netgrow_class'] = $class;
            $arr_data['reward_netgrow_member_id'] = $arr_upline_data->id;
            $arr_data['reward_netgrow_downline_member_id'] = $member_id;
            $arr_data['reward_netgrow_point'] = $point;
            $arr_data['reward_netgrow_position'] = $arr_upline_data->pos;
            $arr_data['reward_netgrow_level'] = $arr_upline_data->level;
            $arr_data['reward_netgrow_date'] = $date;
            $arr_data['reward_netgrow_datetime'] = $datetime;

            if ($this->common_model->insertData('sys_b_reward_netgrow', $arr_data) == FALSE) {
                throw new \Exception("Gagal menambah data perkembangan jaringan ro.", 1);
            }

            //cek apakah class tsb sudah ada di reward achievement upline
            $reward_achievement_id = $this->common_model->getOne('sys_b_reward_achievement', 'reward_achievement_id', ['reward_achievement_member_id' => $arr_upline_data->id, 'reward_achievement_class' => $class]);

            //jika sudah ada class tsb, maka insert
            //jika belum ada, maka update
            if ($reward_achievement_id == '') {
                $arr_data = [];
                $arr_data['reward_achievement_member_id'] = $member_id;
                if ($arr_upline_data->pos == 'L') {
                    $arr_data['reward_achievement_point_left'] = $point;
                } elseif ($arr_upline_data->pos == 'R') {
                    $arr_data['reward_achievement_point_right'] = $point;
                }
                $arr_data['reward_achievement_class'] = $class;
                $arr_data['reward_achievement_last_update_datetime'] = $datetime;

                if ($this->common_model->insertData('sys_b_reward_achievement', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data pencapaian reward.", 1);
                }
            } else {
                if ($arr_upline_data->pos == 'L') {
                    $builder = $this->db->table('sys_b_reward_achievement');
                    $builder->set("reward_achievement_point_left", "reward_achievement_point_left + {$point}", FALSE);
                    $builder->set("reward_achievement_last_update_datetime", $datetime);
                    $builder->where('reward_achievement_id', $reward_achievement_id);
                    $builder->update();
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal mengubah data perkembangan poin reward.", 1);
                    }
                } elseif ($arr_upline_data->pos == 'R') {
                    $builder = $this->db->table('sys_b_reward_achievement');
                    $builder->set("reward_achievement_point_right", "reward_achievement_point_right + {$point}", FALSE);
                    $builder->set("reward_achievement_last_update_datetime", $datetime);
                    $builder->where('reward_achievement_id', $reward_achievement_id);
                    $builder->update();
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal mengubah data perkembangan poin reward.", 1);
                    }
                }
            }
        }
    }

    private function calculate_reward_qualified($datetime)
    {
        $sql = "SELECT * FROM sys_b_reward";
        $query = $this->db->query($sql);
        foreach ($query->getResult() as $row) {
            $sql_achievement = "
                SELECT *
                FROM sys_b_reward_achievement
                WHERE reward_achievement_point_left >= {$row->reward_condition_point_left}
                AND reward_achievement_point_right >= {$row->reward_condition_point_right}
                AND reward_achievement_is_qualified = 0
            ";
            $query_achievement = $this->db->query($sql_achievement);
            foreach ($query_achievement->getResult() as $row_achievement) {
                $arr_data = [];
                $arr_data['reward_qualified_member_id'] = $row_achievement->reward_achievement_member_id;
                $arr_data['reward_qualified_reward_id'] = $row->reward_id;
                $arr_data['reward_qualified_reward_title'] = $row->reward_title;
                $arr_data['reward_qualified_reward_value'] = $row->reward_value;
                $arr_data['reward_qualified_reward_class'] = $row_achievement->reward_achievement_class;
                $arr_data['reward_qualified_condition_sponsor'] = 0;
                $arr_data['reward_qualified_condition_point_left'] = $row->reward_condition_point_left;
                $arr_data['reward_qualified_condition_point_right'] = $row->reward_condition_point_right;
                $arr_data['reward_qualified_datetime'] = $datetime;

                if ($this->common_model->insertData('sys_b_reward_qualified', $arr_data) == FALSE) {
                    throw new \Exception("Gagal menambah data kualifikasi reward.", 1);
                }

                if (WA_NOTIFICATION_IS_ACTIVE) {
                    $client_name = COMPANY_NAME;
                    $client_wa_cs_number = WA_CS_NUMBER;
                    $member_name = $this->mlm_service->get_member_name_by_member_id($row_achievement->reward_achievement_member_id);
                    $member_mobilephone = $this->mlm_service->get_member_mobilephone_by_member_id($row_achievement->reward_achievement_member_id);

                    $content = "*Pemberitahuan Kualifikasi Reward*
Hai {$member_name},
Selamat anda masuk kualifikasi mendapatkan reward kelas *{$row_achievement->reward_achievement_class}* *{$row->reward_title}*.

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}
                    ";

                    $this->notification_lib->send_waone($content, phoneNumberFilter($member_mobilephone));
                }
            }
        }
    }
}
