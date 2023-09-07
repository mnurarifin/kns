<?php

namespace App\Models\Member;

use CodeIgniter\Model;

class Profile_model extends Model
{
    public function get($id)
    {
        $data = $this->db->table("sys_member")->select([
            "member_id",
            "member_image",
            "member_name",
            "member_email",
            "member_identity_type",
            "member_identity_no",
            "DATE(member_join_datetime) AS member_join_datetime",
            "member_mobilephone",
            "member_gender",
            "member_job",
            "member_birth_place",
            "member_birth_date",
            "member_province_id",
            "member_city_id",
            "member_subdistrict_id",
            "member_address",
            "member_bank_account_name",
            "member_bank_account_no",
            "member_bank_id",
            "member_bank_branch",
            "member_devisor_name",
            "member_devisor_relation",
            "main.member_account_username AS member_account_username",
            "network_code",
            "sponsor.member_account_username AS sponsor_member_account_username",
        ])
            ->join("sys_network", "network_member_id = member_id", "LEFT")
            ->join("sys_member_account main", "main.member_account_member_id = member_id")
            ->join("sys_member_account sponsor", "sponsor.member_account_member_id = network_sponsor_member_id", "LEFT")->getWhere(["member_id" => $id])->getRow();

        if ($data->member_image != '' && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $data->member_image)) {
            $data->member_image = UPLOAD_URL . URL_IMG_MEMBER . "{$data->member_image}";
        } else {
            $data->member_image = UPLOAD_URL . URL_IMG_MEMBER . "_default.png";
        }

        $data->member_join_datetime_formatted = convertDatetime($data->member_join_datetime, 'id');
        if (is_null($data->sponsor_member_account_username)) {
            $data->sponsor_member_account_username = '-';
        }

        return $data;
    }

    public function edit($update, $where)
    {
        $this->db->table("sys_member")->update($update, $where);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }

        return $this->get($where);
    }

    public function editPassword($update, $where)
    {
        $this->db->table("sys_member_account")->update($update, $where);
        if ($this->db->affectedRows() < 0) {
            throw new \Exception("Gagal mengubah data.", 1);
        }

        return TRUE;
    }

    public function getChildren($member_id)
    {
        $parent_member_id = $this->db->table("sys_member")->getWhere(["member_id" => $member_id])->getRow("member_parent_member_id");
        return $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $parent_member_id])->getResult();
    }

    public function getParent($member_id)
    {
        return $this->db->table("sys_member")->getWhere(["member_id" => $member_id])->getRow("member_parent_member_id");
    }
}
