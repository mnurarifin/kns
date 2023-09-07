<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Royalty extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
    }

    public function calculate_royalty_leadership()
    {
        $this->royalty_service = service('Royalty');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_royalty_leadership', $this->yesterday)) {
            echo "Error! Cron calculate_royalty_leadership periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->royalty_service->calculate_royalty_leadership($this->yesterday);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_royalty_leadership', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }

    public function calculate_royalty_qualified()
    {
        $this->royalty_service = service('Royalty');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_royalty_qualified', $this->yesterday)) {
            echo "Error! Cron calculate_royalty_qualified periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->royalty_service->calculate_royalty_qualified($this->yesterday);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_royalty_qualified', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }
}
