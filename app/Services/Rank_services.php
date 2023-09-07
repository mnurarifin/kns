<?php
namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;

class Rank_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->mlm_service = service('Mlm');

        $this->notification_lib = new Notification();
    }

    public function calculate_rank_qualified($datetime) {
        $date = date("Y-m-d", strtotime($datetime));

        //ambil data master peringkat
        $sql = "SELECT * FROM sys_rank WHERE rank_is_active = 1 ORDER BY rank_order_by ASC";
        $query = $this->db->query($sql);
        foreach($query->getResult() as $row) {
            $rank_id = $row->rank_id;
            $rank_title = $row->rank_title;
            $rank_order_by = $row->rank_order_by;
            $condition_point_left = $row->rank_condition_point_left;
            $condition_point_right = $row->rank_condition_point_right;
            $condition_downline_rank_id = $row->rank_condition_downline_rank_id;
            $condition_downline_rank_left_count = $row->rank_condition_downline_rank_left_count;
            $condition_downline_rank_right_count = $row->rank_condition_downline_rank_right_count;

            //ambil data poin
            $sql_point = "
                SELECT network_member_id,
                network_total_point_left,
                network_total_point_right
                FROM sys_network
                WHERE network_rank_id < {$rank_id}
            ";
            $query_point = $this->db->query($sql_point);
            foreach($query_point->getResult() as $row_point) {
                $member_id = $row_point->network_member_id;
                $point_left = $row_point->network_total_point_left;
                $point_right = $row_point->network_total_point_right;
                $downline_rank_id = $row->rank_condition_downline_rank_id;

                //ambil data syarat peringkat downline
                $sql_downline_rank = "
                    SELECT IFNULL(SUM(IF(rank_netgrow_position = 'L', 1, 0)), 0) AS downline_count_left,
                    IFNULL(SUM(IF(rank_netgrow_position = 'R', 1, 0)), 0) AS downline_count_right
                    FROM sys_rank_netgrow
                    WHERE rank_netgrow_member_id = {$member_id}
                    AND rank_netgrow_rank_id = {$downline_rank_id}
                ";
                $row_downline_rank = $this->db->query($sql_downline_rank)->getRow();
                $downline_count_left = $row_downline_rank->downline_count_left;
                $downline_count_right = $row_downline_rank->downline_count_right;

                //cek apakah pada rank_id tsb sudah ada datanya di rank_achievement dan apakah sudah qualified
                $sql_achievement = "
                    SELECT rank_achievement_id,
                    rank_achievement_is_qualified
                    FROM sys_rank_achievement
                    WHERE rank_achievement_member_id = {$member_id}
                    AND rank_achievement_rank_id = {$rank_id}
                ";
                $row_achievement = $this->db->query($sql_achievement)->getRow();
                if(!is_null($row_achievement)) {
                    $rank_achievement_id = $row_achievement->rank_achievement_id;
                    $rank_achievement_is_qualified = $row_achievement->rank_achievement_is_qualified;
                    if($rank_achievement_is_qualified == 0) {
                        $arr_data = [];
                        $arr_data['rank_achievement_point_left'] = $point_left;
                        $arr_data['rank_achievement_point_right'] = $point_right;
                        $arr_data['rank_achievement_downline_rank_left_count'] = $downline_count_left;
                        $arr_data['rank_achievement_downline_rank_right_count'] = $downline_count_right;
                        $arr_data['rank_achievement_last_update_datetime'] = $datetime;
                        if($this->common_model->updateData('sys_rank_achievement', 'rank_achievement_id', $rank_achievement_id, $arr_data) == FALSE) {
                            throw new \Exception("Gagal mengubah data pencapaian poin peringkat.", 1);
                        }
                    }
                } else {
                    $arr_data = [];
                    $arr_data['rank_achievement_member_id'] = $member_id;
                    $arr_data['rank_achievement_rank_id'] = $rank_id;
                    $arr_data['rank_achievement_point_left'] = $point_left;
                    $arr_data['rank_achievement_point_right'] = $point_right;
                    $arr_data['rank_achievement_downline_rank_id'] = $downline_rank_id;
                    $arr_data['rank_achievement_downline_rank_left_count'] = $downline_count_left;
                    $arr_data['rank_achievement_downline_rank_right_count'] = $downline_count_right;
                    $arr_data['rank_achievement_last_update_datetime'] = $datetime;
                    if($this->common_model->insertData('sys_rank_achievement', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data pencapaian poin peringkat.", 1);
                    }
                    $rank_achievement_id = $this->db->insertID();
                    $rank_achievement_is_qualified = 0;
                }

                if($point_left >= $condition_point_left &&
                    $point_right >= $condition_point_right &&
                    $downline_count_left >= $condition_downline_rank_left_count &&
                    $downline_count_right >= $condition_downline_rank_right_count &&
                    $rank_achievement_is_qualified == 0) {

                    //insert data log kualifikasi peringkat
                    $arr_data = [];
                    $arr_data['rank_qualified_member_id'] = $member_id;
                    $arr_data['rank_qualified_rank_id'] = $rank_id;
                    $arr_data['rank_qualified_rank_title'] = $rank_title;
                    $arr_data['rank_qualified_rank_order_by'] = $rank_order_by;
                    $arr_data['rank_qualified_condition_point_left'] = $condition_point_left;
                    $arr_data['rank_qualified_condition_point_right'] = $condition_point_right;
                    $arr_data['rank_qualified_datetime'] = $datetime;
                    if($this->common_model->insertData('sys_rank_qualified', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data kualifikasi peringkat.", 1);
                    }

                    //update peringkat di data network
                    $builder = $this->db->table('sys_network');
                    $builder->set("network_rank_id", $rank_id);
                    $builder->where('network_member_id', $member_id);
                    $builder->update();
                    if($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal mengubah data peringkat.", 1);
                    }

                    //update is_qualified pada data achievement
                    $arr_data = [];
                    $arr_data['rank_achievement_is_qualified'] = 1;
                    $arr_data['rank_achievement_last_update_datetime'] = $datetime;
                    if($this->common_model->updateData('sys_rank_achievement', 'rank_achievement_id', $rank_achievement_id, $arr_data) == FALSE) {
                        throw new \Exception("Gagal update data qualified pada pencapaian peringkat.", 1);
                    }

                    if(WA_NOTIFICATION_IS_ACTIVE) {
                        $client_name = COMPANY_NAME;
                        $client_wa_cs_number = WA_CS_NUMBER;
                        $member_name = $this->mlm_service->get_member_name_by_member_id($member_id);
                        $member_mobilephone = $this->mlm_service->get_member_mobilephone_by_member_id($member_id);

                        $content = "Selamat, {$member_name},
Peringkat anda naik menjadi {$rank_title}.

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}
                        ";

                        $this->notification_lib->send_waone($content, phoneNumberFilter($member_mobilephone));
                    }

                    //update netgrow rank
                    $arr_network_upline = (array)json_decode($this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $member_id]));

                    foreach($arr_network_upline as $arr_upline_data) {
                        $arr_upline_data = (object) $arr_upline_data;

                        $arr_data = [];
                        $arr_data['rank_netgrow_member_id'] = $arr_upline_data->id;
                        $arr_data['rank_netgrow_downline_member_id'] = $member_id;
                        $arr_data['rank_netgrow_rank_id'] = $rank_id;
                        $arr_data['rank_netgrow_position'] = $arr_upline_data->pos;
                        $arr_data['rank_netgrow_level'] = $arr_upline_data->level;
                        $arr_data['rank_netgrow_date'] = $date;
                        $arr_data['rank_netgrow_datetime'] = $datetime;

                        if($this->common_model->insertData('sys_rank_netgrow', $arr_data) == FALSE) {
                            throw new \Exception("Gagal menambah data perkembangan jaringan peringkat.", 1);
                        }
                    }
                }
            }
        }
    }
}
