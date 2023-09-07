<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    public function gallery_category_option()
    {
        $options = array();
        $sql = "SELECT gallery_category_id, gallery_category_title FROM site_gallery_category ORDER BY gallery_category_title ASC";
        $query = $this->db->query($sql)->getResult();
        if (!empty($query)) {
            $i = 0;
            foreach ($query as $row) {
                $options[$i]['title'] = $row->gallery_category_title;
                $options[$i]['value'] = $row->gallery_category_id;
                $i++;
            }
        }
        return $options;
    }
}
