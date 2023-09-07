<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ProfileModel extends Model
{

    public function getAdminProfile($id)
    {
        $data = array();

        if ($id) {
            $sql = "
                SELECT 
                    administrator_id,
                    administrator_username,
                    administrator_name,
                    administrator_password,
                    administrator_email,
                    administrator_image,
                    administrator_last_login,
                    administrator_is_active
                FROM site_administrator
                WHERE administrator_id = '$id'
            ";

            $query = $this->db->query($sql);
            $results = $query->getRow();

            $data = $results;
        }

        return $data;
    }

    public function updateAdminProfile($data, $where)
    {
        $this
            ->db
            ->table('site_administrator')
            ->update($data, $where);
    }
}
