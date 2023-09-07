<?php

namespace App\Validations;

use App\Models\Common_model;

class Validations
{
	protected $db;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->common_model = new Common_model();
	}

	public function check_json($string)
	{
		if (json_decode($string) && !empty((array)json_decode($string))) {
			return TRUE;
		}
		return FALSE;
	}

	public function check_slug($slug, $params = null)
	{
		if ($slug == '') {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_warehouse_transaction ON warehouse_transaction_id = member_registration_transaction_id
			JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
			WHERE member_network_slug = '{$slug}'
			AND member_registration_transaction_type = 'warehouse'
			AND warehouse_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_stockist_transaction ON stockist_transaction_id = member_registration_transaction_id
			JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
			WHERE member_network_slug = '{$slug}'
			AND member_registration_transaction_type = 'stockist'
			AND stockist_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		return TRUE;
	}

	public function check_bank_account_no($bank_account_no, $params = null)
	{
		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_warehouse_transaction ON warehouse_transaction_id = member_registration_transaction_id
			JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
			WHERE member_bank_account_no = '{$bank_account_no}'
			AND member_bank_account_no != ''
			AND member_registration_transaction_type = 'warehouse'
			AND warehouse_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_stockist_transaction ON stockist_transaction_id = member_registration_transaction_id
			JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
			WHERE member_bank_account_no = '{$bank_account_no}'
			AND member_bank_account_no != ''
			AND member_registration_transaction_type = 'stockist'
			AND stockist_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		return TRUE;
	}

	public function check_email($email, $params = null)
	{
		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_warehouse_transaction ON warehouse_transaction_id = member_registration_transaction_id
			JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
			WHERE member_email = '{$email}'
			AND member_registration_transaction_type = 'warehouse'
			AND warehouse_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_stockist_transaction ON stockist_transaction_id = member_registration_transaction_id
			JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
			WHERE member_email = '{$email}'
			AND member_registration_transaction_type = 'stockist'
			AND stockist_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		return TRUE;
	}

	public function is_unique_empty($string, $params)
	{
		if ($string == "") {
			return TRUE;
		} else {
			$tableField = explode(",", $params)[0];
			$notField = FALSE;
			$notValue = FALSE;
			if (isset(explode(",", $params)[1])) {
				$notField = explode(",", $params)[1];
			}
			if (isset(explode(",", $params)[2])) {
				$notValue = explode(",", $params)[2];
			}
			$table = explode(".", $tableField)[0];
			$field = explode(".", $tableField)[1];
			$where = [$field => $string];
			if ($notField && $notValue) {
				$where = array_merge($where, ["{$notField} !=" => $notValue]);
			}
			if ($this->db->table($table)->getWhere($where)->getRow()) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function check_empty_numeric($string)
	{
		if ($string == "") {
			return TRUE;
		} else if (preg_match('/^[0-9]*$/', $string)) {
			return TRUE;
		}
		return FALSE;
	}

	//cek kaki terluar
	public function check_outer($sponsor_username, $params)
	{
		$sponsor_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $sponsor_username]);
		$params = explode(',', $params);
		if ($params[2] == 1) {
			return TRUE;
		}
		$upline_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $params[0]]);
		$position = $params[1] == 'L' ? 'network_outer_node_left_member_id' : ($params[1] == 'R' ? 'network_outer_node_right_member_id' : FALSE);
		if (!$position) {
			return FALSE;
		}
		$sql = "SELECT * FROM sys_network_outer_node WHERE network_outer_node_member_id = '{$sponsor_member_id}' AND {$position} = '{$upline_member_id}'";
		$result = $this->db->query($sql)->getRow();
		if (!empty($result)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//cek kode otp yang terakhir
	public function check_otp($otp, $member_id)
	{
		$now = date("Y-m-d H:i:s");
		$sql = "SELECT 1 FROM sys_member_otp WHERE member_otp_member_id = '{$member_id}' AND member_otp_value = '{$otp}' AND member_otp_expired_datetime > '{$now}' ORDER BY member_otp_id DESC";
		$result = $this->db->query($sql)->getRow();
		if (!empty($result)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//cek apakah kode network eksis
	public function check_network_code($network_code)
	{
		$sql = "SELECT 1 FROM sys_network WHERE network_code = '{$network_code}' AND network_is_active = 1 AND network_is_suspended = 0 LIMIT 1";
		$result = $this->db->query($sql)->getRow();
		if (!empty($result)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//cek apakah upline dan sponsor crossline atau sejalur
	public function check_crossline($upline_username, $sponsor_username)
	{
		$upline_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $upline_username]);
		$sponsor_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $sponsor_username]);

		if (is_null($upline_member_id) || is_null($sponsor_member_id)) {
			return FALSE;
		}

		if ($sponsor_member_id == $upline_member_id) {
			return TRUE;
		} else {
			$arr_network_upline = $this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $upline_member_id]);
			if ($arr_network_upline != '') {
				$arr_network_upline = json_decode($arr_network_upline);
				foreach ($arr_network_upline as $value) {
					if ($value->id == $sponsor_member_id) {
						return TRUE;
					}
				}
			}
		}

		return FALSE;
	}

	//cek apakah upline dan sponsor crossline atau sejalur
	public function check_crossline_unilevel($upline_username, $sponsor_username)
	{
		$upline_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $upline_username]);
		$sponsor_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $sponsor_username]);

		if (is_null($upline_member_id) || is_null($sponsor_member_id)) {
			return FALSE;
		}

		if ($sponsor_member_id == $upline_member_id) {
			return TRUE;
		} else {
			$arr_network_upline = $this->common_model->getOne('sys_network_sponsor', 'network_sponsor_arr_data', ['network_sponsor_member_id' => $upline_member_id]);
			if ($arr_network_upline != '') {
				$arr_network_upline = json_decode($arr_network_upline);
				foreach ($arr_network_upline as $value) {
					if ($value->id == $sponsor_member_id) {
						return TRUE;
					}
				}
			}
		}

		return FALSE;
	}

	//cek apakah upline dan sponsor crossline atau sejalur
	public function check_crossline_genealogy_unilevel($upline_username, $network_code)
	{
		$upline_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $upline_username]); ///07
		$top_member_id = $this->common_model->getOne('sys_member_account', 'member_account_member_id', ['member_account_username' => $network_code]); //02

		if (is_null($upline_member_id) || is_null($top_member_id)) {
			return FALSE;
		}

		if ($top_member_id == $upline_member_id) {
			return TRUE;
		} else {
			$arr_network_upline = $this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $upline_member_id]);
			if ($arr_network_upline != '') {
				$arr_network_upline = json_decode($arr_network_upline);
				foreach ($arr_network_upline as $value) {
					if ($value->id == $top_member_id) {
						return TRUE;
					}
				}
			}
		}

		return FALSE;
	}

	//cek apakah titik kiri/kanan dari upline masih tersedia (kosong)
	public function check_upline($position, $upline_network_code)
	{
		$position = ($position == 'L') ? 'left' : 'right';
		$sql = "
			SELECT network_{$position}_node_member_id
			FROM sys_network
			WHERE network_code = '{$upline_network_code}'
			LIMIT 1
		";
		$downline_node_member_id = $this->db->query($sql)->getRow("network_{$position}_node_member_id");
		if ($downline_node_member_id == '0') {
			return TRUE;
		}

		return FALSE;
	}

	//cek apakah serial tipe ada
	public function check_serial_type_id($serial_type_id, $params = null)
	{
		$arr_params = explode(',', $params);
		$serial_type = (isset($arr_params[0])) ? $arr_params[0] : '';
		if ($serial_type_id == '' || $serial_type == '') {
			return FALSE;
		}
		if ($serial_type == 'activation') {
			$sql = "
				SELECT *
				FROM sys_serial_type
				WHERE serial_type_id = '{$serial_type_id}'
			";
		} else if ($serial_type == 'repeatorder') {
			$sql = "
				SELECT *
				FROM sys_serial_ro_type
				WHERE serial_ro_type_id = '{$serial_type_id}'
			";
		} else {
			return FALSE;
		}
		$serial = $this->db->query($sql)->getRow();
		if (!is_null($serial)) {
			return TRUE;
		}
		return FALSE;
	}

	//cek apakah serial tipe ada
	public function check_serial_type_stock($quantity, $params = null)
	{
		$arr_params = explode(',', $params);
		$member_id = (isset($arr_params[0])) ? $arr_params[0] : '';
		$serial_type = (isset($arr_params[1])) ? $arr_params[1] : '';
		$serial_type_id = (isset($arr_params[2])) ? $arr_params[2] : '';
		if ($quantity == '' || $member_id == '' || $serial_type == '' || $serial_type_id == '') {
			return FALSE;
		}
		$date = date("Y-m-d H:i:s");
		if ($serial_type == "activation") {
			$sql = "
			SELECT *
			FROM sys_serial_member_stock
			JOIN sys_serial_type ON serial_member_stock_serial_type_id = serial_type_id
			WHERE serial_member_stock_member_id = {$member_id}
			AND serial_member_stock_is_used = 0
			AND serial_member_stock_expired_date >= '{$date}'
			AND serial_type_id = '{$serial_type_id}'
			ORDER BY serial_member_stock_expired_date ASC
			LIMIT 0,{$quantity}
			";
		} else if ($serial_type == "repeatorder") {
			$sql = "
			SELECT *
			FROM sys_serial_ro_member_stock
			JOIN sys_serial_ro_type ON serial_ro_member_stock_serial_ro_type_id = serial_ro_type_id
			WHERE serial_ro_member_stock_member_id = {$member_id}
			AND serial_ro_member_stock_is_used = 0
			AND serial_ro_member_stock_expired_date >= '{$date}'
			AND serial_ro_type_id = '{$serial_type_id}'
			ORDER BY serial_ro_member_stock_expired_date ASC
			LIMIT 0,{$quantity}
			";
		} else {
			return FALSE;
		}
		$serial = $this->db->query($sql)->getResult();
		if (!empty($serial) && $quantity <= count($serial)) {
			return TRUE;
		}
		return FALSE;
	}

	//cek apakah serial stok member ada (by serial type)
	public function check_membership_serial($serial_serial_type_id)
	{
		$sql = "
			SELECT COUNT(serial_member_stock_serial_id) AS serials
			FROM sys_serial_member_stock
			WHERE serial_member_stock_serial_type_id = '{$serial_serial_type_id}'
			LIMIT 1
		";
		$serial = $this->db->query($sql)->getRow();
		if ($serial->serials > 0) {
			return TRUE;
		}
		return FALSE;
	}

	//cek apakah type serial ro sama dengan type join
	public function check_serial_ro_join($serial_ro_id, $member_id)
	{
		$serial_type_id = $this->db->table("sys_network")->getWhere(["network_member_id" => $member_id])->getRow("network_serial_type_id");
		$sql = "
			SELECT serial_ro_id
			FROM sys_serial_ro
			WHERE serial_ro_serial_ro_type_id = '{$serial_type_id}'
			AND serial_ro_id = '{$serial_ro_id}'
			LIMIT 1
		";
		$serial = $this->db->query($sql)->getRow();
		if (!is_null($serial)) {
			return TRUE;
		}
		return FALSE;
	}

	public function check_serial_pin($serial_pin, $serial_id)
	{
		$sql = "
			SELECT serial_id
			FROM sys_serial
			WHERE serial_id = '{$serial_id}'
			AND serial_pin = '{$serial_pin}'
			AND serial_is_active = 1
			AND serial_is_used = 0
			LIMIT 1
		";
		$serial = $this->db->query($sql)->getRow();
		if (!is_null($serial)) {
			return TRUE;
		}
		return FALSE;
	}

	//cek apakah type upgrade lebih tinggi dari type sekarang
	public function check_serial_member_upgrade($serial_id, $member_id)
	{
		$sql = "
			SELECT serial_type_bv
			FROM sys_serial
			JOIN sys_serial_type ON serial_type_id = serial_serial_type_id
			WHERE serial_id = '{$serial_id}'
			LIMIT 1
		";
		$serial_bv = $this->db->query($sql)->getRow("serial_type_bv");
		$sql = "
			SELECT serial_type_bv
			FROM sys_member_plan_activity
			JOIN sys_serial_type ON serial_type_id = member_plan_activity_serial_type_id
			JOIN sys_member ON member_id = member_plan_activity_member_id
			WHERE member_id = '{$member_id}'
			ORDER BY member_plan_activity_datetime DESC
			LIMIT 1
		";
		$network_bv = $this->db->query($sql)->getRow("serial_type_bv");
		if ($serial_bv > $network_bv) {
			return TRUE;
		}
		return FALSE;
	}

	//fungsi cek apakah serial sesuai dengan pemilik & belum kadaluarsa (jika ada tanggal kadaluarsa)
	public function check_serial_member_stock($serial_id, $params = null)
	{
		$arr_params = explode(',', $params);
		$member_id = (isset($arr_params[0])) ? $arr_params[0] : '';
		if ($serial_id == '' || $member_id == '') {
			return FALSE;
		}

		$date = date('Y-m-d');
		$sql = "
			SELECT 1
			FROM sys_serial_member_stock
			WHERE serial_member_stock_serial_id = '{$serial_id}'
			AND serial_member_stock_member_id = {$member_id}
			AND serial_member_stock_is_used = '0'
			AND (IF(serial_member_stock_expired_date != NULL, serial_member_stock_expired_date < '{$date}', TRUE))
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return TRUE;
		}

		return FALSE;
	}

	//fungsi cek apakah serial sesuai dengan pemilik & belum kadaluarsa (jika ada tanggal kadaluarsa) by serial type
	public function check_serial_member_stock_type($serial_type_id, $params = null)
	{
		$arr_params = explode(',', $params);
		$member_id = (isset($arr_params[0])) ? $arr_params[0] : '';
		if ($serial_type_id == '' || $member_id == '') {
			return FALSE;
		}

		$date = date('Y-m-d');
		$sql = "
			SELECT 1
			FROM sys_serial_member_stock
			WHERE serial_member_stock_serial_type_id = '{$serial_type_id}'
			AND serial_member_stock_is_used = '0'
			AND serial_member_stock_member_id = {$member_id}
			AND (IF(serial_member_stock_expired_date != NULL, serial_member_stock_expired_date < '{$date}', TRUE))
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return TRUE;
		}

		return FALSE;
	}

	//fungsi cek apakah serial sesuai dengan pemilik & belum kadaluarsa (jika ada tanggal kadaluarsa)
	public function check_serial_ro_member_stock($serial_id, $params = null)
	{
		$arr_params = explode(',', $params);
		$member_id = (isset($arr_params[0])) ? $arr_params[0] : '';
		if ($serial_id == '' || $member_id == '') {
			return FALSE;
		}

		$date = date('Y-m-d');
		$sql = "
			SELECT 1
			FROM sys_serial_ro_member_stock
			WHERE serial_ro_member_stock_serial_ro_id = '{$serial_id}'
			AND serial_ro_member_stock_is_used = '0'
			AND serial_ro_member_stock_member_id = {$member_id}
			AND (IF(serial_ro_member_stock_expired_date != NULL, serial_ro_member_stock_expired_date < '{$date}', TRUE))
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return TRUE;
		}

		return FALSE;
	}

	//fungsi cek no identitas apakah sudah pernah didaftarkan membership dan kadaluarsa
	//public function check_identity($identity_no, $params = null)
	public function check_identity_no($identity_no, $params = null)
	{
		if ($identity_no == '') {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member
			WHERE member_identity_no = '{$identity_no}'
			AND member_is_expired = 1
			AND member_is_network = 0
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_warehouse_transaction ON warehouse_transaction_id = member_registration_transaction_id
			JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
			WHERE member_identity_no = '{$identity_no}'
			AND member_registration_transaction_type = 'warehouse'
			AND warehouse_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_stockist_transaction ON stockist_transaction_id = member_registration_transaction_id
			JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
			WHERE member_identity_no = '{$identity_no}'
			AND member_registration_transaction_type = 'stockist'
			AND stockist_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		return TRUE;
	}

	//fungsi cek apakah username available
	//public function check_email($email = null, $params = null)
	public function check_username($username, $params = null)
	{
		if ($username == '') {
			return FALSE;
		}

		$sql = "SELECT 1 FROM sys_member_account WHERE member_account_username = '{$username}' LIMIT 1";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		return TRUE;
	}

	//fungsi cek apakah nomor handphone sudah terdaftar
	public function check_mobilephone($mobilephone, $member_parent_member_id = false)
	{
		if ($mobilephone == '') {
			return FALSE;
		}

		//member_status 3: deleted
		$mobilephone = phoneNumberFilter($mobilephone);
		$where = "";
		if ($member_parent_member_id) {
			$group = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $member_parent_member_id])->getResult();
			$ids = [];
			foreach ($group as $key => $member) {
				$ids[] = $member->member_id;
			}
			$ids = implode(",", $ids);
			$where = " AND member_id NOT IN ({$ids}) ";
		}
		$sql = "SELECT 1 FROM sys_member WHERE member_mobilephone = '{$mobilephone}' AND member_status != 3 {$where} LIMIT 1";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_warehouse_transaction ON warehouse_transaction_id = member_registration_transaction_id
			JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
			WHERE member_mobilephone = '{$mobilephone}'
			AND member_registration_transaction_type = 'warehouse'
			AND warehouse_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		$sql = "SELECT 1
			FROM sys_member_registration
			JOIN inv_stockist_transaction ON stockist_transaction_id = member_registration_transaction_id
			JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
			WHERE member_mobilephone = '{$mobilephone}'
			AND member_registration_transaction_type = 'stockist'
			AND stockist_transaction_payment_status IN ('PENDING')
			LIMIT 1
		";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return FALSE;
		}

		return TRUE;
	}

	//fungsi cek apakah bank ada dan aktif dalam data ref_bank
	public function check_bank_by_id($bank_id)
	{
		if ($bank_id == '') {
			return FALSE;
		}

		$sql = "SELECT 1 FROM ref_bank WHERE bank_id = {$bank_id} AND bank_is_active = 1 LIMIT 1";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return TRUE;
		}

		return FALSE;
	}

	//fungsi cek apakah bank ada dan aktif dalam data ref_bank
	public function check_bank_by_name($bank_name)
	{
		if ($bank_name == '') {
			return FALSE;
		}

		$sql = "SELECT 1 FROM ref_bank WHERE bank_name = '{$bank_name}' AND bank_is_active = 1 LIMIT 1";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			return TRUE;
		}

		return FALSE;
	}

	//fungsi cek password member
	public function check_old_password($field, $params)
	{
		$arr_params = explode(',', $params);
		$password = (isset($arr_params[0])) ? $arr_params[0] : '';
		$member_id = (isset($arr_params[1])) ? $arr_params[1] : '';
		if ($password == '' || $member_id == '') {
			return FALSE;
		}

		$sql = "SELECT member_account_password FROM sys_member_account WHERE member_account_member_id = {$member_id}";
		$row = $this->db->query($sql)->getRow();
		if (!is_null($row)) {
			if (password_verify($password, $row->member_account_password)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	//fungsi cek kode unik
	public function check_captcha($params)
	{
		$captcha = \Config\Services::Captcha();

		if (!$captcha->verify($params)) {
			return false;
		} else {
			return true;
		}
	}

	// cek apakah sudah di terima
	public function check_status_receive($warehouse_transaction_id)
	{
		$method_delivery = $this->db->table("inv_warehouse_transaction")->select("warehouse_transaction_delivery_method")->getWhere(["warehouse_transaction_id" => $warehouse_transaction_id])->getRow("warehouse_transaction_delivery_method");

		$status = "delivered";
		if ($method_delivery == "pickup") {
			$status = "paid";
		}

		$transaction = $this->db->table("inv_warehouse_transaction")->select("warehouse_transaction_id")->getWhere([
			"warehouse_transaction_id" => $warehouse_transaction_id,
			"warehouse_transaction_status" => $status
		])->getRow();

		if (is_null($transaction)) {
			return false;
		} else {
			return true;
		}
	}

	public function check_status_receive_stockist($stockist_transaction_id)
	{
		$method_delivery = $this->db->table("inv_stockist_transaction")->select("stockist_transaction_delivery_method")->getWhere(["stockist_transaction_id" => $stockist_transaction_id])->getRow("stockist_transaction_delivery_method");

		$status = "delivered";

		if ($method_delivery == "pickup") {
			$status = "paid";
		}

		$transaction = $this->db->table("inv_stockist_transaction")->select("stockist_transaction_id")->getWhere([
			"stockist_transaction_id" => $stockist_transaction_id,
			"stockist_transaction_status" => $status
		])->getRow();

		if (is_null($transaction)) {
			return false;
		} else {
			return true;
		}
	}
}
