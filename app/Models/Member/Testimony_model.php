<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Testimony_model extends Model
{
    public function init($request = null)
    {
        $this->request = $request;
    }

    public function add($data)
    {
        $this->db->table('site_testimony')->insert($data);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal tambah testimoni", 1);
        }

        return true;
    }
}
