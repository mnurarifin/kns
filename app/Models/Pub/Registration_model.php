<?php

namespace App\Models\Pub;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Registration_model extends Model
{
    public function getMemberName($network_slug)
    {
        return $this->db->table("sys_member")->join("sys_member_account", "member_account_member_id = member_id")
            ->join("sys_network", "network_member_id = member_id")
            ->select("member_name, member_account_username")->getWhere(["network_slug" => $network_slug])->getRow();
    }
}
