<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class BusinessPlanModel extends Model
{
    public function addBusiness($data)
    {
        $this->db->table('site_business_page')->insert($data);

        if ($this->affectedRows() > 0)
            return true;
        else
            return false;
    }

    public function businessIsActive($page_id)
    {
        $sql = "SELECT page_id FROM site_business_page WHERE page_is_active = 1 AND page_id = '$page_id'";
        return $this->db->query($sql)->getRow('page_id');
    }

    public function countActiveBusiness()
    {
        $sql = "SELECT COUNT(page_id) AS total_active FROM site_business_page WHERE page_is_active = 1";
        return $this->db->query($sql)->getRow('total_active');
    }

    function changeOrderMenu($order, $op)
    {
        $next_order = $op == 'up' ? $order - 1 : $order + 1;

        $sql_last_order = "
        SELECT
            MAX(page_order_by) as last_order
        FROM site_business_page
        ";

        $last_order = $this->db->query($sql_last_order)->getRow('last_order');

        if ($next_order == '0' || $next_order > $last_order) {
            throw new \Exception("Error Processing Request", 1);
        }

        $sql_current_id = "
        SELECT
            page_id
        FROM site_business_page
        WHERE page_order_by = '$order'
        ";

        $current_id = $this->db->query($sql_current_id)->getRow('page_id');

        $sql_next_id = "
        SELECT
            page_id
        FROM site_business_page
        WHERE page_order_by = '$next_order'
        ";

        $next_id = $this->db->query($sql_next_id)->getRow('page_id');

        $update_next_id = "
        UPDATE site_business_page
        SET page_order_by = '$next_order'
        WHERE page_id = '$current_id'
        ";


        $this->db->query($update_next_id);

        $update_current_id = "
        UPDATE site_business_page
        SET page_order_by = '$order'
        WHERE page_id = '$next_id'
        ";

        $this->db->query($update_current_id);
    }
}
