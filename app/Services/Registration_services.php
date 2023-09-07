<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;

class Registration_services extends BaseController
{
    public function __construct()
    {
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

    public function execute($params)
    {
        //ambil data paket
        $sql = "SELECT inv_product_package.*, SUM(product_package_detail_price * product_package_detail_quantity) AS product_package_price
        FROM inv_product_package
        JOIN inv_product_package_detail ON product_package_detail_product_package_id = product_package_id
        WHERE product_package_id = '{$params->product_package_id}'
        GROUP BY product_package_id";
        $obj_product_package = $this->db->query($sql)->getRow();
        if ($obj_product_package == FALSE) {
            throw new \Exception("Gagal mendapatkan data paket.", 1);
        }

        $network_code =  $params->network_code;

        /* start membership */
        //insert member
        if (!isset($params->parent_member_id)) {
            $params->parent_member_id = 0;
        }
        $arr_data = [];
        $arr_data['member']['member_parent_member_id'] = $params->parent_member_id; //jika 1 grup hu maka isikan parent_id
        $arr_data['member']['member_name'] = $params->member_name;
        $arr_data['member']['member_email'] = $params->member_email;
        $arr_data['member']['member_mobilephone'] = phoneNumberFilter($params->member_mobilephone);
        $arr_data['member']['member_gender'] = $params->member_gender;
        $arr_data['member']['member_birth_place'] = $params->member_birth_place;
        $arr_data['member']['member_birth_date'] = $params->member_birth_date;
        $arr_data['member']['member_address'] = $params->member_address;
        $arr_data['member']['member_subdistrict_id'] = $params->member_subdistrict_id;
        $arr_data['member']['member_city_id'] = $params->member_city_id;
        $arr_data['member']['member_province_id'] = $params->member_province_id;
        $arr_data['member']['member_bank_id'] = $params->member_bank_id;
        $arr_data['member']['member_bank_name'] = $this->common_model->GetOne('ref_bank', 'bank_name', ['bank_id' => $params->member_bank_id]);
        $arr_data['member']['member_bank_account_name'] = $params->member_bank_account_name;
        $arr_data['member']['member_bank_account_no'] = $params->member_bank_account_no;
        $arr_data['member']['member_bank_branch'] = property_exists($params, 'member_bank_branch') ? $params->member_bank_branch : '';
        $arr_data['member']['member_identity_type'] = property_exists($params, 'member_identity_type') ? $params->member_identity_type : 'KTP';
        $arr_data['member']['member_identity_no'] = $params->member_identity_no;
        $arr_data['member']['member_identity_image'] = property_exists($params, 'member_identity_image') ? $params->member_identity_image : '';
        $arr_data['member']['member_join_datetime'] = $this->datetime;
        $arr_data['member']['member_is_network'] = '1';


        //insert account
        // jika username menggunakan input dari FE
        $arr_data['account']['member_account_username'] = $params->member_account_username;
        // jika username menggunakan kode mitra
        // $arr_data['account']['member_account_username'] = $network_code;
        $arr_data['account']['member_account_password'] = $params->member_account_password;
        $arr_data['account']['member_account_pin'] = generateNumber(6);

        $member_id = $this->membership_service->save_member($arr_data); //mendapatkan ID member

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

        //inisialisasi data bonus
        $this->bonus_service->init_bonus($member_id);

        //inisialisasi data stok produk member
        // $this->membership_service->init_inv_member_product_stock($member_id);
        /* end membership */

        /* start network */
        $sponsor_member_id = $this->mlm_service->get_member_id_by_network_code($params->network_sponsor_network_code);

        //insert network
        $arr_data = [];
        $arr_data['network_member_id'] = $member_id;
        $arr_data['network_code'] = $network_code;
        $arr_data['network_product_package_id'] = $obj_product_package->product_package_id;
        $arr_data['network_product_package_name'] = $obj_product_package->product_package_name;
        $arr_data['network_sponsor_member_id'] = $sponsor_member_id;
        $arr_data['network_sponsor_network_code'] = strtoupper($params->network_sponsor_network_code);
        $arr_data['network_upline_member_id'] = $sponsor_member_id;
        $arr_data['network_upline_network_code'] = strtoupper($params->network_sponsor_network_code);
        $arr_data['network_activation_datetime'] = $this->datetime;

        // $this->network_service->set_point_a($obj_serial_type->serial_type_point_a);
        $this->network_service->save_network($arr_data);

        //insert plan activity
        $arr_data = [];
        $arr_data['member_plan_activity_member_id'] = $member_id;
        $arr_data['member_plan_activity_product_package_id'] = $obj_product_package->product_package_id;
        $arr_data['member_plan_activity_product_package_name'] = $obj_product_package->product_package_name;
        $arr_data['member_plan_activity_product_package_price'] = $obj_product_package->product_package_price;
        $arr_data['member_plan_activity_type'] = "activation";
        $arr_data['member_plan_activity_note'] = "Aktivasi Paket {$obj_product_package->product_package_name} dengan point {$obj_product_package->product_package_point}";
        $arr_data['member_plan_activity_datetime'] = $this->datetime;
        $this->serial_service->insert_member_plan_activity($arr_data);

        /* end network */

        /* start serial */
        // $serial_used_note = "Dipakai oleh {$params->network_sponsor_network_code} untuk aktivasi {$network_code} pada " . convertDatetime($this->datetime) . "";
        // $this->serial_service->set_datetime($this->datetime);
        // $this->serial_service->update_serial_used($params->serial_id, $member_id, $serial_used_note);
        /* end serial */

        /* start netgrow */
        // $this->netgrow_service->set_point_a($obj_product_package->serial_type_point_a);
        $this->netgrow_service->set_product_package_id($obj_product_package->product_package_id);
        $this->netgrow_service->set_product_package_price($obj_product_package->product_package_price);
        $this->netgrow_service->set_datetime($this->datetime);
        $this->netgrow_service->set_date($this->date);
        $this->netgrow_service->insert_netgrow($member_id);
        /* end netgrow */

        /* start plan b */
        // $this->reward_service->insert_reward($member_id, $obj_serial_type->serial_type_point_b, 1, $this->datetime);
        /* end plan b */
        $project_name = PROJECT_NAME;
        $client_name = COMPANY_NAME;
        $client_wa_cs_number = WA_CS_NUMBER;
        $password = property_exists($params, "member_account_password_raw") ? "Password : {$params->member_account_password_raw}" : "";
        //         $content = "*REGISTRASI BERHASIL*
        // Hai {$params->member_name},
        // Pendaftaran mitra berhasil ditambahkan.

        // Mohon menunggu approval dari admin untuk mendapatkan hak akses sistem.

        // Terima kasih telah menjadi bagian dari Keluarga Besar {$client_name}.

        // Jika Anda punya pertanyaan, silakan hubungi customer service kami di:
        // *wa.me/{$client_wa_cs_number} (WA/Telp)*

        // *-- {$project_name} --*
        //             ";

        // $this->notification_lib->send_waone($content, phoneNumberFilter($params->member_mobilephone));

        return [
            'member_id' => $member_id,
            'member_name' => $params->member_name,
            'phone_number' => phoneNumberFilter($params->member_mobilephone),
            'network_code' => $network_code,
            'member_account_username' => $params->member_account_username,
            'password' => $password,
            'sponsor_member_name' => $this->common_model->GetOne('sys_member', 'member_name', ['member_id' => $sponsor_member_id]),
            'sponsor_username' => $this->common_model->getOne("sys_member_account", "member_account_username", ["member_account_member_id" => $sponsor_member_id]),
        ];
    }

    public function save($params)
    {
        $arr_member_registration = [
            // 'member_id' => $member_id,
            'member_name' => $params->member_name,
            'member_mobilephone' => $params->country_phone_code . str_replace("+62", "", $params->member_mobilephone),
            'member_gender' => $params->member_gender,
            'member_email' => $params->member_email,
            // 'member_birth_place' => $params->member_birth_place,
            // 'member_birth_date' => $params->member_birth_date,
            'member_address' => $params->member_address,
            'member_subdistrict_id' => $params->member_subdistrict_id,
            'member_city_id' => $params->member_city_id,
            'member_province_id' => $params->member_province_id,
            'member_bank_id' => $params->member_bank_id,
            'member_bank_name' => $this->common_model->GetOne('ref_bank', 'bank_name', ['bank_id' => $params->member_bank_id]),
            'member_bank_account_name' => $params->member_bank_account_name,
            'member_bank_account_no' => $params->member_bank_account_no,
            'member_bank_branch' => property_exists($params, 'member_bank_branch') ? $params->member_bank_branch : '',
            'member_identity_type' => property_exists($params, 'member_identity_type') ? $params->member_identity_type : 'KTP',
            'member_identity_no' => $params->member_identity_no,
            'member_identity_image' => property_exists($params, 'member_identity_image') ? $params->member_identity_image : '',
            'member_tax_no' =>  property_exists($params, 'member_tax_no') ? $params->member_tax_no : '',
            'member_registration_username' => $params->network_code,
            'member_registration_password' => $params->member_account_password,
            'member_registration_sponsor_username' => $params->network_sponsor_network_code,
            'member_registration_datetime' => $this->datetime,
            'member_registration_status' => 'requested',
            'member_registration_status_datetime' => $this->datetime,
            'member_registration_transaction_type' => $params->type,
            'member_network_slug' => $params->network_slug,
            'member_job' => $params->member_job,
            'member_is_network' => '0'
        ];

        $this->db->table("sys_member_registration")->insert($arr_member_registration);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal insert member registration", 1);
        }
        $member_registration_id = $this->db->insertID();
        $sponsor_member_id = $this->common_model->GetOne('sys_network', 'network_member_id', ['network_code' => $params->network_sponsor_network_code]);

        return [
            'member_id' => 0,
            'member_name' => $params->member_name,
            'phone_number' => $params->country_phone_code . str_replace("+62", "", $params->member_mobilephone),
            'network_code' => "",
            'member_account_username' => "",
            'password' => $params->member_account_password,
            'sponsor_member_name' => $this->common_model->GetOne('sys_member', 'member_name', ['member_id' => $sponsor_member_id]),
            'sponsor_username' => $params->network_sponsor_network_code,
            'member_registration_id' => $member_registration_id,
        ];
    }

    public function update_transaction_id($transaction_id, $member_registration_id)
    {

        $this->db->table("sys_member_registration")->update(["member_registration_transaction_id" => $transaction_id], ["member_registration_id" => $member_registration_id]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal update transaksi member registration", 1);
        }
    }
}
