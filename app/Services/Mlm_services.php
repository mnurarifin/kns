<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\DataTable;

class Mlm_services extends BaseController
{
    public $request;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->dataTable = new DataTable();
    }

    public function init($request = null)
    {
        $this->request = $request;
    }

    public function get_last_leg_position($member_id)
    {
        $total_downline = $this->db->table("sys_network")->getWhere(["network_upline_member_id" => $member_id])->getResult();
        return $total_downline ? count($total_downline) : 0;
    }

    public function get_serial_type_by_serial_id($serial_id)
    {
        $sql = "
            SELECT sys_serial_type.*
            FROM sys_serial
            INNER JOIN sys_serial_type ON serial_type_id = serial_serial_type_id
            WHERE serial_id = '{$serial_id}'
        ";
        $row = $this->db->query($sql)->getRow();
        if (!is_null($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public function get_serial_ro_type_by_serial_ro_id($serial_ro_id)
    {
        $sql = "
            SELECT sys_serial_ro_type.*
            FROM sys_serial_ro
            INNER JOIN sys_serial_ro_type ON serial_ro_type_id = serial_ro_serial_ro_type_id
            WHERE serial_ro_id = '{$serial_ro_id}'
        ";
        $row = $this->db->query($sql)->getRow();
        if (!is_null($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public function get_serial_id_by_serial_type($serial_type_id, $member_id)
    {
        $sql = "
            SELECT serial_member_stock_serial_id
            FROM sys_serial_member_stock
            WHERE serial_member_stock_serial_type_id = '{$serial_type_id}'
            AND serial_member_stock_member_id = '{$member_id}'
        ";
        $row = $this->db->query($sql)->getRow();
        if (!is_null($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public function get_member_id_by_network_code($network_code)
    {
        return $this->common_model->getOne('sys_network', 'network_member_id', ['network_code' => $network_code]);
    }

    public function get_network_by_network_code($network_code)
    {
        return $this->common_model->getDetail('sys_network', 'network_code', $network_code);
    }

    public function get_network_by_member_id($member_id)
    {
        return $this->common_model->getDetail('sys_network', 'network_member_id', $member_id);
    }

    public function get_member_by_member_id($member_id)
    {
        return $this->common_model->getDetail('sys_member', 'member_id', $member_id);
    }

    public function get_network_code_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_network', 'network_code', ['network_member_id' => $member_id]);
    }

    public function get_member_account_username_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_member_account', 'member_account_username', ['member_account_member_id' => $member_id]);
    }

    public function get_member_account_username_by_network_code($network_code)
    {
        return $this->db->table('sys_network')->select('member_account_username')->join('sys_member_account', 'member_account_member_id = network_member_id')->getWhere(['network_code' => $network_code])->getRow('member_account_username');
    }

    public function get_member_mobilephone_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_member', 'member_mobilephone', ['member_id' => $member_id]);
    }

    public function get_member_name_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_member', 'member_name', ['member_id' => $member_id]);
    }

    public function get_sponsor_member_id_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_network', 'network_sponsor_member_id', ['network_member_id' => $member_id]);
    }

    public function get_serial_type_id_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_network', 'network_serial_type_id', ['network_member_id' => $member_id]);
    }

    public function get_serial_type_name_by_member_id($member_id)
    {
        return $this->common_model->getOne('sys_network', 'network_serial_type_name', ['network_member_id' => $member_id]);
    }

    public function get_count_sponsoring_by_date_member_id($member_id, $date)
    {
        return $this->common_model->getCount('sys_netgrow_sponsor', 'netgrow_sponsor_id', ['netgrow_sponsor_member_id' => $member_id, 'netgrow_sponsor_date' => $date]);
    }

    /**
     * function untuk mendapatkan titik downline terluar
     * @param int $member_id id member yang dicari
     * @return Array
     */
    public function get_network_outer_node_by_member_id($member_id)
    {
        $arr_result = [];
        $sql = "
            SELECT network_outer_node_left_member_id AS node_left_member_id,
            network_left.network_code AS node_left_network_code,
            network_left.network_serial_type_name AS node_left_serial_type_name,
            member_left.member_name AS node_left_member_name,
            network_outer_node_right_member_id AS node_right_member_id,
            network_right.network_code AS node_right_network_code,
            network_right.network_serial_type_name AS node_right_serial_type_name,
            member_right.member_name AS node_right_member_name
            FROM sys_network_outer_node
            INNER JOIN sys_network network_left ON network_left.network_member_id = network_outer_node_left_member_id
            INNER JOIN sys_network network_right ON network_right.network_member_id = network_outer_node_right_member_id
            INNER JOIN sys_member member_left ON member_left.member_id = network_outer_node_left_member_id
            INNER JOIN sys_member member_right ON member_right.member_id = network_outer_node_right_member_id
            WHERE network_outer_node_member_id = {$member_id}
        ";
        $row = $this->db->query($sql)->getRow();
        if (!empty($row)) {
            $arr_result = [
                'left' => [
                    'member_id' => $row->node_left_member_id,
                    'member_name' => $row->node_left_member_name,
                    'network_code' => $row->node_left_network_code,
                    'serial_type_name' => $row->node_left_serial_type_name,
                ],
                'right' => [
                    'member_id' => $row->node_right_member_id,
                    'member_name' => $row->node_right_member_name,
                    'network_code' => $row->node_right_network_code,
                    'serial_type_name' => $row->node_right_serial_type_name,
                ],
            ];
        }

        return $arr_result;
    }

    /**
     * function untuk mendapatkan titik downline terluar
     * @param int $member_id id member yang dicari
     * @return Array
     */
    public function get_info_member_by_member_id($member_id)
    {
        $sql = "
            SELECT main_member.member_id AS member_id,
            main_member.member_name AS member_name,
            main_member.member_mobilephone AS member_mobilephone,
            main_member.member_image AS member_image,
            main_member.member_join_datetime AS member_join_datetime,
            main_network.network_code AS network_code,
            main_network.network_position AS network_position,
            (
                CASE main_network.network_position
                    WHEN 'L' THEN 'Kiri'
                    WHEN 'R' THEN 'Kanan'
                    ELSE '-'
                END
            ) AS network_position_text,
            main_network.network_left_node_member_id AS network_left_node_member_id,
            main_network.network_right_node_member_id AS network_right_node_member_id,
            main_network.network_total_node_left AS network_total_node_left,
            main_network.network_total_node_right AS network_total_node_right,
            main_network.network_total_point_left AS network_total_point_left,
            main_network.network_total_point_right AS network_total_point_right,
            main_network.network_total_sponsoring AS network_total_sponsoring,
            main_network.network_is_active AS network_is_active,
            main_network.network_activation_datetime AS network_activation_datetime,
            main_network.network_is_active AS network_is_active,
            main_network.network_is_suspended AS network_is_suspended,
            main_network.network_serial_type_id AS network_serial_type_id,
            main_network.network_serial_type_name AS network_serial_type_name,
            main_network.network_sponsor_member_id AS sponsor_member_id,
            IFNULL(main_network.network_sponsor_network_code, '-') AS sponsor_network_code,
            IFNULL(sponsor_member_account.member_account_username, '-') AS sponsor_username,
            main_network.network_upline_member_id AS upline_member_id,
            IFNULL(main_network.network_upline_network_code, '-') AS upline_network_code,
            IFNULL(upline_member_account.member_account_username, '-') AS upline_username,
            main_member_account.member_account_username AS member_account_username
            FROM sys_member main_member
            JOIN sys_network main_network ON main_network.network_member_id = main_member.member_id
            JOIN sys_member_account main_member_account ON main_network.network_member_id = main_member_account.member_account_member_id
            LEFT JOIN sys_member_account sponsor_member_account ON main_network.network_sponsor_member_id = sponsor_member_account.member_account_member_id
            LEFT JOIN sys_member_account upline_member_account ON main_network.network_upline_member_id = upline_member_account.member_account_member_id
            WHERE main_member.member_id = {$member_id}
        ";
        $row = $this->db->query($sql)->getRow();
        if (!empty($row)) {
            return $row;
        } else {
            return FALSE;
        }
    }

    //fungsi untuk mendapatkan paket yang dihitung sebagai bonus
    //jika hari ini upgrade, maka masih menggunakan paket sebelumnya
    public function get_applied_product_package_id_by_member_id($member_id, $date)
    {
        $product_package_id = '';
        $sql = "
            SELECT member_plan_activity_product_package_id AS product_package_id
            FROM sys_member_plan_activity
            WHERE member_plan_activity_member_id = {$member_id}
            AND member_plan_activity_type IN ('activation', 'upgrade')
            AND (IF(member_plan_activity_type = 'upgrade', DATE(member_plan_activity_datetime) < '{$date}', TRUE))
            ORDER BY member_plan_activity_datetime DESC, member_plan_activity_product_package_id DESC
            LIMIT 1
        ";
        $row = $this->db->query($sql)->getRow();
        if (!is_null($row)) {
            $product_package_id = $row->product_package_id;
        }

        return $product_package_id;
    }

    public function get_serial_type_id_by_serial_id($serial_id)
    {
        return $this->common_model->getOne('sys_serial', 'serial_serial_type_id', ['serial_id' => $serial_id]);
    }

    /**
     * function untuk generate genealogi binary
     * @param int $root_network_code batas kode member yang diperbolehkan
     * @param int $top_network_code kode member paling atas dari genealogi yang sedang ditampilkan
     * @param int $level_depth kedalaman level
     * @return Array
     */
    public function get_genealogy_binary($root_network_code, $top_network_code, $level_depth = 3)
    {
        $arr_data = [];
        $root_member_id = $this->get_member_id_by_network_code($root_network_code);
        $top_member_id = $this->get_member_id_by_network_code($top_network_code);
        $outer_left_member_id = $this->common_model->getOne('sys_network_outer_node', 'network_outer_node_left_member_id', ['network_outer_node_member_id' => $root_member_id]);
        $outer_right_member_id = $this->common_model->getOne('sys_network_outer_node', 'network_outer_node_right_member_id', ['network_outer_node_member_id' => $root_member_id]);

        //hitung jumlah data berdasarkan kedalaman level
        $data_max = 0;
        $arr_level_node_start = [];
        for ($i = 0; $i <= $level_depth; $i++) {
            $pow = pow(2, $i);
            $data_max = $data_max + $pow;
            array_push($arr_level_node_start, $pow);
        }

        //cari jarak level antara root & top
        $level = $this->common_model->getOne('sys_netgrow_node', 'netgrow_node_level', ['netgrow_node_member_id' => $root_member_id, 'netgrow_node_downline_member_id' => $top_member_id]);
        if ($level == '') {
            $level = 0;
        }

        $upline = 0;
        for ($x = 1; $x <= $data_max; $x++) {
            if ($x % 2 == 0) {
                $position = 'L';
                $downline_node_member_id = 'network_left_node_member_id';
                $upline++;
            } else {
                $position = 'R';
                $downline_node_member_id = 'network_right_node_member_id';
            }

            if ($x == 1) {
                $member_id = $top_member_id;
            } else {
                if (isset($arr_data[$upline][$downline_node_member_id])) {
                    $member_id = $arr_data[$upline][$downline_node_member_id];
                } else {
                    $member_id = '';
                }

                if (in_array($x, $arr_level_node_start)) {
                    $level++;
                }
            }

            if ($member_id == '') {
                $arr_data[$x] = [];
                $arr_data[$x]['genealogy_status'] = 'blank';
                $arr_data[$x]['sort'] = $x;
                $arr_data[$x]['genealogy_parent'] = $upline;
                $arr_data[$x]['genealogy_level'] = $level;
            } else {
                $row = $this->get_info_member_by_member_id($member_id);
                if ($row != FALSE) {
                    $row = (array) $row;
                    if ($row['member_image'] != '' && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $row['member_image'])) {
                        $profile_image = $row['member_image'];
                    } else {
                        $profile_image = '_default.png';
                    }

                    $arr_data[$x] = $row;
                    $arr_data[$x]['genealogy_status'] = 'filled';
                    $arr_data[$x]['sort'] = $x;
                    $arr_data[$x]['genealogy_parent'] = $upline;
                    $arr_data[$x]['genealogy_level'] = $level;
                    $arr_data[$x]['genealogy_image'] = UPLOAD_URL . URL_IMG_MEMBER . $profile_image;
                } else {
                    $outer =
                        ($position == 'L' && $outer_left_member_id == $arr_data[$upline]['member_id']) ||
                        ($position == 'R' && $outer_right_member_id == $arr_data[$upline]['member_id']);

                    if ($outer || $root_member_id == 1) {
                        $arr_data[$x] = [];
                        $arr_data[$x]['genealogy_status'] = 'empty';
                        $arr_data[$x]['sort'] = $x;
                        $arr_data[$x]['genealogy_parent'] = $upline;
                        $arr_data[$x]['genealogy_level'] = $level;
                        $arr_data[$x]['genealogy_upline_network_code'] = $arr_data[$upline]['network_code'];
                        $arr_data[$x]['genealogy_upline_username'] = $arr_data[$upline]['member_account_username'];
                        $arr_data[$x]['genealogy_node_position'] = $position;
                        $arr_data[$x]['activation'] = TRUE;
                        $arr_data[$x]['cloning'] = $outer ? TRUE : FALSE;
                    } else {
                        $arr_data[$x] = [];
                        $arr_data[$x]['genealogy_status'] = 'blank';
                        $arr_data[$x]['sort'] = $x;
                        $arr_data[$x]['genealogy_parent'] = $upline;
                        $arr_data[$x]['genealogy_level'] = $level;
                    }
                }
            }
        }
        $arr_data['outer_left_username'] = $this->common_model->getOne('sys_member_account', 'member_account_username', ['member_account_member_id' => $outer_left_member_id]);
        $arr_data['outer_right_username'] = $this->common_model->getOne('sys_member_account', 'member_account_username', ['member_account_member_id' => $outer_right_member_id]);
        return $arr_data;
    }

    /**
     * function untuk generate genealogi unilevel
     * @param int $root_network_code batas kode member yang diperbolehkan
     * @param int $top_network_code kode member paling atas dari genealogi yang sedang ditampilkan
     * @param int $level_depth kedalaman level
     * @return Array
     */
    public function get_genealogy_unilevel_old($root_network_code, $top_network_code)
    {
        $arr_data = [];
        $root_member_id = $this->get_member_id_by_network_code($root_network_code);
        $top_member_id = $this->get_member_id_by_network_code($top_network_code);

        $obj_member = $this->db->table("sys_member_account")->join("sys_network", "network_member_id = member_account_member_id")->getWhere(["member_account_member_id" => $top_member_id])->getRow();
        $lv = $this->get_level_sponsor($root_member_id, $top_member_id);
        $arr_data[] = [
            "id" => $top_member_id,
            "text" => "{$obj_member->member_account_username} [Lv : {$lv}][Sponsoring : {$obj_member->network_total_sponsoring}]",
            "data" => [
                "username" => $obj_member->member_account_username,
                "sponsor" => $obj_member->network_total_sponsoring,
            ],
            "icon" => "bx bxs-user-circle",
        ];

        $arr_lv1 = $this->get_sponsor($top_member_id);
        foreach ($arr_lv1 as $lv1) {
            $children_lv1 = [];
            $arr_lv2 = $this->get_sponsor($lv1->network_member_id);
            foreach ($arr_lv2 as $lv2) {
                $children_lv1[] = TRUE;
            }
            $lv = $this->get_level_sponsor($root_member_id, $lv1->network_member_id);
            $arr_data[0]["children"][] = [
                "id" => $lv1->network_member_id,
                "text" => "{$lv1->member_account_username} [Lv : {$lv}][Sponsoring : {$lv1->network_total_sponsoring}]",
                "data" => [
                    "username" => $lv1->member_account_username,
                    "sponsor" => $lv1->network_total_sponsoring,
                ],
                "children" => !empty($children_lv1) ? TRUE : FALSE,
                "icon" => "bx bxs-user-circle",
            ];
        }
        return $arr_data;
    }
    public function get_genealogy_unilevel($type, $network_code, $root_network_code)
    {
        $select = "
        member_id,
        member_name,
        member_image,
        network_code,
        network_total_sponsoring,
        network_upline_leg_position
        ";

        if ($type == "upline") {
            $arr_data = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->select($select)->getWhere(["network_code" => $network_code])->getResult();
            foreach ($arr_data as $key => $value) {
                $arr_data[$key]->network_total_downline = count($this->db->table("sys_netgrow_node")->getWhere(["netgrow_node_member_id" => $value->member_id])->getResult());
                $arr_data[$key]->reward_point = $this->db->table("sys_reward_point")->select("reward_point_acc")->getWhere(["reward_point_member_id" => $value->member_id])->getRow("reward_point_acc");
                $arr_data[$key]->reward_trip_point = $this->db->table("sys_reward_trip_point")->select("reward_point_acc")->getWhere(["reward_point_member_id" => $value->member_id])->getRow("reward_point_acc");
                $arr_data[$key]->network_upline_leg_position_text = $value->network_upline_leg_position == 0 || $value->network_code == $root_network_code ? "ANDA" : "LEG {$value->network_upline_leg_position}";
                $member_name = explode(" ", $value->member_name);
                $arr_data[$key]->member_name_short = $member_name[0] . (isset($member_name[1]) ? " " . $member_name[1][0] : "") . (isset($member_name[2]) ? " " . $member_name[2][0] : "") . (isset($member_name[3]) ? " " . $member_name[3][0] : "");
                if ($value->member_image && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $value->member_image)) {
                    $arr_data[$key]->member_image = UPLOAD_URL . URL_IMG_MEMBER . $value->member_image;
                } else {
                    $arr_data[$key]->member_image = UPLOAD_URL . URL_IMG_MEMBER . "_default.png";
                }
            }
            if ($network_code == $root_network_code) {
                $level = 0;
            } else {
                $root_member_id = $this->common_model->getOne('sys_network', 'network_member_id', ['network_code' => $root_network_code]);
                $member_id = $this->common_model->getOne('sys_network', 'network_member_id', ['network_code' => $network_code]);
                $network_upline_arr_data = json_decode($this->db->table("sys_network_upline")->getWhere(["network_upline_member_id" => $member_id])->getRow("network_upline_arr_data"));
                foreach ($network_upline_arr_data as $key => $value) {
                    if ($value->id == $root_member_id) {
                        $level = $value->level;
                    }
                }
            }
        } else {
            $arr_data = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->select($select)->getWhere(["network_upline_network_code" => $network_code])->getResult();
            foreach ($arr_data as $key => $value) {
                $arr_data[$key]->network_total_downline = count($this->db->table("sys_netgrow_node")->getWhere(["netgrow_node_member_id" => $value->member_id])->getResult());
                $arr_data[$key]->reward_point = $this->db->table("sys_reward_point")->select("reward_point_acc")->getWhere(["reward_point_member_id" => $value->member_id])->getRow("reward_point_acc");
                $arr_data[$key]->reward_trip_point = $this->db->table("sys_reward_trip_point")->select("reward_point_acc")->getWhere(["reward_point_member_id" => $value->member_id])->getRow("reward_point_acc");
                $arr_data[$key]->network_upline_leg_position_text = $value->network_upline_leg_position == 0 || $value->network_code == $root_network_code ? "ANDA" : "LEG {$value->network_upline_leg_position}";
                $member_name = explode(" ", $value->member_name);
                $arr_data[$key]->member_name_short = $member_name[0] .
                    (isset($member_name[1]) && isset($member_name[1][0]) ? " " . $member_name[1][0] : "") .
                    (isset($member_name[2]) && isset($member_name[2][0]) ? " " . $member_name[2][0] : "") .
                    (isset($member_name[3]) && isset($member_name[3][0]) ? " " . $member_name[3][0] : "");
                if ($value->member_image && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $value->member_image)) {
                    $arr_data[$key]->member_image = UPLOAD_URL . URL_IMG_MEMBER . $value->member_image;
                } else {
                    $arr_data[$key]->member_image = UPLOAD_URL . URL_IMG_MEMBER . "_default.png";
                }
            }
            if ($network_code == $root_network_code) {
                $level = 1;
            } else {
                $root_member_id = $this->common_model->getOne('sys_network', 'network_member_id', ['network_code' => $root_network_code]);
                $member_id = $this->common_model->getOne('sys_network', 'network_member_id', ['network_code' => $network_code]);
                $network_upline_arr_data = json_decode($this->db->table("sys_network_upline")->getWhere(["network_upline_member_id" => $member_id])->getRow("network_upline_arr_data"));
                foreach ($network_upline_arr_data as $key => $value) {
                    if ($value->id == $root_member_id) {
                        $level = $value->level + 1;
                    }
                }
            }
        }

        return [
            "genealogy" => $arr_data,
            "level" => $level,
        ];
    }

    /**
     * function untuk mencari mitra by sponsor
     * @param int $sponsor_member_id id member sponsor
     * @return Array
     */
    public function get_sponsor($sponsor_member_id)
    {
        $sql = "SELECT
        network_member_id,
        member_account_username,
        network_total_sponsoring
        FROM sys_network
        JOIN sys_member_account ON member_account_member_id = network_member_id
        WHERE network_sponsor_member_id = {$sponsor_member_id}";
        return $this->db->query($sql)->getResult();
    }

    /**
     * function untuk mencari level member dengan sponsor
     * @param int $sponsor_member_id id member sponsor
     * @param int $downline_member_id id member downline
     * @return Int
     */
    public function get_level_sponsor($sponsor_member_id, $downline_member_id)
    {
        $arr_network_sponsor = $this->common_model->getOne('sys_network_sponsor', 'network_sponsor_arr_data', ['network_sponsor_member_id' => $downline_member_id]);
        if ($arr_network_sponsor != '') {
            $arr_network_sponsor = json_decode($arr_network_sponsor);
            foreach ($arr_network_sponsor as $value) {
                if ($value->id == $sponsor_member_id) {
                    return $value->level;
                }
            }
        }

        return 0;
    }

    /**
     * function untuk mencari level member dengan upline
     * @param int $upline_member_id id member upline
     * @param int $downline_member_id id member downline
     * @return Int
     */
    public function get_level_upline($upline_member_id, $downline_member_id)
    {
        $arr_network_upline = $this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $downline_member_id]);
        if ($arr_network_upline != '') {
            $arr_network_upline = json_decode($arr_network_upline);
            foreach ($arr_network_upline as $value) {
                if ($value->id == $upline_member_id) {
                    return $value->level;
                }
            }
        }

        return 0;
    }

    /**
     * function untuk mencari posisi member dengan sponsor
     * @param int $sponsor_member_id id member sponsor
     * @param int $downline_member_id id member downline
     * @return Int
     */
    public function get_sponsoring_position($sponsor_member_id, $downline_member_id)
    {
        $arr_network_upline = $this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $downline_member_id]);
        if ($arr_network_upline != '') {
            $arr_network_upline = json_decode($arr_network_upline);
            foreach ($arr_network_upline as $value) {
                if ($value->id == $sponsor_member_id) {
                    return $value->pos;
                }
            }
        }

        return '';
    }

    /**
     * function untuk pengecekan apakah member sejalur
     * @param int $top_member_id id member diatasnya
     * @param int $member_id id member downline
     * @return Boolean
     */
    public function check_line($top_member_id, $member_id)
    {
        if ($top_member_id == $member_id) {
            return TRUE;
        } else {
            $arr_network_upline = $this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $member_id]);
            if ($arr_network_upline != '') {
                $arr_network_upline = json_decode($arr_network_upline);
                foreach ($arr_network_upline as $value) {
                    if ($value->id == $top_member_id) {
                        return TRUE;
                    }
                }
            }
        }

        return FALSE;
    }

    /**
     * function untuk menampilkan data sponsoring
     * @param int $member_id id member login
     * @param string $top_network_code kode member yang sedang ditampilkan
     * @return Array
     */
    public function get_data_table_sponsoring($member_id, $top_network_code)
    {
        $top_member_id = $this->get_member_id_by_network_code($top_network_code);
        $tableName = "sys_netgrow_sponsor";
        $columns = [
            'member_id',
            'network_code',
            'network_product_package_name',
            'member_name',
            'member_city_id',
            'city_name',
            'member_email',
            'member_image',
            'member_mobilephone',
            'network_total_sponsoring',
            'member_join_datetime',
            'member_account_username',
        ];
        $joinTable = " JOIN sys_member ON member_id = netgrow_sponsor_downline_member_id
        JOIN sys_member_account ON member_account_member_id = member_id
        JOIN sys_network ON network_member_id = member_id
        LEFT JOIN ref_city ON member_city_id = city_id ";
        $whereCondition = " netgrow_sponsor_member_id = {$top_member_id} ";
        $groupBy = " GROUP BY member_id ";

        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            if ($value['member_image'] != '' && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $value['member_image'])) {
                $profile_image = $value['member_image'];
            } else {
                $profile_image = '_default.png';
            }
            $arr_data['results'][$key]['image'] = UPLOAD_URL . URL_IMG_MEMBER . $profile_image;
            $arr_data['results'][$key]['arr_member'] = [
                "network_code" => $value["network_code"],
                "member_name" => $value["member_name"],
                "city_name" => $value["city_name"],
                "member_account_username" => $value["member_account_username"],
            ];
            $arr_data['results'][$key]['arr_mobilephone_email'] = ["member_mobilephone" => $value["member_mobilephone"], "member_email" => $value["member_email"]];
            $arr_data['results'][$key]['member_join_datetime_formatted'] = convertDatetime($value['member_join_datetime'], 'id');
        }

        $arr_top_network = [
            'member_account_username' => '',
            'network_id' => '',
            'network_code' => '',
            'total_sponsoring' => '',
            'level' => '',
            'sponsor_member_id' => '',
            'sponsor_network_code' => '',
            'sponsor_username' => '',
        ];
        $obj_top_network = $this->get_network_by_network_code($top_network_code);
        if (!is_null($obj_top_network)) {
            $arr_top_network = [
                'member_account_username' => $this->common_model->getOne("sys_member_account", "member_account_username", ["member_account_member_id" => $obj_top_network->network_member_id]),
                'network_id' => $obj_top_network->network_member_id,
                'network_code' => $obj_top_network->network_code,
                'network_total_sponsoring' => $obj_top_network->network_total_sponsoring,
                'level' => $this->get_level_sponsor($member_id, $obj_top_network->network_member_id),
                'sponsor_member_id' => $obj_top_network->network_sponsor_member_id,
                'sponsor_username' => $this->common_model->getOne("sys_member_account", "member_account_username", ["member_account_member_id" => $obj_top_network->network_sponsor_member_id]),
            ];
        }

        return array_merge($arr_data, $arr_top_network);
    }

    /**
     * function untuk menampilkan data downline
     * @param int $member_id id member login
     * @return Array
     */
    public function get_data_table_downline($member_id)
    {
        $tableName = "sys_netgrow_node";
        $columns = [
            'member_account_username',
            'netgrow_node_downline_member_id',
            'network_code',
            'member_name',
            'member_email',
            'member_join_datetime',
            'netgrow_node_level',
        ];
        $joinTable = " JOIN sys_network ON network_member_id = netgrow_node_downline_member_id
        JOIN sys_member ON network_member_id = member_id
        JOIN sys_member_account ON member_account_member_id = member_id ";
        $whereCondition = " netgrow_node_member_id = {$member_id} ";
        $groupBy = '';

        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['member_join_datetime_formatted'] = convertDatetime($value['member_join_datetime'], 'id');
        }

        return $arr_data;
    }

    /**
     * function untuk menampilkan data pertumbuhan jaringan
     * @param int $member_id id member login
     * @return Array
     */
    public function get_data_table_netgrow($member_id)
    {
        $tableName = "sys_netgrow_master";
        $columns = [
            'netgrow_master_date' => 'master_date',
            'netgrow_master_member_id' => 'master_member_id',
            'netgrow_master_node_left' => 'master_node_left',
            'netgrow_master_node_right' => 'master_node_right',
            'netgrow_master_point_left' => 'master_point_left',
            'netgrow_master_point_right' => 'master_point_right',
            'netgrow_master_wait_left' => 'master_wait_left',
            'netgrow_master_wait_right' => 'master_wait_right',
            'netgrow_master_match' => 'master_match',
            'netgrow_master_match_real' => 'master_match_real',
            'netgrow_master_match_bonus' => 'master_match_bonus',
        ];
        $joinTable = "  ";
        $whereCondition = " netgrow_master_member_id = {$member_id} ";
        $groupBy = '';

        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);
        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['master_date'] = convertDatetime($value['master_date'], 'id');
            $arr_data['results'][$key]['master_sponsor'] = $this->get_count_sponsoring_by_date_member_id($value['master_member_id'], $value['master_date']);
        }

        //dapatkan informasi pertumbuhan hari ini
        $date = date('Y-m-d');

        //dapatkan sponsoring hari ini
        $today_sponsoring = $this->get_count_sponsoring_by_date_member_id($member_id, $date);

        //dapatkan pasangan hari ini
        $today_match_real = $this->common_model->getCount('sys_netgrow_match', 'netgrow_match_id', ['netgrow_match_member_id' => $member_id, 'netgrow_match_date' => $date, 'netgrow_match_bonus >' => 0]);
        $today_match_real = $this->common_model->getCount('sys_netgrow_match', 'netgrow_match_id', ['netgrow_match_member_id' => $member_id, 'netgrow_match_date' => $date]);

        //dapatkan titik/poin menunggu hari ini
        $obj_wait = $this->common_model->getDetail('sys_netgrow_wait', 'netgrow_wait_member_id', $member_id);
        if (!is_null($obj_wait)) {
            $today_wait_left = $obj_wait->netgrow_wait_left;
            $today_wait_right = $obj_wait->netgrow_wait_right;
        } else {
            $today_wait_left = $today_wait_right = 0;
        }

        //dapatkan pertumbuhan titik/poin hari ini
        $sql = "
            SELECT IFNULL(SUM(IF(netgrow_node_position = 'L', 1, 0)), 0) AS node_left,
            IFNULL(SUM(IF(netgrow_node_position = 'R', 1, 0)), 0) AS node_right,
            IFNULL(SUM(IF(netgrow_node_position = 'L', netgrow_node_point, 0)), 0) AS point_left,
            IFNULL(SUM(IF(netgrow_node_position = 'R', netgrow_node_point, 0)), 0) AS point_right
            FROM sys_netgrow_node
            WHERE netgrow_node_member_id = {$member_id}
            AND netgrow_node_date = '{$date}'
        ";
        $row = $this->db->query($sql)->getRow();
        $today_node_left = $row->node_left;
        $today_node_right = $row->node_right;
        $today_point_left = $row->point_left;
        $today_point_right = $row->point_right;

        $arr_today = [
            'master_date' => convertDatetime($date, 'id'),
            'master_network_id' => $member_id,
            'master_node_left' => $today_node_left,
            'master_node_right' => $today_node_right,
            'master_point_left' => $today_point_left,
            'master_point_right' => $today_point_right,
            'master_wait_left' => $today_wait_left,
            'master_wait_right' => $today_wait_right,
            'master_match' => $today_match_real,
            'master_match_real' => $today_match_real,
            'master_sponsor' => $today_sponsoring,
        ];

        return array_merge($arr_data, $arr_today);
    }
}
