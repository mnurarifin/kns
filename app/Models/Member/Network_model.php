<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Network_model extends Model
{
    public $request;

    public function init($request = null)
    {
        $this->request = $request;
    }

    public function getUpgrade($member_id)
    {
        $sql = "SELECT
		serial_type_bv
		FROM sys_serial_type
		ORDER BY serial_type_bv DESC
		";
        $max_bv = $this->db->query($sql)->getRow("serial_type_bv");

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

        return $max_bv > $network_bv;
    }

    public function getMemberByMemberId($id)
    {
        return $this->db->table("sys_member")->join("sys_member_account", "member_account_member_id = member_id")->getWhere(["member_id" => $id])->getRow();
    }

    public function getCloneSuffix($id)
    {
        $parent_member_id = $this->db->table("sys_member")->getWhere(["member_id" => $id])->getRow("member_parent_member_id");
        $sql = "SELECT COUNT(*) AS member_count
        FROM sys_member
        WHERE member_parent_member_id = '{$parent_member_id}'
        ";
        $member_count = $this->db->query($sql)->getRow("member_count");
        return is_null($member_count) ? 1 : $member_count + 1;
    }

    public function getParentMemberUsername($id)
    {
        $sql = "SELECT member_account_username
        FROM sys_member_account
        JOIN sys_member ON member_account_member_id = member_parent_member_id
        WHERE member_id = '{$id}'
        ";
        return $this->db->query($sql)->getRow("member_account_username");
    }

    public function saveMemberRegistration($insert)
    {
        $this->db->table("sys_member_registration")->insert($insert);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah data calon mitra.", 1);
        }
        $id = $this->db->insertID();

        return [
            "member_registration_id" => $id,
            "member_account_username" => $insert["member_registration_username"],
            "sponsor_username" => $insert["member_registration_sponsor_username"],
        ];
    }

    public function getWaitPlacement($id)
    {
        $network_code = $this->db->table("sys_network")->getWhere(["network_member_id" => $id])->getRow("network_code");
        $tableName = "sys_member";
        $columns = [
            'sys_member.member_id' => 'member_id',
            'sys_member.member_name' => 'member_name',
            'sys_member.member_join_datetime' => 'member_join_datetime',
            'member_account_username',
            'member_registration_id',
        ];
        $joinTable = " JOIN sys_member_account ON member_account_member_id = member_id";
        $joinTable .= " JOIN sys_member_registration ON sys_member_registration.member_id = sys_member.member_id";
        $whereCondition = "member_registration_sponsor_username = '{$network_code}' AND sys_member.member_is_network = 0";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data['results'] as $key => $value) {
            $arr_data['results'][$key]['member_join_datetime_formatted'] = convertDatetime($value['member_join_datetime'], 'id');
        }

        return $arr_data;
    }
}
