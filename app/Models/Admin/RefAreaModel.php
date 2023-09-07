<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class RefAreaModel extends Model
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

    public function removeProvince($id)
    {
        $is_error = true;
        $this->transBegin();
        try {
            $query = "DELETE FROM ref_province WHERE province_id = ?";
            $this->query($query, [$id]);
            if ($this->affectedRows() <= 0) {
                $is_error = false;
            }
        } catch (\Throwable $th) {
            $is_error = false;
        }
        if (!$is_error) {
            $this->transRollback();
        } else {
            if ($this->transStatus() === FALSE) {
                $is_error = false;
                $this->transRollback();
            } else {
                $this->transCommit();
            }
        }
        return $is_error;
    }

    public function removeCity($id)
    {
        $is_error = true;
        $this->transBegin();
        try {
            $query = "DELETE FROM ref_city WHERE city_id = ?";
            $this->query($query, [$id]);
            if ($this->affectedRows() <= 0) {
                $is_error = false;
            }
        } catch (\Throwable $th) {
            $is_error = false;
        }
        if (!$is_error) {
            $this->transRollback();
        } else {
            if ($this->transStatus() === FALSE) {
                $is_error = false;
                $this->transRollback();
            } else {
                $this->transCommit();
            }
        }
        return $is_error;
    }

    public function removeSubdistrict($id)
    {
        $is_error = true;
        $this->transBegin();
        try {
            $query = "DELETE FROM ref_subdistrict WHERE subdistrict_id = ?";
            $this->query($query, [$id]);
            if ($this->affectedRows() <= 0) {
                $is_error = false;
            }
        } catch (\Throwable $th) {
            $is_error = false;
        }
        if (!$is_error) {
            $this->transRollback();
        } else {
            if ($this->transStatus() === FALSE) {
                $is_error = false;
                $this->transRollback();
            } else {
                $this->transCommit();
            }
        }
        return $is_error;
    }

    public function removeVillage($id)
    {
        $is_error = true;
        $this->transBegin();
        try {
            $query = "DELETE FROM ref_village WHERE village_id = ?";
            $this->query($query, [$id]);
            if ($this->affectedRows() <= 0) {
                $is_error = false;
            }
        } catch (\Throwable $th) {
            $is_error = false;
        }
        if (!$is_error) {
            $this->transRollback();
        } else {
            if ($this->transStatus() === FALSE) {
                $is_error = false;
                $this->transRollback();
            } else {
                $this->transCommit();
            }
        }
        return $is_error;
    }
}
