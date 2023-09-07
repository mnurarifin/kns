<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class BonusModel extends Model
{
	public function get_detail_komisi($network_id, $bonus_config)
	{
		$str_select_acc = $str_select_paid = "";
		if (count($bonus_config) > 0) {
			foreach ($bonus_config as $key => $row) {
				if ($row->active_bonus == TRUE) {
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

	public function getChargeWithdrawal($month = '')
	{
		$sql = "
		SELECT
			SUM(ewallet_withdrawal_adm_charge_value) AS total
		FROM sys_ewallet_withdrawal
		WHERE ewallet_withdrawal_status = 'success' 
		";

		if ($month) {
			$year = date('Y');
			$sql .= "AND MONTH(ewallet_withdrawal_datetime) = '{$month}' AND YEAR(ewallet_withdrawal_datetime) = '{$year}'";
		}

		return $this->db->query($sql)->getRow('total') ?? 0;
	}

	public function getTaxWithdrawal($month = '')
	{
		$sql = "
		SELECT
			SUM(ewallet_withdrawal_tax_value) AS total
		FROM sys_ewallet_withdrawal
		WHERE ewallet_withdrawal_status = 'success' 
		";

		if ($month) {
			$year = date('Y');
			$sql .= "AND MONTH(ewallet_withdrawal_datetime) = '{$month}' AND YEAR(ewallet_withdrawal_datetime) = '{$year}'";
		}

		return $this->db->query($sql)->getRow('total') ?? 0;
	}

	public function getBonus($withdrawal_id)
	{
		$sql = "
		SELECT
			ewallet_withdrawal_member_id,
			ewallet_withdrawal_value,
			ewallet_withdrawal_adm_charge_percent,
			ewallet_withdrawal_adm_charge_value
		FROM sys_ewallet_withdrawal 
		WHERE ewallet_withdrawal_id = '$withdrawal_id';
		";

		return $this->db->query($sql)->getRow();
	}

	public function addBalance($member_id, $value = 0)
	{
		$sql_balance = "
        UPDATE sys_ewallet
        SET ewallet_acc = ewallet_acc + $value
        WHERE ewallet_member_id = '$member_id'
        ";

		return $this->db->query($sql_balance);
	}
}
