<?php

namespace App\Cron;

use App\Controllers\BaseController;
use App\Controllers\Partner\Callback;

class Network extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
        $this->network_service = service('Network');
        $this->activation_service = service('Activation');
        $this->ewallet_service = service('Ewallet');
    }

    public function generate_network_code()
    {
        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('generate_network_code', $this->date)) {
            echo "Error! Cron generate_network_code periode " . convertDate($this->date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->network_service->generate_stock_network_code(NETWORK_CODE_STOCK_MIN);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('generate_network_code', json_encode($result), $this->date, $this->datetime, date("Y-m-d H:i:s"));
    }

    public function calculate_upgrade()
    {
        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if ($this->cron_service->check_log('calculate_upgrade', $this->yesterday)) {
            echo "Error! Cron calculate_upgrade periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            //ambil data upgrade member hari kemarin
            $sql = "
                SELECT member_plan_activity_member_id AS member_id,
                member_plan_activity_serial_type_id AS serial_type_id
                FROM sys_member_plan_activity
                WHERE member_plan_activity_type = 'upgrade'
                AND DATE(member_plan_activity_datetime) = '{$this->yesterday}'
            ";
            $query = $this->db->query($sql)->getResult();
            if (count($query) > 0) {
                foreach ($query as $row) {
                    //upgrade ewallet limit
                    $this->ewallet_service->upgrade_ewallet_limit($row->member_id, $row->serial_type_id); //params: member_id, serial_type_id
                }
            }
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_upgrade', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));
    }

    public function activation()
    {
        $save = [];
        $this->db->transBegin();
        try {
            $types = ["warehouse", "stockist"];
            foreach ($types as $key => $type) {
                $sql = "SELECT * FROM (
                    SELECT
                    TIMEDIFF(NOW(), {$type}_transaction_payment_paid_datetime) AS diff, sys_member_registration.*, inv_{$type}_transaction.*, inv_{$type}_transaction_payment.*
                    FROM sys_member_registration
                    JOIN inv_{$type}_transaction ON {$type}_transaction_id = member_registration_transaction_id
                    JOIN inv_{$type}_transaction_payment ON {$type}_transaction_payment_{$type}_transaction_id = {$type}_transaction_id
                    WHERE member_registration_transaction_type = '{$type}'
                    AND member_registration_status = 'requested'
                    AND {$type}_transaction_payment_status = 'PAID'
                ) t WHERE diff > '00:15:00'
                ";
                $pending = $this->db->query($sql)->getResult();

                foreach ($pending as $key => $value) {
                    $value->parent_member_id = $value->member_id;
                    $value->member_network_slug = $value->member_network_slug;
                    $value->network_code = $value->member_registration_username;
                    $value->transaction_code = $value->{"{$type}_transaction_code"};
                    $transaction_detail = $this->db->table("inv_{$type}_transaction_detail")->getWhere(["{$type}_transaction_detail_{$type}_transaction_id" => $value->{"{$type}_transaction_id"}])->getResult();
                    $hu = 0;
                    $suffix = "";
                    foreach ($transaction_detail as $detail) {
                        for ($i = 0; $i < $detail->{"{$type}_transaction_detail_quantity"}; $i++) {
                            if ($hu > 0) {
                                $suffix = "-" . ($hu + 1);
                                $value->member_registration_sponsor_username = $this->db->table("sys_network")->getWhere(["network_member_id" => $value->member_id])->getRow("network_code");

                                $this->callback = new Callback();
                                $registration = $this->callback->registration($value);
                                $value->member_id = $registration->member_id;
                            }
                            $value->network_code = $value->network_code . $suffix;
                            $save[] = $value->network_code;
                            $activation = $this->activation_service->execute($value, $detail->{"{$type}_transaction_detail_unit_price"}, "activation");
                            $hu += 1;
                        }
                    }
                }
            }

            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . PHP_EOL . "File: {$e->getFile()}" . PHP_EOL . "Line: {$e->getLine()}" . PHP_EOL;
            $this->cron_service->insert_log('activation', json_encode($result), $this->datetime, $this->datetime, date("Y-m-d H:i:s"));
        }
    }
}
