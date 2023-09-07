<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Models\Common_model;
use App\Libraries\Notification;
use PhpParser\Node\Expr\Print_;

class Otp_model extends Model
{
    protected $db;
    protected $common_model;
    protected $notification_lib;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->notification_lib = new Notification();
    }

    public function generateOTP($id)
    {
        $otp = generateNumber(6);
        $expired = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +1 hour"));
        $this->db->table("sys_member_otp")->insert([
            "member_otp_member_id" => $id,
            "member_otp_value" => $otp,
            "member_otp_expired_datetime" => $expired,
        ]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal generate OTP.", 1);
        }

        if (WA_NOTIFICATION_IS_ACTIVE) {
            $this->common_model = new Common_model();
            $member_mobilephone = $this->common_model->getOne('sys_member', 'member_mobilephone', ['member_id' => $id]);

            $client_name = COMPANY_NAME;
            $client_url = URL_PRODUCTION;
            $content = "*Kode OTP*
*{$otp}*

PENTING ! *[!]*
*Segala bentuk informasi berkaitan dengan Password dan informasi detail lainnya Adalah RAHASIA. Mohon untuk tidak memberitahukannya kepada orang lain atau pihak yang mengatasnamakan {$client_name}.* 

Terima kasih atas kepercayaan anda bersama kami.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";
            $this->notification_lib->send_waone($content, phoneNumberFilter($member_mobilephone));
        }
    }

    public function verify($id, $otp)
    {
        $this->db->table("sys_member_otp")->getWhere(["member_otp_member_id" => $id, "member_otp_value" => $otp])->getRow();

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("OTP tidak ditemukan.", 1);
        }
    }
}
