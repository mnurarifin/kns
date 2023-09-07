<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Serial extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
        $this->serial_service = service('serial');
    }

    public function generate_all_serial()
    {
        $this->bonus_service = service('Serial');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('generate_all_serial', $this->date)) {
            echo "Error! Cron generate_all_serial periode " . convertDate($this->date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {

            $this->generate_serial();
            // $this->generate_serial_ro();

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('generate_all_serial', json_encode($result), $this->date, $this->datetime, date("Y-m-d H:i:s"));
    }

    private function generate_serial()
    {
        //generate serial untuk masing-masing tipe
        $sql = "SELECT serial_type_id FROM sys_serial_type ORDER BY serial_type_id ASC";
        $query = $this->db->query($sql)->getResult();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $this->serial_service->generate_serial($row->serial_type_id, SERIAL_STOCK_MIN); //params: tipe, jumlah
            }
        }
    }

    private function generate_serial_ro()
    {
        //generate serial ro untuk masing-masing tipe
        $sql = "SELECT serial_ro_type_id FROM sys_serial_ro_type ORDER BY serial_ro_type_id ASC";
        $query = $this->db->query($sql)->getResult();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $this->serial_service->generate_serial_ro($row->serial_ro_type_id, SERIAL_RO_STOCK_MIN); //params: tipe, jumlah
            }
        }
    }

    public function update_all_serial_expired()
    {
        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('update_all_serial_expired', $this->date)) {
            echo "Error! Cron update_all_serial_expired periode " . convertDate($this->date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->update_serial_expired();
            $this->update_serial_ro_expired();

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('update_all_serial_expired', json_encode($result), $this->date, $this->datetime, date("Y-m-d H:i:s"));
    }

    public function update_serial_expired()
    {
        //update is_expired = 1 yang serialnya sudah kadaluarsa pada tabel stok serial member
        $sql = "
            UPDATE sys_serial_member_stock
            SET serial_member_stock_is_expired = 1
            WHERE serial_member_stock_expired_date < '{$this->date}'
            AND serial_member_stock_is_expired = 0
        ";
        $this->db->query($sql);
    }

    public function update_serial_ro_expired()
    {
        //update is_expired = 1 yang serialnya sudah kadaluarsa pada tabel stok serial RO member
        $sql = "
            UPDATE sys_serial_ro_member_stock
            SET serial_ro_member_stock_is_expired = 1
            WHERE serial_ro_member_stock_expired_date < '{$this->date}'
            AND serial_ro_member_stock_is_expired = 0
        ";
        $this->db->query($sql);
    }
}
