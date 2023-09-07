<?php

namespace App\Models\Admin;

use CodeIgniter\Model;;

class RefCountryModel extends Model
{

    public function removeCountry($id)
    {
        $query = "DELETE FROM ref_country WHERE bank_id = ?";
        return $this->query($query, [$id]);
    }

    public function activeCountry($id, $field)
    {
        $query = "UPDATE ref_country SET country_is_active = '1' WHERE " . $field . " = ?";
        return $this->query($query, [$id]);
    }

    public function nonActiveCountry($id, $field)
    {
        $query = "UPDATE ref_country SET country_is_active = '0' WHERE " . $field . " = ?";
        return $this->query($query, [$id]);
    }
}
