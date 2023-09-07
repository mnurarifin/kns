<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SerialModel extends Model
{

    public function getStockistOption($like = '')
    {

        $data =  $this->db
            ->table('sys_stockist')
            ->select(
                'stockist_network_id,
                stockist_member_id,
                stockist_name,
                stockist_email,
                stockist_mobilephone,
                member_name,
                network_code,
                '
            )
            ->join('bin_network', 'network_id = stockist_network_id')
            ->join('sys_member', 'member_id = stockist_member_id')
            ->limit(10);

        if ($like) {
            $data->orlike('network_code', $like)
                ->orLike('member_name', $like)
                ->orLike('stockist_name', $like);
        }


        $result = $data->get()->getResult();

        return $result;
    }

    public function getSerialType($table = 'bin')
    {
        $data =  $this->db
            ->table("{$table}_serial_type")
            ->select(
                'serial_type_id,
                serial_type_name,
                serial_type_price'
            )
            ->get()
            ->getResult();

        return $data;
    }


    public function active($type, $id, $administrator_id)
    {
        $datetime = date('Y-m-d H:i:s');

        $sqlSerial = "SELECT serial_serial_type_id, serial_is_used, serial_expired_date, serial_active_ref_id
        FROM {$type}_serial
        WHERE serial_id = '{$id}'";
        $getAvailable = $this->db->query($sqlSerial)->getRow();

        if ($getAvailable->serial_is_used == 1 || $getAvailable->serial_active_ref_id == 0) {
            return false;
        }

        /*Update status serial*/
        $query = $this->db->table($type . '_serial')
            ->set('serial_active_datetime', $datetime)
            ->set('serial_is_active', 1)
            ->where(['serial_id' => $id]);
        return $query->update();
    }

    public function inactive($type, $id, $administrator_id)
    {
        $datetime = date('Y-m-d H:i:s');

        $sqlSerial = "SELECT serial_serial_type_id, serial_is_used, serial_expired_date, serial_active_ref_id
        FROM {$type}_serial
        WHERE serial_id = '{$id}'";
        $getAvailable = $this->db->query($sqlSerial)->getRow();

        if ($getAvailable->serial_is_used == 1 || $getAvailable->serial_active_ref_id == 0) {
            return false;
        }

        /*Update status serial*/
        $query = $this->db->table($type . '_serial')
            ->set('serial_active_datetime', $datetime)
            ->set('serial_is_active', 0)
            ->where(['serial_id' => $id]);
        return $query->update();
    }

    public function detailSerial($type, $serial)
    {
        $arr_result = array();
        $data = $this->db->table($type . '_serial')
            ->select('sys_serial.*,
            buyer_ref.network_code as buyer_network_code,
            buyer.member_name as buyer_name,
            user_ref.network_code as user_network_code,
            user.member_name as user_name,
            ')
            ->join('sys_network buyer_ref', 'buyer_ref.network_member_id = serial_active_ref_id', 'left')
            ->join('sys_member buyer', 'buyer.member_id = serial_active_ref_id', 'left')
            ->join('sys_network user_ref', 'user_ref.network_member_id = serial_used_member_id', 'left')
            ->join('sys_member user', 'user.member_id = serial_used_member_id', 'left')
            ->getWhere([
                'serial_id' => $serial,
            ])->getRowArray();
        if (!empty($data)) {
            $arr_result = $data;
        }

        return $arr_result;
    }

    public function getStockSerial($prefix_serial, $limit, $type_id = 0)
    {
        $table = $prefix_serial . '_serial';
        $whereCondition = 'serial_is_sold = 0 AND serial_is_used = 0';
        if ($type_id) {
            $whereCondition .= " AND serial_serial_type_id = $type_id";
        }
        // Check serial registrasi
        $sql = "
            SELECT
                serial_id,
                serial_pin,
                serial_network_code,
                serial_expired_date
            FROM $table
            WHERE {$whereCondition}
        ";

        $sql .= "  LIMIT $limit";

        $result = $this->db->query($sql)->getResult();

        return $result;
    }

    public function getStockSerialUpgrade($prefix_serial, $limit, $type_id = '', $type_name = '')
    {
        $table = $prefix_serial . '_serial';
        $whereCondition = 'serial_last_owner_status IS NULL';

        if ($type_id) {
            $whereCondition .= " AND serial_serial_type_id = $type_id ";
        }

        if ($type_name) {
            $whereCondition .= " AND serial_id LIKE '{$type_name}%'";
        }

        // Check serial registrasi
        $sql = "
            SELECT
                serial_id,
                serial_pin,
                serial_expired_date
            FROM $table
            WHERE {$whereCondition}
        ";

        $sql .= "  LIMIT $limit";

        $result = $this->db->query($sql)->getResult();

        return $result;
    }

    public function totalSerialPerusahaan($type)
    {
        $builder = $this->db->table($type . '_serial');
        $builder->where([
            'serial_is_used' => 0,
            'serial_is_sold' => 0,
        ]);
        return $builder->countAllResults();
    }

    public function getDetailSalesSerial($type, $serial_id)
    {
        $table = $type . "_serial_transfer_log";
        $type_serial = $type . "_serial";
        $sql = "
            SELECT serial_transfer_log_serial_buyer_member_id AS member_id,
            member_ref_network_code AS member_code,
            member_name,
            serial_transfer_log_serial_buy_datetime AS tanggal,
            IFNULL(serial_type_master_name,'') AS type
            FROM $table
            JOIN bin_member_ref ON serial_transfer_log_serial_buyer_member_id = member_ref_member_id
            JOIN sys_member ON member_ref_member_id = member_id
            LEFT JOIN $type_serial ON serial_transfer_log_serial_id = serial_id
            LEFT JOIN bin_serial_type ON serial_serial_type_id = serial_type_id
            WHERE serial_transfer_log_serial_id = '$serial_id' AND serial_transfer_log_status = 'masuk'
            ORDER BY serial_transfer_log_serial_buy_datetime DESC
        ";
        $data = $this->db->query($sql)->getResult();
        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }
    }
}
