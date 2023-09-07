<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Profitsharing extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
        $this->qualified_date = date('Y-m-d', strtotime('first day of previous month', strtotime($this->datetime))); //yyyy-mm-01 bulan kemarin
        $this->qualified_year_month = date('Y-m', strtotime('-1 month')); //yyyy-mm bulan kemarin
    }

    public function calculate_profitsharing()
    {
        $this->profitsharing_service = service('Profitsharing');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_profitsharing', $this->qualified_date)) {
            $qualified_date = date('Y-m-d', strtotime('first day of previous month', strtotime($this->datetime)));
            echo "Error! Cron calculate_profitsharing periode " . convertDate($qualified_date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->profitsharing_service->calculate($this->qualified_year_month, $this->qualified_date);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_profitsharing', json_encode($result), $this->qualified_date, $this->datetime, date("Y-m-d H:i:s"));
    }
}
