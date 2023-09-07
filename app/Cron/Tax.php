<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Tax extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
        $this->tax_service = service('Tax');
    }

    public function reinit_tax_member()
    {
        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('reinit_tax_member', $this->date)) {
            echo "Error! Cron reinit_tax_member periode " . convertDate($this->date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $members = $this->db->table("sys_member")->get()->getResult();
            foreach ($members as $member) {
                $this->tax_service->init_tax_member($member->member_id, date("Y", strtotime($this->datetime)), $member->member_tax_no, $this->datetime);;
            }
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('reinit_tax_member', json_encode($result), $this->date, $this->datetime, date("Y-m-d H:i:s"));
    }
}
