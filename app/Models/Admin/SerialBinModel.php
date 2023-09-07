<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SerialBinModel extends Model
{

    public function activeSerial($id)
    {
        $this->transBegin();
        $is_error = true;
        try {
            $query = "
            UPDATE bin_serial
            SET serial_is_active = 1
            WHERE serial_pin = ?
            ";
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
            if ($this->transStatus() === false) {
                $is_error = false;
                $this->transRollback();
            } else {
                $this->transCommit();
            }
        }
        return $is_error;
    }

    public function nonActiveSerial($id)
    {
        $this->transBegin();
        $is_error = true;
        try {
            $query = "
            UPDATE bin_serial
            SET serial_is_active = 0
            WHERE serial_pin = ?
            ";
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
            if ($this->transStatus() === false) {
                $is_error = false;
                $this->transRollback();
            } else {
                $this->transCommit();
            }
        }
        return $is_error;
    }
}
