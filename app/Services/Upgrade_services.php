<?php
namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;

class Upgrade_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->mlm_service = service('Mlm');
        $this->network_service = service('Network');
        $this->netgrow_service = service('Netgrow');
        $this->serial_service = service('Serial');
        $this->repeatorder_service = service('Repeatorder');
        $this->reward_service = service('Reward');
        $this->report_service = service('Report');

        $this->datetime = date('Y-m-d H:i:s');
        $this->date = date('Y-m-d', strtotime($this->datetime));
        $this->yesterday = date("Y-m-d", strtotime($this->date . "-1 day"));

        $this->notification_lib = new Notification();
    }

    public function set_datetime($value) {
        $this->datetime = $value;
    }

    public function set_date($value) {
        $this->date = $value;
    }

    public function set_yesterday($value) {
        $this->yesterday = $value;
    }

    public function execute($params)
    {
        //ambil data tipe serial lama
        $old_serial_type_name = $this->mlm_service->get_serial_type_name_by_member_id($params->member_id);
        if($old_serial_type_name == '') {
            throw new \Exception("Gagal mendapatkan data tipe serial lama.", 1);
        }

        //ambil data tipe serial baru
        $obj_serial_type = $this->mlm_service->get_serial_type_by_serial_id($params->serial_id);
        if($obj_serial_type == FALSE) {
            throw new \Exception("Gagal mendapatkan data tipe serial baru.", 1);
        }

        //ambil data account username
        $member_account_username = $this->mlm_service->get_member_account_username_by_member_id($params->member_id);
        if(is_null($member_account_username)) {
            throw new \Exception("Gagal mendapatkan username member.", 1);
        }

        //update network
        $arr_data = [];
        $arr_data['network_member_id'] = $params->member_id;
        $arr_data['network_serial_type_id'] = $obj_serial_type->serial_type_id;
        $arr_data['network_serial_type_name'] = $obj_serial_type->serial_type_name;
        $this->network_service->set_point_a($obj_serial_type->serial_type_point_a);
        $this->network_service->upgrade_network($arr_data);

        //insert plan activity
        $arr_data = [];
        $arr_data['member_plan_activity_member_id'] = $params->member_id;
        $arr_data['member_plan_activity_serial_id'] = $params->serial_id;
        $arr_data['member_plan_activity_serial_type_id'] = $obj_serial_type->serial_type_id;
        $arr_data['member_plan_activity_serial_type_name'] = $obj_serial_type->serial_type_name;
        $arr_data['member_plan_activity_serial_type_price'] = $obj_serial_type->serial_type_price;
        $arr_data['member_plan_activity_type'] = "upgrade";
        $arr_data['member_plan_activity_note'] = "Upgrade Paket {$obj_serial_type->serial_type_name} dengan Serial {$params->serial_id}";
        $arr_data['member_plan_activity_datetime'] = $this->datetime;
        $this->serial_service->insert_member_plan_activity($arr_data);

        /* 1 th pertama
        $this->report_service->insert_income_log('upgrade', $obj_serial_type->serial_type_price, $params->member_id, $this->datetime, $obj_serial_type->serial_type_id);
        */

        /* end network */

        /* start serial */
        $serial_used_note = "Dipakai oleh {$member_account_username} untuk upgrade {$obj_serial_type->serial_type_name} pada " . convertDatetime($this->datetime) . "";
        $this->serial_service->set_datetime($this->datetime);
        $this->serial_service->update_serial_used($params->serial_id, $params->member_id, $serial_used_note);
        /* end serial */

        /* start netgrow */
        //jika yang upgrade bukan id root, maka jalankan upgrade netgrow
        if($params->member_id != 1) {
            $this->netgrow_service->set_point_a($obj_serial_type->serial_type_point_a);
            $this->netgrow_service->set_serial_type_id($obj_serial_type->serial_type_id);
            $this->netgrow_service->set_serial_type_price($obj_serial_type->serial_type_price);
            $this->netgrow_service->set_datetime($this->datetime);
            $this->netgrow_service->set_date($this->date);
            $this->netgrow_service->set_match_type(CONFIG_MATCH_TYPE); //dasar penghitungan pasangan (node / point)
            $this->netgrow_service->set_flushout_type(CONFIG_FLUSHOUT_TYPE); //pembatasan maksimal bonus pasangan (pair / nominal)
            $this->netgrow_service->set_is_gen_match(TRUE);
            $this->netgrow_service->upgrade_netgrow($params->member_id);
        }
        /* end netgrow */

        /* start plan b */
        $this->reward_service->insert_reward($params->member_id, $obj_serial_type->serial_type_point_b, 1, $this->datetime);
        /* end plan b */

        if(WA_NOTIFICATION_IS_ACTIVE) {
            $client_name = COMPANY_NAME;
            $client_wa_cs_number = WA_CS_NUMBER;
            $member_name = $this->mlm_service->get_member_name_by_member_id($params->member_id);
            $member_mobilephone = $this->mlm_service->get_member_mobilephone_by_member_id($params->member_id);
            $tomorrow = date("Y-m-d", strtotime($this->datetime . " +1 day"));
            $tomorrow_text = convertDate($tomorrow);
            $content = "*Upgrade Paket Berhasil*
Hai {$member_name},
Upgrade Paket dari {$old_serial_type_name} ke *{$obj_serial_type->serial_type_name}* berhasil diproses.
*Paket upgrade berlaku mulai besok hari, {$tomorrow_text}.*

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}
            ";

            $this->notification_lib->send_waone($content, phoneNumberFilter($member_mobilephone));
        }

        return [
            'member_id' => $params->member_id,
            'member_account_username' => $member_account_username,
        ];
    }
}
