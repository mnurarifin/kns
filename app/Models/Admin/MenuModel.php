<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MenuModel extends Model
{


    /*
    //MENU ADMINISTRATOR
    //================================================================================
    */

    public function removeMenu($id)
    {
        $query = "DELETE FROM site_administrator_menu WHERE administrator_menu_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function removePrivilegeByMenu($id)
    {
        $query = "DELETE FROM site_administrator_privilege WHERE administrator_privilege_administrator_menu_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function activeMenu($id)
    {
        $query = "UPDATE site_administrator_menu SET administrator_menu_is_active = 1 WHERE administrator_menu_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveMenu($id)
    {
        $query = "UPDATE site_administrator_menu SET administrator_menu_is_active = 0 WHERE administrator_menu_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function getSubMenuId($id)
    {
        $query = "SELECT administrator_menu_id
                FROM site_administrator_menu
                WHERE administrator_menu_par_id = ?
            ";
        return $this->db->query($query, [$id])->getResult();
    }

    function ChangeOrderMenu($id, $op, $sort)
    {
        $isOk = false;
        $this->db->transBegin();

        try {
            $query = "SELECT administrator_menu_par_id, administrator_menu_order_by FROM site_administrator_menu WHERE administrator_menu_id = ?";
            $menu = $this->db->query($query, [$id])->getRow();

            $query2 = "SELECT administrator_menu_id, administrator_menu_order_by 
                    FROM site_administrator_menu 
                    WHERE administrator_menu_par_id = {$menu->administrator_menu_par_id} AND administrator_menu_order_by {$op} {$menu->administrator_menu_order_by}
                    ORDER BY administrator_menu_order_by {$sort}";
            $menu2 = $this->db->query($query2)->getRow();

            if (!empty($menu2)) {
                $res = $this->db->query("UPDATE site_administrator_menu SET administrator_menu_order_by = {$menu2->administrator_menu_order_by} WHERE administrator_menu_id = {$id}");
                if ($res) {
                    $res2 = $this->db->query("UPDATE site_administrator_menu SET administrator_menu_order_by = {$menu->administrator_menu_order_by} WHERE administrator_menu_id = {$menu2->administrator_menu_id}");
                    if ($res2) {
                        $isOk = true;
                    }
                }
            } else {
                //menu udah ada di paling ujung
                $isOk = true;
            }
        } catch (\Throwable $th) {
            $isOk = false;
            $msg = $th->getMessage();
        }

        if (!$isOk) {
            $this->db->transRollback();
        } else {
            if ($this->db->transStatus() === FALSE) {
                $isOk = false;
                $this->db->transRollback();
            } else {
                $this->db->transCommit();
            }
        }
        return $isOk;
    }

    function ChangeOrderPublicMenu($id, $op, $sort)
    {
        $isOk = false;
        $this->db->transBegin();

        try {
            $query = "SELECT menu_par_id, menu_order_by FROM site_menu WHERE menu_id = ?";
            $menu = $this->db->query($query, [$id])->getRow();

            $query2 = "SELECT menu_id, menu_order_by 
                    FROM site_menu 
                    WHERE menu_par_id = {$menu->menu_par_id} AND menu_order_by {$op} {$menu->menu_order_by}
                    ORDER BY menu_order_by {$sort}";
            $menu2 = $this->db->query($query2)->getRow();

            if (!empty($menu2)) {
                $res = $this->db->query("UPDATE site_menu SET menu_order_by = {$menu2->menu_order_by} WHERE menu_id = {$id}");
                if ($res) {
                    $res2 = $this->db->query("UPDATE site_menu SET menu_order_by = {$menu->menu_order_by} WHERE menu_id = {$menu2->menu_id}");
                    if ($res2) {
                        $isOk = true;
                    }
                }
            } else {
                //menu udah ada di paling ujung
                $isOk = true;
            }
        } catch (\Throwable $th) {
            $isOk = false;
            $msg = $th->getMessage();
        }

        if (!$isOk) {
            $this->db->transRollback();
        } else {
            if ($this->db->transStatus() === FALSE) {
                $isOk = false;
                $this->db->transRollback();
            } else {
                $this->db->transCommit();
            }
        }
        return $isOk;
    }


    /*
    //MENU MEMBER
    //================================================================================
    */

    public function removeMenuPublic($id)
    {
        $query = "DELETE FROM site_menu WHERE menu_id  = ?";
        return $this->db->query($query, [$id]);
    }

    public function activeMenuPublic($id)
    {
        $query = "UPDATE site_menu SET menu_is_active = 1 WHERE menu_id  = ?";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveMenuPublic($id)
    {
        $query = "UPDATE site_menu SET menu_is_active = 0 WHERE menu_id  = ?";
        return $this->db->query($query, [$id]);
    }

    public function getSubMenuIdPublic($id)
    {
        $query = "SELECT menu_id 
                FROM site_menu
                WHERE menu_par_id = ?
            ";
        return $this->db->query($query, [$id]);
    }
}
