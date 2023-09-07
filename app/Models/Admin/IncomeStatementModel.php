<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class IncomeStatementModel extends Model
{

	public function getData($month, $year)
	{
		$sql_bonus = "SELECT
			bonus_log_date,
			IFNULL(SUM(bonus_log_sponsor),0) as total_sponsor,
			IFNULL(SUM(bonus_log_gen_node),0) as total_gen_node,
			IFNULL(SUM(bonus_log_power_leg),0) as total_power_leg,
			IFNULL(SUM(bonus_log_matching_leg),0)as total_matching_leg,
			IFNULL(SUM(bonus_log_cash_reward),0)as total_cash_reward,
			(
				SELECT 
					COUNT(network_member_id)
				FROM sys_network
				WHERE DATE(network_activation_datetime) = bonus_log_date
				-- AND network_type_id = 1
			) AS total_reg
		FROM sys_bonus_log
		WHERE MONTH(bonus_log_date) = '{$month}'
		AND YEAR(bonus_log_date) = '{$year}'
		GROUP BY bonus_log_date
		";

		$data_bonus = $this->db->query($sql_bonus)->getResult();

		foreach ($data_bonus as $key => $value) {
			$total_trx = $this->db->query("SELECT member_join_date, SUM(transaction_total_price) AS transaction_total_price FROM (
				SELECT member_join_date, SUM(transaction_total_price) AS transaction_total_price FROM (
					SELECT warehouse_transaction_total_price AS transaction_total_price, DATE(member_join_datetime) AS member_join_date
					FROM inv_warehouse_transaction
					JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
					JOIN sys_member ON member_join_datetime = warehouse_transaction_payment_paid_datetime
					WHERE DATE(member_join_datetime) = '{$value->bonus_log_date}'
					AND warehouse_transaction_status IN ('paid', 'complete', 'delivered', 'approved')
					AND warehouse_transaction_buyer_type = 'member'
					GROUP BY warehouse_transaction_id
				) t1
				UNION
				SELECT member_join_date, SUM(transaction_total_price) AS transaction_total_price FROM (
					SELECT stockist_transaction_total_price AS transaction_total_price, DATE(member_join_datetime) AS member_join_date
					FROM inv_stockist_transaction
					JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
					JOIN sys_member ON member_join_datetime = stockist_transaction_payment_paid_datetime
					WHERE DATE(member_join_datetime) = '{$value->bonus_log_date}'
					AND stockist_transaction_status IN ('paid', 'complete', 'delivered', 'approved')
					AND stockist_transaction_buyer_type = 'member'
					GROUP BY stockist_transaction_id
				) t2
			) t3;")->getRow("transaction_total_price") ?? 0;
			$total_company = $total_trx * 41 / 100;
			$total_payout = $total_trx * 59 / 100;
			$total_sponsor = $total_trx * 12.5 / 100;
			$total_gen_node = $total_trx * 16.5 / 100;
			$total_power_leg = $total_trx * 10 / 100;
			$total_matching_leg = $total_trx * 5 / 100;
			$total_cash_reward = $total_trx * 15 / 100;

			$data_bonus[$key]->total_trx = $total_trx;
			$data_bonus[$key]->trx = $total_trx;
			$data_bonus[$key]->company = $total_company;
			$data_bonus[$key]->payout = $total_payout;
			$data_bonus[$key]->diff_sponsor = $total_sponsor - $value->total_sponsor;
			$data_bonus[$key]->diff_gen_node = $total_gen_node - $value->total_gen_node;
			$data_bonus[$key]->diff_power_leg = $total_power_leg - $value->total_power_leg;
			$data_bonus[$key]->diff_matching_leg = $total_matching_leg - $value->total_matching_leg;
			$data_bonus[$key]->diff_cash_reward = $total_cash_reward - $value->total_cash_reward;
			$data_bonus[$key]->total_diff = $data_bonus[$key]->diff_sponsor + $data_bonus[$key]->diff_gen_node + $data_bonus[$key]->diff_power_leg + $data_bonus[$key]->diff_matching_leg + $data_bonus[$key]->diff_cash_reward;
		}

		return $data_bonus;
	}

	public function getYear()
	{
		$sql_years = "
		SELECT
			YEAR(bonus_log_date) AS val,
			YEAR(bonus_log_date) AS name
		FROM sys_bonus_log
		GROUP BY YEAR(bonus_log_date)
		";

		$years = $this->db->query($sql_years)->getResult();

		return $years;
	}

	public function getDataBonus($tanggal)
	{
		$sql = "SELECT
			SUM(bonus_log_sponsor) AS total_sponsor,
			SUM(bonus_log_gen_node) AS total_gen_node,
			SUM(bonus_log_power_leg) AS total_power_leg,
			SUM(bonus_log_matching_leg) AS total_matching_leg,
			SUM(bonus_log_cash_reward) AS total_cash_reward,
			FROM sys_bonus_log
			WHERE bonus_log_date = '{$tanggal}'
		";
		return $this->db->query($sql)->getRow();
	}

	public function get_data_new_member($month, $year)
	{
		$arr_data = [];
		$sql = "
			SELECT COUNT(network_id) AS total,
			serial_type_price AS serial_price,
			date(network_activation_datetime) AS tanggal
			FROM sys_network
			JOIN sys_serial_type ON network_type_id = serial_type_id
			WHERE MONTH(network_activation_datetime) = {$month} AND YEAR(network_activation_datetime) = {$year}
			GROUP BY network_type_id, date(network_activation_datetime)
		";
		$data = $this->db->query($sql)->getResult();
		if (!empty($data)) {
			foreach ($data as $key => $row) {
				$arr_data[] = array(
					'tanggal' => $row->tanggal,
					'member' => (int)($row->total * $row->serial_price),
				);
			}
		}
		return $arr_data;
	}

	public function get_data_ro($month, $year)
	{
		$arr_data = [];
		$sql = "
			SELECT COUNT(sales_personal_id) AS total,
			sales_personal_date AS tanggal
			FROM sys_sales_personal
			WHERE sales_personal_month = {$month} AND sales_personal_year = {$year}
			GROUP BY sales_personal_date
		";
		$data = $this->db->query($sql)->getResult();
		if (!empty($data)) {
			foreach ($data as $key => $row) {
				$arr_data[] = array(
					'tanggal' => $row->tanggal,
					'ro' => (int)($row->total * 650000),
				);
			}
		}
		return $arr_data;
	}

	public function get_data_upgrade($month, $year)
	{
		$arr_data = [];
		$sql = "
			SELECT COUNT(upgrade_log_network_id) AS total,
			upgrade_log_date AS tanggal,
			serial_serial_type_id AS type_id,
			serial_type_price
			FROM sys_upgrade_log
			JOIN sys_serial ON upgrade_log_serial_id = serial_id
			JOIN sys_serial_type ON serial_type_id = serial_serial_type_id
			WHERE MONTH(upgrade_log_date) = {$month} AND YEAR(upgrade_log_date) = {$year}
			GROUP BY upgrade_log_date, type_id
		";
		$data = $this->db->query($sql)->getResult();
		if (!empty($data)) {
			foreach ($data as $key => $row) {
				$arr_data[] = array(
					'tanggal' => $row->tanggal,
					'upgrade' => (int)($row->total * $row->serial_type_price),
				);
			}
		}
		return $arr_data;
	}

	function get_selling_serial($month, $year)
	{
		$sql = "SELECT
			DATE(serial_buy_datetime) AS tanggal,
			SUM(t.total * t.price_per_piece) AS omset_laba_kotor 
			FROM(
				SELECT 
					COUNT(*) AS total,
					CASE
						WHEN COUNT(*) >= 200 THEN '280000'
						WHEN COUNT(*) >= 30 THEN '290000'
						ELSE '300000' 
					END AS price_per_piece,
					serial_buy_datetime,
					serial_buyer_member_id
				FROM sys_serial 
				WHERE MONTH(serial_buy_datetime) = '{$month}'
				AND YEAR(serial_buy_datetime) = '{$year}'
				AND serial_is_sold = 1
				AND serial_buyer_member_id > 0
				AND serial_serial_type_id != 2
			GROUP BY 
			serial_buy_datetime,
			serial_buyer_member_id
			) AS t
        ";

		return $this->db->query($sql)->getRow('omset_laba_kotor');
	}
}
