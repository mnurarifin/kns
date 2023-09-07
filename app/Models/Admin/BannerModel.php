<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class BannerModel extends Model
{
    public function addBanner($data)
    {
        $this->db->table('site_banner')->insert($data);

        if ($this->affectedRows() > 0)
            return true;
        else
            return false;
    }

    public function bannerIsActive($banner_id)
    {
        $sql = "SELECT banner_id FROM site_banner WHERE banner_is_active = 1 AND banner_id = '$banner_id'";
        return $this->db->query($sql)->getRow('banner_id');
    }

    public function countActiveBanner()
    {
        $sql = "SELECT COUNT(banner_id) AS total_active FROM site_banner WHERE banner_is_active = 1";
        return $this->db->query($sql)->getRow('total_active');
    }

    function changeOrderMenu($order, $op)
    {
        $next_order = $op == 'up' ? $order - 1 : $order + 1;

        $sql_last_order = "
        SELECT
            MAX(banner_order_by) as last_order
        FROM site_banner
        ";

        $last_order = $this->db->query($sql_last_order)->getRow('last_order');

        if ($next_order == '0' || $next_order > $last_order) {
            throw new \Exception("Error Processing Request", 1);
        }

        $sql_current_id = "
        SELECT
            banner_id
        FROM site_banner
        WHERE banner_order_by = '$order'
        ";

        $current_id = $this->db->query($sql_current_id)->getRow('banner_id');

        $sql_next_id = "
        SELECT
            banner_id
        FROM site_banner
        WHERE banner_order_by = '$next_order'
        ";

        $next_id = $this->db->query($sql_next_id)->getRow('banner_id');

        $update_next_id = "
        UPDATE site_banner
        SET banner_order_by = '$next_order'
        WHERE banner_id = '$current_id'
        ";


        $this->db->query($update_next_id);

        $update_current_id = "
        UPDATE site_banner
        SET banner_order_by = '$order'
        WHERE banner_id = '$next_id'
        ";

        $this->db->query($update_current_id);
    }
}
