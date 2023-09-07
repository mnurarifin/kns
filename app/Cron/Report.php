<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Report extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
    }

    public function calculate_profit_loss()
    {
        $this->report_service = service('Report');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_profit_loss', $this->yesterday)) {
            echo "Error! Cron calculate_profit_loss periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->report_service->calculate_profit_loss($this->yesterday);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_profit_loss', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }
}
