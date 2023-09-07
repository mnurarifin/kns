<?php

namespace App\Models\Pub;

use CodeIgniter\Model;

class Login_model extends Model
{
	public function getAdminAccount($username)
	{
		$sql = "
			SELECT administrator_id,
			administrator_password
			FROM site_administrator
			WHERE administrator_username = '{$username}'
		";
		$row_account = $this->db->query($sql)->getRow();

		if (!is_null($row_account)) {
			return $row_account;
		} else {
			return FALSE;
		}
	}

	public function getMemberAccount($username)
	{
		$sql = "
			SELECT member_account_member_id,
			member_account_password
			FROM sys_member_account
			WHERE member_account_username = '{$username}'
		";
		$row_account = $this->db->query($sql)->getRow();

		if (!is_null($row_account)) {
			return $row_account;
		} else {
			return FALSE;
		}
	}

	public function getMemberByOTP($otp, $username)
	{
		$sql = "
			SELECT member_id,
			member_parent_member_id,
			member_name,
			member_image,
			member_is_expired,
			member_is_network,
			member_status,
			network_code,
			network_rank_id,
			network_slug
			FROM sys_member
			jOIN sys_member_account ON member_account_member_id = member_id
			LEFT JOIN sys_network ON network_member_id = member_id
			WHERE member_id = (
				SELECT member_otp_member_id
				FROM sys_member_otp
				WHERE member_otp_value = '{$otp}'
				AND member_otp_expired_datetime > NOW()
			)
			AND member_account_username = '{$username}'
		";
		$row_member = $this->db->query($sql)->getRow();
		if (!is_null($row_member)) {
			return $row_member;
		} else {
			return FALSE;
		}
	}

	public function getAdmin($id)
	{
		$sql = "
			SELECT administrator_id,
			administrator_name,
			administrator_image,
			administrator_group_id,
			administrator_group_type,
			administrator_is_active,
			administrator_last_login
			FROM site_administrator
			JOIN site_administrator_group ON administrator_group_id = administrator_administrator_group_id
			WHERE administrator_id = {$id}
		";
		$row_member = $this->db->query($sql)->getRow();
		if (!is_null($row_member)) {
			return $row_member;
		} else {
			return FALSE;
		}
	}

	public function getMember($id)
	{
		$sql = "
			SELECT member_id,
			member_parent_member_id,
			member_name,
			member_image,
			member_is_expired,
			member_is_network,
			member_status,
			network_code,
			network_rank_id,
			network_slug
			FROM sys_member
			LEFT JOIN sys_network ON network_member_id = member_id
			WHERE member_id = {$id}
		";
		$row_member = $this->db->query($sql)->getRow();
		if (!is_null($row_member)) {
			return $row_member;
		} else {
			return FALSE;
		}
	}

	public function getStockist($id)
	{
		$sql = "
			SELECT stockist_member_id, stockist_type
			FROM inv_stockist
			WHERE stockist_member_id = {$id}
		";
		$row_stockist = $this->db->query($sql)->getRow();
		if (!is_null($row_stockist)) {
			return [
				"is_stockist" => TRUE,
				"stockist_type" => $row_stockist->stockist_type,
			];
		} else {
			return [
				"is_stockist" => FALSE,
				"stockist_type" => "",
			];
		}
	}

	public function getUpgrade($member_id)
	{
		$sql = "SELECT
		product_package_point
		FROM inv_product_package
		ORDER BY product_package_point DESC
		";
		$max_bv = $this->db->query($sql)->getRow("product_package_point");

		$sql = "
			SELECT product_package_point
			FROM sys_member_plan_activity
			JOIN inv_product_package ON product_package_id = member_plan_activity_product_package_id
			JOIN sys_member ON member_id = member_plan_activity_member_id
			WHERE member_id = '{$member_id}'
			ORDER BY member_plan_activity_datetime DESC
			LIMIT 1
		";
		$network_bv = $this->db->query($sql)->getRow("serial_type_bv");

		return $max_bv > $network_bv;
	}

	public function getSuperuserMenu($menuParID = null)
	{
		$whereOption = "";
		if ($menuParID != null) {
			$whereOption = " AND administrator_menu_par_id = '" . $menuParID . "'";
		}
		$sql = "
            SELECT *
            FROM site_administrator_menu
            WHERE administrator_menu_is_active = '1'
            " . $whereOption . "
            ORDER BY administrator_menu_order_by ASC
        ";
		return $this->db->query($sql)->getResult();
	}

	public function getAdministratorMenu($administratorGroupID = 0, $menuParID = null)
	{
		$whereOption = "";
		if ($menuParID != null) {
			$whereOption = " AND administrator_menu_par_id = '" . $menuParID . "'";
		}
		$sql = "
            SELECT site_administrator_menu.*,
            administrator_privilege_action
            FROM site_administrator_menu
            INNER JOIN site_administrator_privilege ON administrator_menu_id = administrator_privilege_administrator_menu_id
            WHERE administrator_menu_is_active = '1'
            AND administrator_privilege_administrator_group_id = '" . $administratorGroupID . "'
            " . $whereOption . "
            ORDER BY administrator_menu_id, administrator_menu_order_by ASC
        ";
		return $this->db->query($sql)->getResult();
	}
}
