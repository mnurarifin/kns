<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class KomisiModel extends Model
{
    public function getBonusTransfer($arr_bonus_network_id = [], $transfer_condition)
    {
        $where_network_id = count($arr_bonus_network_id) > 0 ? 'bonus_network_id in (' . implode(', ', $arr_bonus_network_id) . ')AND ' : '';
        $sql = 'SELECT * FROM (
            SELECT
            bonus_total_acc - bonus_total_paid AS saldo,
            bonus_sponsor_acc - bonus_sponsor_paid AS sponsor_saldo,
            bonus_match_acc - bonus_match_paid AS match_saldo,
            bonus_cashback_acc - bonus_cashback_paid AS cashback_saldo,
            bonus_unilevel_acc - bonus_unilevel_paid AS unilevel_saldo,
            member_id,
            bonus_network_id,
            member_ref_network_code as network_code,
            member_name,
            member_bank_id,
            member_bank_name,
            member_bank_city,
            member_bank_branch,
            member_bank_account_name,
            member_bank_account_no,
            member_mobilephone,
            network_is_active,
            network_is_suspended
            FROM bin_bonus
            JOIN bin_member_ref ON bin_member_ref.member_ref_network_id = bin_bonus.bonus_network_id
            JOIN sys_member ON sys_member.member_id = bin_member_ref.member_ref_member_id
            JOIN bin_network ON bin_member_ref.member_ref_network_id = bin_network.network_id
            ) t WHERE ' . $where_network_id . $transfer_condition;

        return $this->db->query($sql)->getResult('array');
    }

    public function processTransfer($bonus_transfer, $datetime, $administrator_id, $administrator_name)
    {
        // UPDATE BIN_BONUS

        $transfer_code = $this->getTransferCode();
        $arr_insert_bin_bonus_transfer = [
            'bonus_transfer_network_id' => $bonus_transfer['bonus_network_id'],
            'bonus_transfer_network_code' => $bonus_transfer['network_code'],
            'bonus_transfer_member_name' => $bonus_transfer['member_name'],
            'bonus_transfer_category' => 'daily',
            'bonus_transfer_code' => $transfer_code,
            'bonus_transfer_total_bonus' => $bonus_transfer['saldo'],
            // 'bonus_transfer_adm_charge_percent' => $bonus_transfer['transfer_adm_charge_percent'],
            // 'bonus_transfer_adm_charge_value' => $bonus_transfer['transfer_adm_charge_value'],
            'bonus_transfer_nett' => $bonus_transfer['saldo'],
            'bonus_transfer_bank_id' => $bonus_transfer['member_bank_id'],
            'bonus_transfer_bank_name' => $bonus_transfer['member_bank_name'],
            'bonus_transfer_bank_city' => $bonus_transfer['member_bank_city'] ? $bonus_transfer['member_bank_city'] : '',
            'bonus_transfer_bank_branch' => $bonus_transfer['member_bank_branch'] ? $bonus_transfer['member_bank_branch'] : '',
            'bonus_transfer_bank_account_name' => $bonus_transfer['member_bank_account_name'],
            'bonus_transfer_bank_account_no' => $bonus_transfer['member_bank_account_no'],
            'bonus_transfer_mobilephone' => $bonus_transfer['member_mobilephone'],
            'bonus_transfer_note' => 'Transfer Bonus Harian',
            'bonus_transfer_datetime' => $datetime,
            'bonus_transfer_administrator_id' => $administrator_id,
            'bonus_transfer_administrator_name' => $administrator_name,
            'bonus_transfer_datetime' => $datetime,
            'bonus_transfer_sponsor' => $bonus_transfer['sponsor_saldo'],
            'bonus_transfer_match' => $bonus_transfer['match_saldo'],
            'bonus_transfer_bonus_cashback' => $bonus_transfer['cashback_saldo'],
            'bonus_transfer_bonus_unilevel' => $bonus_transfer['unilevel_saldo'],
        ];

        $this->db->table('bin_bonus_transfer')->insert($arr_insert_bin_bonus_transfer);

        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal menambah data  bin bonus transfer", 1);
        }

        // $arr_insert_bonus_log = [
        //     'bonus_log_network_id' => $bonus_transfer['bonus_network_id'],
        //     'bonus_log_value' => $bonus_transfer['saldo'],
        //     'bonus_log_type' => 'out',
        //     'bonus_log_date' => $datetime,
        //     'bonus_log_note' => 'Transfer Bonus Harian dengan kode ' . $transfer_code,
        // ];
        // $this->db->table('bin_bonus_log')->insert($arr_insert_bonus_log);

        // if ($this->db->affectedRows() < 0) {
        //     throw new \Exception("Gagal menambah data riwayat bonus", 1);
        // }

        $this->db->table('bin_bonus')
            ->set('bonus_sponsor_paid', 'bonus_sponsor_paid + ' . $bonus_transfer['sponsor_saldo'], false)
            ->set('bonus_match_paid', 'bonus_match_paid + ' . $bonus_transfer['match_saldo'], false)
            ->set('bonus_cashback_paid', 'bonus_cashback_paid + ' . $bonus_transfer['cashback_saldo'], false)
            ->set('bonus_unilevel_paid', 'bonus_unilevel_paid + ' . $bonus_transfer['unilevel_saldo'], false)
            ->set('bonus_total_paid', 'bonus_total_paid + ' . $bonus_transfer['saldo'], false)
            ->where(['bonus_network_id' => $bonus_transfer['bonus_network_id']])
            ->update();

        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal menambah data bin bonus", 1);
        }

        // UPDATE SYS_BONUS

        $arr_insert_sys_bonus_transfer = [
            'bonus_transfer_member_id' => $bonus_transfer['member_id'],
            'bonus_transfer_member_name' => $bonus_transfer['member_name'],
            'bonus_transfer_category' => 'daily',
            'bonus_transfer_code' => $transfer_code,
            'bonus_transfer_bonus' => $bonus_transfer['saldo'],
            // 'bonus_transfer_adm_charge_percent' => $bonus_transfer['transfer_adm_charge_percent'],
            // 'bonus_transfer_adm_charge_value' => $bonus_transfer['transfer_adm_charge_value'],
            'bonus_transfer_total' => $bonus_transfer['saldo'],
            'bonus_transfer_bank_id' => $bonus_transfer['member_bank_id'],
            'bonus_transfer_bank_name' => $bonus_transfer['member_bank_name'],
            'bonus_transfer_bank_city' => $bonus_transfer['member_bank_city'] ? $bonus_transfer['member_bank_city'] : '',
            'bonus_transfer_bank_branch' => $bonus_transfer['member_bank_branch'] ? $bonus_transfer['member_bank_branch'] : '',
            'bonus_transfer_bank_account_name' => $bonus_transfer['member_bank_account_name'],
            'bonus_transfer_bank_account_no' => $bonus_transfer['member_bank_account_no'],
            'bonus_transfer_mobilephone' => $bonus_transfer['member_mobilephone'],
            'bonus_transfer_status' => 'success',
            'bonus_transfer_note' => 'Transfer Bonus Harian',
            'bonus_transfer_datetime' => $datetime,
            'bonus_transfer_status_update_administrator_id' => $administrator_id,
            'bonus_transfer_status_update_administrator_name' => $administrator_name,
            'bonus_transfer_status_update_datetime' => $datetime,
        ];
        $this->db->table('sys_bonus_transfer')->insert($arr_insert_sys_bonus_transfer);

        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal menambah data sys bonus transfer", 1);
        }

        $arr_insert_sys_bonus_log = [
            'bonus_log_member_id' => $bonus_transfer['member_id'],
            'bonus_log_value' => $bonus_transfer['saldo'],
            'bonus_log_type' => 'out',
            'bonus_log_date' => $datetime,
            'bonus_log_note' => 'Transfer Bonus Harian dengan kode ' . $transfer_code,
        ];
        $this->db->table('sys_bonus_log')->insert($arr_insert_sys_bonus_log);

        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal menambah data riwayat bonus", 1);
        }

        $this->db->table('sys_bonus')
            ->set('bonus_paid', 'bonus_paid + ' . $bonus_transfer['saldo'], false)
            ->where([
                'bonus_member_id' => $bonus_transfer['member_id'],
            ])->update();

        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal menambah data sys bonus", 1);
        }

        return true;
    }

    public function processBonusTransfer($params, $datetime, $administrator_id, $administrator_name)
    {
        $this->db->table("sys_bonus_transfer")->insert($params["insert"]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah data bonus transfer.", 1);
        }

        $bonus_transfer_id = $this->db->insertID();

        $update = $this->db->table("sys_bonus");
        foreach ($params["update"] as $field => $val) {
            $update->set($field, $field . "+" . $val, FALSE);
        }
        $update->where($params["where"])->update();
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data bonus.", 1);
        }

        return [
            'id' => $bonus_transfer_id,
            'status' => true
        ];
    }

    public function getTransferCode()
    {
        $prefix = 'TRF';

        $sql = "
        SELECT
        MAX(bonus_transfer_code) as max_code
        FROM bin_bonus_transfer
        WHERE bonus_transfer_code LIKE '%{$prefix}%'
        ";
        $max_code = $this->db->query($sql)->getRow('max_code');

        if ($max_code) {
            $max_int = (int) substr($max_code, 5, 5) + 1;
            $code = $prefix . sprintf("%06s", $max_int);
        } else {
            $code = $prefix . sprintf('%06s', 1);
        }

        return $code;
    }

    public function getDetailKomisi($network_id, $bonus_config)
    {
        $str_select_acc = $str_select_paid = "";
        if (count($bonus_config) > 0) {
            foreach ($bonus_config as $key => $row) {
                if ($row->is_active == true) {
                    $str_select_acc .= "bonus_" . $row->name . "_acc, ";
                    $str_select_paid .= "bonus_" . $row->name . "_paid, ";
                }
            }
        }
        $sql = "
			SELECT $str_select_acc $str_select_paid bonus_network_id
			FROM bin_bonus
			WHERE bonus_network_id = '$network_id'
		";
        return $this->db->query($sql)->getRowArray();
    }
}
