<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;

class Activation_services extends BaseController
{
    public function __construct()
    {
        $this->auto_placement = TRUE;
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->mlm_service = service('Mlm');
        $this->membership_service = service('Membership');
        $this->network_service = service('Network');
        $this->netgrow_service = service('Netgrow');
        $this->serial_service = service('Serial');
        $this->bonus_service = service('Bonus');
        $this->ewallet_service = service('Ewallet');
        $this->repeatorder_service = service('Repeatorder');
        $this->reward_service = service('Reward');
        $this->report_service = service('Report');
        $this->tax_service = service('Tax');

        $this->datetime = date('Y-m-d H:i:s');
        $this->date = date('Y-m-d', strtotime($this->datetime));
        $this->yesterday = date("Y-m-d", strtotime($this->date . "-1 day"));

        $this->notification_lib = new Notification();
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

    public function set_placement($network_upline_network_code)
    {
        $this->auto_placement = FALSE;
        $this->network_upline_network_code = $network_upline_network_code;
    }

    public function execute($params, $member_plan_activity_value, $type = "activation")
    {
        // $params = $this->db->table("sys_member_registration")->getWhere(["member_registration_id" => $member_registration_id])->getRow();
        // $network_code =  $this->network_service->get_network_code();
        // $network_code = $params->member_registration_username;

        // /* start membership */
        // //insert member
        // if (!isset($params->parent_member_id)) {
        //     $params->parent_member_id = 0;
        // }
        // $arr_data = [];
        // // $arr_data['member']['member_parent_member_id'] = $params->parent_member_id; //jika 1 grup hu maka isikan parent_id
        // $arr_data['member']['member_parent_member_id'] = 0;
        // $arr_data['member']['member_name'] = $params->member_name;
        // $arr_data['member']['member_email'] = $params->member_email;
        // $arr_data['member']['member_mobilephone'] = phoneNumberFilter($params->member_mobilephone);
        // $arr_data['member']['member_gender'] = $params->member_gender;
        // $arr_data['member']['member_birth_place'] = $params->member_birth_place;
        // $arr_data['member']['member_birth_date'] = $params->member_birth_date;
        // $arr_data['member']['member_address'] = $params->member_address;
        // $arr_data['member']['member_subdistrict_id'] = $params->member_subdistrict_id;
        // $arr_data['member']['member_city_id'] = $params->member_city_id;
        // $arr_data['member']['member_province_id'] = $params->member_province_id;
        // $arr_data['member']['member_bank_id'] = $params->member_bank_id;
        // $arr_data['member']['member_bank_name'] = $this->common_model->GetOne('ref_bank', 'bank_name', ['bank_id' => $params->member_bank_id]);
        // $arr_data['member']['member_bank_account_name'] = $params->member_bank_account_name;
        // $arr_data['member']['member_bank_account_no'] = $params->member_bank_account_no;
        // $arr_data['member']['member_bank_branch'] = property_exists($params, 'member_bank_branch') ? $params->member_bank_branch : '';
        // $arr_data['member']['member_identity_type'] = property_exists($params, 'member_identity_type') ? $params->member_identity_type : 'KTP';
        // $arr_data['member']['member_identity_no'] = $params->member_identity_no;
        // $arr_data['member']['member_identity_image'] = property_exists($params, 'member_identity_image') ? $params->member_identity_image : '';
        // $arr_data['member']['member_join_datetime'] = $this->datetime;
        // $arr_data['member']['member_is_network'] = '1';

        // //insert account
        // // jika username menggunakan input dari FE
        // // $arr_data['account']['member_account_username'] = $params->member_account_username;
        // // jika username menggunakan kode mitra
        // $arr_data['account']['member_account_username'] = $network_code;
        // // $arr_data['account']['member_account_password'] = $params->member_account_password;
        // $arr_data['account']['member_account_password'] = $params->member_registration_password;
        // $arr_data['account']['member_account_pin'] = generateNumber(6);

        // $member_id = $this->membership_service->save_member($arr_data); //mendapatkan ID member
        $member_id = $params->member_id; //mendapatkan ID member

        //insert plan activity
        /*
        //disable karena membership & activation sekaligus
        $arr_data = [];
        $arr_data['member_plan_activity_member_id'] = $member_id;
        $arr_data['member_plan_activity_serial_id'] = $params->serial_id;
        $arr_data['member_plan_activity_serial_type_id'] = $obj_serial_type->serial_type_id;
        $arr_data['member_plan_activity_serial_type_name'] = $obj_serial_type->serial_type_name;
        $arr_data['member_plan_activity_serial_type_price'] = $obj_serial_type->serial_type_price;
        $arr_data['member_plan_activity_type'] = "membership";
        $arr_data['member_plan_activity_note'] = "Registrasi dengan Serial {$params->serial_id}";
        $arr_data['member_plan_activity_datetime'] = $this->datetime;
        $this->serial_service->insert_member_plan_activity($arr_data);
        */

        //inisialisasi data stok produk member
        // $this->membership_service->init_inv_member_product_stock($member_id);
        /* end membership */

        /* start network */
        $params->network_sponsor_network_code = $params->member_registration_sponsor_username;
        $sponsor_member_id = $this->mlm_service->get_member_id_by_network_code($params->network_sponsor_network_code);
        if ($this->auto_placement) {
            $upline_member_id = $sponsor_member_id;
            $network_upline_network_code = $params->network_sponsor_network_code;
            $network_upline_leg_position = $network_sponsor_leg_position = $this->mlm_service->get_last_leg_position($sponsor_member_id) + 1;
        } else {
            $upline_member_id = $this->mlm_service->get_member_id_by_network_code($this->network_upline_network_code);
            $network_upline_network_code = $this->network_upline_network_code;
            $network_sponsor_leg_position = $this->db->table("sys_network")->getWhere(["network_member_id" => $upline_member_id])->getRow("network_sponsor_leg_position");
            $network_upline_leg_position = $this->mlm_service->get_last_leg_position($upline_member_id) + 1;
        }

        //insert network
        $arr_data = [];
        $arr_data['network_member_id'] = $member_id;
        $arr_data['network_code'] = $params->network_code;
        // $arr_data['network_product_package_id'] = $obj_product_package->product_package_id;
        // $arr_data['network_product_package_name'] = $obj_product_package->product_package_name;
        $arr_data['network_sponsor_member_id'] = $sponsor_member_id;
        $arr_data['network_sponsor_network_code'] = strtoupper($params->network_sponsor_network_code);
        $arr_data['network_sponsor_leg_position'] = $network_sponsor_leg_position;
        $arr_data['network_upline_member_id'] = $upline_member_id;
        // $arr_data['network_upline_network_code'] = strtoupper($params->network_sponsor_network_code);
        $arr_data['network_upline_network_code'] = $network_upline_network_code;
        $arr_data['network_upline_leg_position'] = $network_upline_leg_position;
        $arr_data['network_activation_datetime'] = $this->datetime;

        // $this->network_service->set_point_a($obj_serial_type->serial_type_point_a);
        $this->network_service->save_network($arr_data);

        $this->db->table("sys_network")->update(["network_slug" => $params->member_network_slug], ["network_member_id" => $member_id]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal ubah slug.", 1);
        }

        $this->db->table("sys_member")->update(["member_is_network" => "1"], ["member_id" => $member_id]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal ubah jaringan.", 1);
        }

        $member_registration = $this->db->table("sys_member_registration")->getWhere(["member_id" => $member_id])->getRow();
        if (!is_null($member_registration) && $member_registration->member_is_network == 0) {
            $this->db->table("sys_member_registration")->update(["member_is_network" => "1"], ["member_id" => $member_id]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal ubah jaringan registrasi.", 1);
            }
        }

        //insert plan activity
        // $arr_data = [];
        // $arr_data['member_plan_activity_member_id'] = $member_id;
        // $arr_data['member_plan_activity_product_package_id'] = $obj_product_package->product_package_id;
        // $arr_data['member_plan_activity_product_package_name'] = $obj_product_package->product_package_name;
        // $arr_data['member_plan_activity_product_package_price'] = $obj_product_package->product_package_price;
        // $arr_data['member_plan_activity_type'] = "activation";
        // $arr_data['member_plan_activity_note'] = "Aktivasi Paket {$obj_product_package->product_package_name} dengan point {$obj_product_package->product_package_point}";
        // $arr_data['member_plan_activity_datetime'] = $this->datetime;
        // $this->serial_service->insert_member_plan_activity($arr_data);

        /* end network */

        /* start serial */
        // $serial_used_note = "Dipakai oleh {$params->network_sponsor_network_code} untuk aktivasi {$network_code} pada " . convertDatetime($this->datetime) . "";
        // $this->serial_service->set_datetime($this->datetime);
        // $this->serial_service->update_serial_used($params->serial_id, $member_id, $serial_used_note);
        /* end serial */

        /* start netgrow */
        // $this->netgrow_service->set_point_a($obj_product_package->serial_type_point_a);
        // $this->netgrow_service->set_product_package_id($obj_product_package->product_package_id);
        // $this->netgrow_service->set_product_package_price($obj_product_package->product_package_price);
        $this->netgrow_service->set_datetime($this->datetime);
        $this->netgrow_service->set_date($this->date);
        $this->netgrow_service->set_transaction_price($member_plan_activity_value);
        $this->netgrow_service->insert_netgrow($member_id);
        /* end netgrow */

        //inisialisasi data bonus
        $this->bonus_service->init_bonus($member_id, $sponsor_member_id);
        $this->bonus_service->init_reward($member_id);
        $this->tax_service->init_tax_member($member_id, date("Y", strtotime($this->datetime)), $params->member_tax_no, $this->datetime);

        if ($this->db->table("sys_member_registration")->getWhere(["member_id" => $member_id])->getRow()) {
            $this->db->table("sys_member_registration")->update([
                "member_registration_status" => "approved",
            ], ["member_id" => $member_id]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal ubah member registration", 1);
            }
        }

        $arr_data = [
            "member_plan_activity_member_id" => $params->parent_member_id,
            "member_plan_activity_value" => $member_plan_activity_value,
            "member_plan_activity_type" => $type,
            "member_plan_activity_note" => ($type == "activation" ? "Aktivasi" : "Kloning") . " dengan kode transaksi : " . $params->transaction_code,
            "member_plan_activity_datetime" => $this->datetime,
        ];
        if ($this->common_model->insertData("sys_member_plan_activity", $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah plan activity data jaringan.", 1);
        }

        return (object)[
            'member_id' => $member_id,
            'network_code' => $params->network_code,
            // 'network_position' => $params->network_position == "L" ? "Kiri" : ($params->network_position == "R" ? "Kanan" : ""),
            'sponsor_username' => $this->common_model->getOne("sys_member_account", "member_account_username", ["member_account_member_id" => $sponsor_member_id]),
            'sponsor_member_name' => $this->common_model->getOne("sys_member", "member_name", ["member_id" => $sponsor_member_id]),
            'upline_username' => $this->common_model->getOne("sys_member_account", "member_account_username", ["member_account_member_id" => $upline_member_id]),
            'upline_member_name' => $this->common_model->getOne("sys_member", "member_name", ["member_id" => $upline_member_id]),
            'member_mobilephone' => $params->member_mobilephone
        ];
    }
}
