<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Common extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
    }

    public function delete_member_otp_expired()
    {
        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('delete_member_otp_expired', $this->yesterday)) {
            echo "Error! Cron delete_member_otp_expired periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $sql = "DELETE FROM sys_member_otp WHERE DATE(member_otp_expired_datetime) < '{$this->date}'";
            $this->db->query($sql);

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('delete_member_otp_expired', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }
}
