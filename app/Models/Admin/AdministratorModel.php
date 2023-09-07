<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class AdministratorModel extends Model
{

    public function getDataAdministrator($group_type)
    {
        $sql = "
            SELECT 
				administrator_id,
				administrator_username,
				administrator_name,
				administrator_group_title,
				administrator_group_id
				administrator_last_login,
				administrator_is_active
			FROM site_administrator
			JOIN site_administrator_group ON administrator_group_id = administrator_administrator_group_id
		";
        $query = $this->db->query($sql)->getResult();
        $total = $this->db->query('SELECT FOUND_ROWS() as total')->getRow()->total;
        $output['data'] = $query;
        $output['total'] = $total;

        return $output;
    }

    public function getDetailadministratorGroup($group_type)
    {
        $sql = "SELECT 
				administrator_group_id as id,
				administrator_group_title as label
			FROM site_administrator_group WHERE administrator_group_is_active = 1   
";
        if ($group_type !== 'superuser') {
            $sql .= "AND administrator_group_id != '1'";
        }

        return $this->db->query($sql)->getResult();
    }

    public function getDetailAdministrator($id)
    {
        $data = array();
        $listData = array();
        $sql = "SELECT 
                administrator_id,
                administrator_administrator_group_id,
                administrator_username,
                administrator_name,
                administrator_email,
                administrator_group_title,
                administrator_group_type,
                administrator_privilege_administrator_group_id,
                administrator_privilege_administrator_menu_id,
                administrator_menu_title,
                administrator_menu_id
            from site_administrator
            JOIN site_administrator_group ON administrator_group_id = administrator_administrator_group_id
            JOIN site_administrator_privilege ON administrator_privilege_administrator_group_id = administrator_administrator_group_id
            JOIN site_administrator_menu ON administrator_menu_id = administrator_privilege_administrator_menu_id
            where administrator_id = $id and administrator_menu_par_id = 0
            ORDER BY administrator_menu_order_by ASC
        ";

        $query = $this->db->query($sql);

        if (!empty($query)) {
            $data['status'] = true;
            foreach ($query->getResult() as $key => $value) {
                $subMenu = "SELECT 
                        administrator_id,
                        administrator_administrator_group_id,
                        administrator_username,
                        administrator_name,
                        administrator_email,
                        administrator_group_title,
                        administrator_group_type,
                        administrator_privilege_administrator_group_id,
                        administrator_privilege_administrator_menu_id,
                        administrator_menu_title,
                        administrator_menu_id
                    from site_administrator
                    JOIN site_administrator_group ON administrator_group_id = administrator_administrator_group_id
                    JOIN site_administrator_privilege ON administrator_privilege_administrator_group_id = administrator_administrator_group_id
                    JOIN site_administrator_menu ON administrator_menu_id = administrator_privilege_administrator_menu_id
                    WHERE administrator_menu_par_id = $value->administrator_menu_id and  administrator_id = $id
                    ORDER BY administrator_menu_order_by ASC 
                ";
                $querySubMenu = $this->db->query($subMenu);
                $subMenu = $querySubMenu->getResult();
                $res = array(
                    "administratorMenuId" => $value->administrator_menu_id,
                    "administratorMenuTitle" => $value->administrator_menu_title,
                    "SubMenu" => $subMenu
                );
                $listData[] = $res;
            }
            $data['result'] = $listData;
        } else {
            $data['status'] = false;
            $data['result'] = array();
        }
        return $data;
    }

    public function getSuperuserMenu()
    {
        $data = array();
        $listData = array();

        $sql = "SELECT *
                FROM site_administrator_menu
                WHERE administrator_menu_par_id = 0
                ORDER BY administrator_menu_order_by ASC
            ";
        $query = $this->db->query($sql);

        if (!empty($query)) {
            $data['status'] = true;
            foreach ($query->getResult() as $key => $value) {
                $subMenu =
                    " SELECT *
                    FROM site_administrator_menu
                    WHERE administrator_menu_par_id = $value->administrator_menu_id
                    ORDER BY administrator_menu_order_by ASC 
                ";
                $querySubMenu = $this->db->query($subMenu);
                $subMenu = $querySubMenu->getResult();
                $res = array(
                    "administratorMenuId" => $value->administrator_menu_id,
                    "administratorMenuTitle" => $value->administrator_menu_title,
                    "SubMenu" => $subMenu
                );
                $listData[] = $res;
            }
            $data['result'] = $listData;
        } else {
            $data['status'] = false;
            $data['result'] = array();
        }
        return $data;
    }

    public function getDetailMenuAdministratorGroup($id)
    {
        $data = array();
        $listData = array();
        $sql = "SELECT 
                administrator_group_id,
                administrator_group_type,
                administrator_group_title,
                administrator_group_is_active,
                administrator_privilege_administrator_group_id,
                administrator_privilege_administrator_menu_id,
                administrator_menu_title,
                administrator_menu_id
            FROM site_administrator_group
            JOIN site_administrator_privilege ON administrator_privilege_administrator_group_id = administrator_group_id
            JOIN site_administrator_menu ON administrator_menu_id = administrator_privilege_administrator_menu_id
            WHERE administrator_group_id = $id and administrator_menu_par_id = 0 
            ORDER BY administrator_menu_order_by ASC
        ";
        $query = $this->db->query($sql);

        if (!empty($query)) {
            $data['status'] = true;
            foreach ($query->getResult() as $key => $value) {
                $subMenu = "SELECT 
                        administrator_group_id,
                        administrator_group_type,
                        administrator_group_title,
                        administrator_group_is_active,
                        administrator_privilege_administrator_group_id,
                        administrator_privilege_administrator_menu_id,
                        administrator_menu_title,
                        administrator_menu_id
                    FROM site_administrator_group
                    JOIN site_administrator_privilege ON administrator_privilege_administrator_group_id = administrator_group_id
                    JOIN site_administrator_menu ON administrator_menu_id = administrator_privilege_administrator_menu_id
                    WHERE administrator_menu_par_id = $value->administrator_menu_id and  administrator_group_id = $id
                    ORDER BY administrator_menu_order_by ASC 
                ";
                $querySubMenu = $this->db->query($subMenu);
                $subMenu = $querySubMenu->getResult();
                $res = array(
                    "administratorMenuId" => $value->administrator_menu_id,
                    "administratorMenuTitle" => $value->administrator_menu_title,
                    "SubMenu" => $subMenu
                );
                $listData[] = $res;
            }
            $data['result'] = $listData;
        } else {
            $data['status'] = false;
            $data['result'] = array();
        }
        return $data;
    }

    public function activeAdministrator($id)
    {
        $query = "UPDATE site_administrator
				SET administrator_is_active = 1
				WHERE administrator_id = ?
			";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveAdministrator($id)
    {
        $query = "UPDATE site_administrator
				SET administrator_is_active = 0
				WHERE administrator_id = ?
			";
        return $this->db->query($query, [$id]);
    }

    public function removeAdministrator($id)
    {
        $query = "DELETE FROM site_administrator
				WHERE administrator_id = ?
			";
        return $this->db->query($query, [$id]);
    }

    public function getDataEdit($id)
    {
        $sql = "SELECT *
                FROM site_administrator_privilege
                WHERE administrator_privilege_administrator_group_id = ?";

        return $this->db->query($sql, [$id])->getResult();
    }

    public function removeGroup($id)
    {
        $query = "DELETE FROM site_administrator_group WHERE administrator_group_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function removePrevillage($id)
    {
        $query = "DELETE FROM site_administrator_privilege WHERE administrator_privilege_administrator_group_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function activeGroup($id)
    {
        $query = "UPDATE site_administrator_group SET administrator_group_is_active = 1 WHERE administrator_group_id = ?";
        return  $this->db->query($query, [$id]);
    }

    public function nonActiveGroup($id)
    {
        $query = "UPDATE site_administrator_group SET administrator_group_is_active = 0 WHERE administrator_group_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function administrator_group_option($group_type)
    {
        $options = array();
        $sql = "SELECT administrator_group_title FROM site_administrator_group ";

        if ($group_type !== 'superuser') {
            $sql .= "WHERE administrator_group_id != '1'";
        }


        $sql .= " ORDER BY administrator_group_title ASC";

        $query = $this->db->query($sql)->getResult();
        if (!empty($query)) {
            $i = 0;
            foreach ($query as $row) {
                $options[$i]['title'] = $row->administrator_group_title;
                $options[$i]['value'] = $row->administrator_group_title;
                $i++;
            }
        }
        return $options;
    }
}
