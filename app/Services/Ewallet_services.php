<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Ewallet_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function set_datetime($value)
    {
        $this->datetime = $value;
    }

    public function init_ewallet($member_id)
    {
        if ($this->common_model->insertData('sys_ewallet', ['ewallet_member_id' => $member_id]) == FALSE) {
            throw new \Exception("Gagal menambah data ewallet.", 1);
        }
    }

    public function add_ewallet($member_id, $value)
    {
        $this->db->table("sys_ewallet")->set("ewallet_acc", "ewallet_acc+" . $value, FALSE)->where(["ewallet_member_id" => $member_id])->update();
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data ewallet.", 1);
        }
    }

    public function add_ewallet_log($member_id, $value, $type, $note, $datetime)
    {
        $this->db->table("sys_ewallet_log")->insert([
            "ewallet_log_member_id" => $member_id,
            "ewallet_log_value" => $value,
            "ewallet_log_type" => $type,
            "ewallet_log_note" => $note,
            "ewallet_log_datetime" => $datetime,
        ]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah riwayat ewallet.", 1);
        }
    }

    public function init_ewallet_limit($member_id, $serial_type_id)
    {
        $obj_serial_type = $this->common_model->getDetail('sys_serial_type', 'serial_type_id', $serial_type_id);
        if ($obj_serial_type == FALSE) {
            throw new \Exception("Gagal mendapatkan data tipe serial.", 1);
        }

        $arr_data = [];
        $arr_data['ewallet_withdrawal_limit_member_id'] = $member_id;
        $arr_data['ewallet_withdrawal_limit_ro_value'] = $obj_serial_type->serial_type_withdrawal_ro_value;
        $arr_data['ewallet_withdrawal_limit_quota_value'] = $obj_serial_type->serial_type_withdrawal_limit_value;
        $arr_data['ewallet_withdrawal_limit_withdrawal_value'] = $obj_serial_type->serial_type_withdrawal_limit_value;
        $arr_data['ewallet_withdrawal_limit_last_update_datetime'] = $this->datetime;
        if ($this->common_model->insertData('sys_ewallet_withdrawal_limit', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data limit ewallet.", 1);
        }
    }

    public function update_ewallet_limit($member_id, $serial_ro_id)
    {
        $ro_value = $this->db->table('sys_serial_ro')->select('serial_ro_type_price')->join('sys_serial_ro_type', 'serial_ro_type_id = serial_ro_serial_ro_type_id')->getWhere(['serial_ro_id' => $serial_ro_id])->getRow('serial_ro_type_price');
        if (is_null($ro_value)) {
            throw new \Exception("Gagal mendapatkan data nilai RO.", 1);
        }

        $obj_ewallet_withdrawal_limit = $this->common_model->getDetail('sys_ewallet_withdrawal_limit', 'ewallet_withdrawal_limit_member_id', $member_id);
        if ($obj_ewallet_withdrawal_limit == FALSE) {
            throw new \Exception("Gagal mendapatkan data limit ewallet.", 1);
        }

        $limit_ro_value = $obj_ewallet_withdrawal_limit->ewallet_withdrawal_limit_ro_value;
        $limit_ro_acc_value = $obj_ewallet_withdrawal_limit->ewallet_withdrawal_limit_ro_acc_value;
        $limit_quota_add = $obj_ewallet_withdrawal_limit->ewallet_withdrawal_limit_quota_value;
        $new_ro_acc_value = $limit_ro_acc_value + $ro_value;

        //jika penambahan nilai mencukupi untuk minimal RO, maka tambahkan quota limit
        if (($new_ro_acc_value) >= $limit_ro_value) {
            $limit_ro_acc_value_balance = $new_ro_acc_value - $limit_ro_value;
            $sql = "
                UPDATE sys_ewallet_withdrawal_limit
                SET ewallet_withdrawal_limit_ro_acc_value = {$limit_ro_acc_value_balance},
                ewallet_withdrawal_limit_withdrawal_value = ewallet_withdrawal_limit_withdrawal_value + {$limit_quota_add},
                ewallet_withdrawal_limit_last_update_datetime = '{$this->datetime}'
                WHERE ewallet_withdrawal_limit_member_id = {$member_id}
            ";
            $this->db->query($sql);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah data limit ewallet.", 1);
            }
        } else {
            $sql = "
                UPDATE sys_ewallet_withdrawal_limit
                SET ewallet_withdrawal_limit_ro_acc_value = ewallet_withdrawal_limit_ro_acc_value + {$ro_value},
                ewallet_withdrawal_limit_last_update_datetime = '{$this->datetime}'
                WHERE ewallet_withdrawal_limit_member_id = {$member_id}
            ";
            $this->db->query($sql);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah data limit ewallet.", 1);
            }
        }
    }

    public function upgrade_ewallet_limit($member_id, $serial_type_id)
    {
        $obj_serial_type = $this->common_model->getDetail('sys_serial_type', 'serial_type_id', $serial_type_id);
        if ($obj_serial_type == FALSE) {
            throw new \Exception("Gagal mendapatkan data tipe serial.", 1);
        }

        $sql = "
            UPDATE sys_ewallet_withdrawal_limit
            SET ewallet_withdrawal_limit_ro_value = {$obj_serial_type->serial_type_withdrawal_ro_value},
            ewallet_withdrawal_limit_quota_value = {$obj_serial_type->serial_type_withdrawal_limit_value},
            ewallet_withdrawal_limit_withdrawal_value = ewallet_withdrawal_limit_withdrawal_value + {$obj_serial_type->serial_type_withdrawal_limit_value},
            ewallet_withdrawal_limit_last_update_datetime = '{$this->datetime}'
            WHERE ewallet_withdrawal_limit_member_id = {$member_id}
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah data limit ewallet.", 1);
        }
    }
}
