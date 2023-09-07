<?php

namespace App\Models\Pub;

use CodeIgniter\Model;

class Image_model extends Model
{
    public function getImage($where, $type)
    {
        $sql = "
        SELECT * 
        FROM site_gallery 
        JOIN site_gallery_category ON gallery_gallery_category_id = gallery_category_id
        WHERE gallery_gallery_category_id = {$where}
        ";
        $image = $this->db->query($sql);
        if ($image != null) {
            if ($type == 'single') {
                $image = $image->getRow();
                if ($image != null) {
                    $image = UPLOAD_URL.URL_IMG_GALLERY.$image->gallery_file;
                } else {
                    $image = UPLOAD_URL.URL_IMG_GALLERY.'_default.png';
                }
            } else {
                $image = $image->getResult();
                if (!empty($image)) {
                    foreach ($image as $key => $val) {
                        $image[$key] = UPLOAD_URL.URL_IMG_GALLERY.$val->gallery_file;
                    }
                } else {
                    $image = UPLOAD_URL.URL_IMG_GALLERY.'_default.png';
                }
            }
        } else {
            $image = '';
        }

        return $image;
    }
}