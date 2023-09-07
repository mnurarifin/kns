<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Bonus extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
    }

    public function calculate_bonus_daily()
    {
        $this->bonus_service = service('Bonus');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_bonus_daily', $this->yesterday)) {
            echo "Error! Cron calculate_bonus_daily periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->bonus_service->calculateDaily($this->yesterday, $this->datetime); //params: bonus_date, run_datetime
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_bonus_daily', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }

    public function calculate_bonus_monthly()
    {
        $this->bonus_service = service('Bonus');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_bonus_monthly', $this->yesterday)) {
            echo "Error! Cron calculate_bonus_monthly periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->bonus_service->calculateMonthly($this->yesterday, $this->datetime); //params: bonus_date, run_datetime
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_bonus_monthly', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }
}
