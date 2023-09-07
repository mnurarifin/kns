<?php

namespace App\Models;

use CodeIgniter\Model;

class Common_model extends Model
{
    public function getOne($table_name = '', $fieldname = null, $where = null, $fieldsort = null, $sort = 'asc')
    {
        $builder = $this->db->table($table_name, false);
        $builder->select($fieldname);
        if($where != null) {
            $builder->where($where);
        }
        if($fieldsort == null) {
            $fieldsort = $fieldname;
        }
        $builder->orderBy($fieldsort, $sort);
        $builder->offset(0);
        $builder->limit(1);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            $result = $row->$fieldname;
        } else {
            $result = '';
        }
        return $result;
    }

    public function getSum($table_name = '', $fieldname = null, $where = null)
    {
        $builder = $this->db->table($table_name);
        $builder->select("IFNULL(SUM(" . $fieldname . "), 0) AS " . $fieldname, false);
        if($where != null) {
            $builder->where($where);
        }
        $query = $builder->get();
        $row = $query->getRow();
        return $row->$fieldname;
    }

    public function getCount($table_name = '', $fieldname = null, $where = null)
    {
        $builder = $this->db->table($table_name);
        $builder->select("IFNULL(COUNT(1), 0) AS " . $fieldname, false);
        if($where != null) {
            $builder->where($where);
        }
        $query = $builder->get();
        $row = $query->getRow();
        return $row->$fieldname;
    }

    public function getMax($table_name = '', $fieldname = null, $where = null)
    {
        $builder = $this->db->table($table_name);
        $builder->select("IFNULL(MAX(" . $fieldname . "), 0) AS " . $fieldname, false);
        if($where != null) {
            $builder->where($where);
        }
        $query = $builder->get();
        $row = $query->getRow();
        return $row->$fieldname;
    }

    public function getMin($table_name = '', $fieldname = null, $where = null)
    {
        $builder = $this->db->table($table_name);
        $builder->select("IFNULL(MIN(" . $fieldname . "), 0) AS " . $fieldname, false);
        if($where != null) {
            $builder->where($where);
        }
        $query = $builder->get();
        $row = $query->getRow();
        return $row->$fieldname;
    }

    public function getDetail($table_name, $fieldname, $value_id)
    {
        $builder = $this->db->table($table_name);
        $builder->select('*');
        $builder->where($fieldname, $value_id);
        return $builder->get()->getRow();
    }

    public function lastId()
    {
        $query = $this->db->query('SELECT LAST_INSERT_ID() AS last_insert_id');
        $row = $query->row();
        return $row->last_insert_id;
    }

    public function insertData($table_name, $data)
    {
        $builder = $this->db->table($table_name);
        $builder->set($data);
        $builder->insert();
        if ($this->db->affectedRows() <= 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateData($table_name, $fieldname, $value_id, $data)
    {
        $builder = $this->db->table($table_name);
        $builder->where($fieldname, $value_id);
        $builder->update($data);
        if ($this->db->affectedRows() <= 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function deleteData($table_name, $fieldname, $value_id)
    {
        $builder = $this->db->table($table_name);
        $builder->where($fieldname, $value_id);
        $builder->delete();
        if ($this->db->affectedRows() < 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function usernameToNetworkCode($username)
    {
        $sql = "SELECT network_code
        FROM sys_network
        JOIN sys_member_account ON member_account_member_id = network_member_id
        WHERE member_account_username = '{$username}'
        ";
        $network_code = $this->db->query($sql)->getRow("network_code");
        if (is_null($network_code)) {
            throw new \Exception("Jaringan tidak ditemukan", 1);
        }

        return $network_code;
    }

    public function usernameToMemberId($username)
    {
        $sql = "SELECT network_member_id
        FROM sys_network
        JOIN sys_member_account ON member_account_member_id = network_member_id
        WHERE member_account_username = '{$username}'
        ";
        $network_member_id = $this->db->query($sql)->getRow("network_member_id");
        if (is_null($network_member_id)) {
            throw new \Exception("Jaringan tidak ditemukan", 1);
        }

        return $network_member_id;
    }
}
