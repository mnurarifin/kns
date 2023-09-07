<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class TestimonyModel extends Model
{

    public function removeTesty($id)
    {
        $query = "DELETE FROM site_testimony WHERE testimony_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function activeTesty($id)
    {
        $query = "UPDATE site_testimony SET testimony_is_publish = 1 WHERE testimony_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveTesty($id)
    {
        $query = "UPDATE site_testimony SET testimony_is_publish = 0 WHERE testimony_id = ?";
        return $this->db->query($query, [$id]);
    }
}
