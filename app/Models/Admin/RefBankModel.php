<?php

namespace App\Models\Admin;

use CodeIgniter\Model;;

class RefBankModel extends Model
{

    public function removeBank($id)
    {
        $query = "DELETE FROM ref_bank WHERE bank_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function activeBank($id, $field)
    {
        $query = "UPDATE ref_bank SET bank_is_active = 1 WHERE " . $field . " = ?";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveBank($id, $field)
    {
        $query = "UPDATE ref_bank SET bank_is_active = 0 WHERE " . $field . " = ?";
        return $this->db->query($query, [$id]);
    }
}
