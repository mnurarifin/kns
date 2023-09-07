<?php

namespace Validations;

class MlmValidations
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function check_network_code($network_code, $params)
    {
        $arr_params = explode(',', $params);
        if ($arr_params[1] == 'uni') {
            $table = 'uni_network';
        }
        if ($arr_params[1] == 'bin') {
            $table = 'bin_network';
        }
        $res = $this->db->table($table)
            ->select('network_code')
            ->getWhere([
                'network_code' => $network_code,
                'network_type' => $arr_params[2],
                'network_is_active' => 1,
                'network_is_suspended' => 0,
            ])
            ->getRow('network_code');
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function check_crossline($upline_code, $params = null)
    {
        $upline_id = $this->db->table(NET_TYPE . "_network")->getWhere(['network_code' => $upline_code])->getRow('network_id');
        $sponsor_code = explode(',', $params)[1];
        $sponsor_id = $this->db->table(NET_TYPE . "_network")->getWhere(['network_code' => $sponsor_code])->getRow('network_id');

        if ($upline_id == null || $sponsor_id == null) {
            return false;
        }

        if ($upline_id == $sponsor_id) {
            return true;
        }

        $get_arr_upline = $this->db->table(NET_TYPE . "_network_upline")
            ->select('network_upline_data_upline')
            ->getWhere(['network_upline_network_id' => $upline_id])
            ->getRow('network_upline_data_upline');
        if (!empty($get_arr_upline)) {
            $not_crosline = false;
            foreach (json_decode($get_arr_upline) as $value) {
                if ($value->id == $sponsor_id) {
                    $not_crosline = true;
                }
            }
            return $not_crosline;
        } else {
            return true;
        }

        return $not_crosline;
    }

    public function check_upline($position, $params)
    {
        $res = false;
        $position = ($position == 'L') ? 'left' : 'right';
        $upline_code = explode(',', $params)[1];
        $network = $this->db->table(NET_TYPE . "_network")->getWhere([
            'network_code' => $upline_code,
        ])->getRow("network_{$position}_node_network_id");
        if ($network == '0') {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial($serial_id, $params)
    {
        $res = false;
        $serial = $this->db->table('sys_serial')->whereIn('serial_serial_type_id', ['1', '2'])->getWhere([
            'serial_id' => $serial_id,
        ])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_upgrade($serial_id, $params)
    {
        $res = false;
        $serial = $this->db->table('sys_serial')->whereIn('serial_serial_type_id', ['3'])->getWhere([
            'serial_id' => $serial_id,
        ])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_active($serial_id, $params)
    {
        $res = false;
        $serial = $this->db->table('sys_serial')->getWhere([
            'serial_id' => $serial_id,
            'serial_is_active' => '1',
        ])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_bought($serial_id, $params)
    {
        $res = false;
        $serial = $this->db->table('sys_serial')->getWhere([
            'serial_id' => $serial_id,
            'serial_is_bought' => '1',
        ])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_used($serial_id, $params)
    {
        $res = false;
        $serial = $this->db->table('sys_serial')->getWhere([
            'serial_id' => $serial_id,
            'serial_is_used' => '0',
        ])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_pin($serial_pin, $params)
    {
        $res = false;
        $serial_id = explode(',', $params)[1];
        $serial = $this->db->table('sys_serial')->getWhere(['serial_id' => $serial_id, 'serial_pin' => $serial_pin])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_owned($serial_id, $params)
    {
        $res = false;
        $member_id = explode(',', $params)[1];
        $serial = $this->db->table('sys_serial_member')->getWhere(['serial_member_serial_id' => $serial_id, 'serial_member_serial_buyer_member_id' => $member_id])->getRow('serial_member_serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_membership_serial_send($serial_id, $params)
    {
        $res = false;
        $value = explode(',', $params)[1];
        $serial = $this->db->table('sys_serial')->getWhere(['serial_id' => $serial_id, 'serial_is_buyed' => $value])->getRow('serial_id');
        if ($serial) {
            $res = true;
        }
        return $res;
    }

    public function check_network_serial($serial_id, $params = null)
    {
        $res = false;
        $arr_params = explode(', ', $params);
        $member_id = $arr_params[0];
        $type = $arr_params[1];
        if ($type == 'uni') {
            $table = 'uni_serial_member_stock';
        }
        if ($type == 'bin') {
            $table = 'bin_serial_member_stock';
        }

        if ($serial_id == null) {
            $res = false;
        } else {
            $data_serial = $this->db->table($table)
                ->select('serial_member_stock_serial_id, serial_member_stock_expired_date')
                ->getWhere([
                    'serial_member_stock_serial_id' => $serial_id,
                    'serial_member_stock_member_id' => $member_id,
                ])->getRow();
            if (empty($data_serial)) {
                $res = false;
            } else {
                if ($data_serial->serial_member_stock_expired_date != null || !empty($data_serial->serial_member_stock_expired_date)) {
                    if (date('Y-m-d') < $data_serial->serial_member_stock_expired_date) {
                        $res = true;
                    } else {
                        $res = false;
                    }
                } else {
                    $res = true;
                }
            }
        }
        return $res;
    }

    public function check_identity($identity = null, $params = null)
    {
        if ($identity == null) {
            $res = false;
        } else {
            $sql = "
                SELECT member_identity_no
                FROM sys_member
                WHERE member_identity_no = '" . $identity . "' AND member_is_expired = 1 AND member_is_network = 0
            ";
            $data = $this->db->query($sql)->getRow('member_identity_no');
            if (empty($data)) {
                $res = true;
            } else {
                $res = false;
            }
        }
        return $res;
    }

    public function check_email($email = null, $params = null)
    {
        if ($email == null) {
            $res = false;
        } else {
            $sql = "
                SELECT member_account_username
                FROM sys_member_account
                WHERE member_account_username = '" . $email . "'
            ";
            $data = $this->db->query($sql)->getRow('member_account_username');
            if (empty($data)) {
                $res = true;
            } else {
                $res = false;
            }
        }
        return $res;
    }

    public function check_mobilephone($mobilephone, $member_id)
    {
        $where = ['member_mobilephone' => phoneNumberFilter($mobilephone), 'member_status <>' => '3'];
        if ($member_id) {
            $member_parent_member_id = $this->db->query("SELECT * FROM sys_member WHERE member_id = '{$member_id}'")->getRow("member_parent_member_id");
            $where = array_merge($where, ['member_parent_member_id <>' => $member_parent_member_id]);
        }
        $member = $this->db->table('sys_member')->getWhere($where)->getRow();
        if (!empty($member)) {
            return false;
        } else {
            return true;
        }
    }

    public function check_bank_by_id($id)
    {
        $sql = "SELECT bank_id FROM ref_bank WHERE bank_id = '{$id}' AND bank_is_active = 1";
        $data = $this->db->query($sql)->getRow('bank_id');
        if (empty($data)) {
            $res = false;
        } else {
            $res = true;
        }
    }

    public function check_bank_by_name($bank_name)
    {
        $sql = "SELECT bank_id FROM ref_bank WHERE bank_name = '{$bank_name}' AND bank_is_active = 1";
        $data = $this->db->query($sql)->getRow('bank_id');
        if (empty($data)) {
            $res = false;
        } else {
            $res = true;
        }
    }

    public function check_old_password($field, $params)
    {
        $params = explode(',', $params);

        $builder = $this->db->table('sys_member_account');
        $where = "member_account_member_id = " . $params[1];
        $password_check = $builder->select('member_account_password')
            ->getWhere("{$where}")
            ->getRow('member_account_password');

        if (!password_verify($params[0], $password_check)) {
            return false;
        }

        return true;
    }
}
