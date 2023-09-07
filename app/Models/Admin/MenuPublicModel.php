<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MenuPublicModel extends Model
{

    public function removeMenuPublic($id)
    {
        $query = "DELETE FROM site_menu WHERE menu_id  = ?";
        return $this->query($query, [$id]);
    }

    public function activeMenuPublic($id)
    {
        $query = "UPDATE site_menu SET menu_is_active = 1 WHERE menu_id  = ?";
        return $this->query($query, [$id]);
    }

    public function nonActiveMenuPublic($id)
    {
        $query = "UPDATE site_menu SET menu_is_active = 0 WHERE menu_id  = ?";
        return $this->query($query, [$id]);
    }

    public function getSubMenuIdPublic($id)
    {
        $query = "SELECT menu_id 
                FROM site_menu
                WHERE menu_par_id = ?
            ";
        return $this->query($query, [$id]);
    }
}
