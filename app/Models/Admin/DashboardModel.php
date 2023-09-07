<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    function rangeRegistration($date)
    {
        $sql = "SELECT
                DATE(member_join_datetime) AS tanggal, count(member_id) AS total 
            FROM sys_member
            WHERE DATE(member_join_datetime) = '{$date}'
            GROUP BY DATE(member_join_datetime)
        ";
        return $this->db->query($sql)->getResult('array');
    }

    function rangeActivation($date)
    {
        $sql = "SELECT
                DATE(member_activation_datetime) AS tanggal, count(member_id) AS total 
            FROM sys_member
            WHERE DATE(member_activation_datetime) = '{$date}'
            GROUP BY DATE(member_activation_datetime)
        ";
        return $this->db->query($sql)->getResult('array');
    }

    function rangeRO($date)
    {
        // $sql = "SELECT
        //         DATE(serial_ro_used_datetime) AS tanggal, count(serial_ro_id) AS total 
        //     FROM sys_serial_ro
        //     WHERE DATE(serial_ro_used_datetime) = '{$date}'
        //     GROUP BY DATE(serial_ro_used_datetime)
        // ";
        $sql = "SELECT
        DATE(ro_personal_datetime) AS tanggal, count(ro_personal_id) AS total 
        FROM sys_ro_personal
        WHERE DATE(ro_personal_datetime) = '{$date}'
        GROUP BY DATE(ro_personal_datetime)
        ";
        return $this->db->query($sql)->getResult('array');
    }

    function totalBonus()
    {
        $sql = "SELECT
        SUM(bonus_sponsor_acc) AS bonus_sponsor,
        SUM(bonus_gen_node_acc) AS bonus_gen_node,
        SUM(bonus_power_leg_acc) AS bonus_power_leg,
        SUM(bonus_matching_leg_acc) AS bonus_matching_leg,
        SUM(bonus_cash_reward_acc) AS bonus_cash_reward,
        SUM(bonus_sponsor_acc) + SUM(bonus_gen_node_acc) + SUM(bonus_power_leg_acc) + SUM(bonus_matching_leg_acc) + SUM(bonus_cash_reward_acc)AS bonus_total
        FROM sys_bonus
        ";
        return $this->db->query($sql)->getRow();
    }

    function rangeBonus($date)
    {
        $sql = "SELECT
        t.*,
        bonus_sponsor+
        bonus_gen_node+
        bonus_power_leg+
        bonus_matching_leg+
        bonus_cash_reward AS total
        FROM (
        SELECT
        SUM(bonus_log_sponsor) AS bonus_sponsor,
        SUM(bonus_log_gen_node) AS bonus_gen_node,
        SUM(bonus_log_power_leg) AS bonus_power_leg,
        SUM(bonus_log_matching_leg) AS bonus_matching_leg,
        SUM(bonus_log_cash_reward) AS bonus_cash_reward,
        bonus_log_date AS tanggal
        FROM sys_bonus_log
        WHERE bonus_log_date = '{$date}'
        GROUP BY bonus_log_date) t
        ";
        return $this->db->query($sql)->getResult('array');
    }

    // function sumBonusAll($type, $arrBonus)
    // {
    //     $selectBonus = '';
    //     foreach ($arrBonus as $bonus) {
    //         $selectBonus .= "SUM(bonus_{$bonus}_acc) AS `{$bonus}`, ";
    //     }
    //     $selectBonus .= rtrim($selectBonus, ', ');

    //     $sql = "
    //         SELECT $selectBonus
    //         FROM " . $type . "_bonus
    //     ";
    //     return $this->db->query($sql)->getRow();
    // }

    // function payoutBonus($type)
    // {
    //     $sql = "SELECT
    //             SUM(bonus_transfer_adm_charge_value) AS total_cut, 
    //             SUM(bonus_transfer_nett) AS total_transfer 
    //         FROM {$type}_bonus_transfer 
    //         WHERE bonus_transfer_status = 'success'
    //     ";
    //     return $this->db->query($sql)->getRow();
    // }

    function countRegistration()
    {
        return $this->db->table('sys_member_plan_activity')->where("member_plan_activity_type = 'membership'")->countAll();
    }

    function countActivation()
    {
        $sum_warehouse = $this->db->table("inv_warehouse_transaction")->selectSum("warehouse_transaction_total_price")
            ->whereIn("warehouse_transaction_status", ['paid', 'approved', 'delivered', 'complete'])
            ->getWhere([
                "warehouse_transaction_buyer_type" => "member",
            ])->getRow('warehouse_transaction_total_price') ?? 0;

        $sum_stockist = $this->db->table("inv_stockist_transaction")->selectSum("stockist_transaction_total_price")
            ->whereIn("stockist_transaction_status", ['paid', 'approved', 'delivered', 'complete'])
            ->getWhere([
                "stockist_transaction_buyer_type" => "member",
            ])->getRow('stockist_transaction_total_price') ?? 0;

        return $sum_warehouse + $sum_stockist;
    }

    function countStockist()
    {
        $sum = $this->db->table("inv_warehouse_transaction")->selectSum("warehouse_transaction_total_price")
            ->whereIn("warehouse_transaction_status", ['paid', 'approved', 'delivered', 'complete'])
            ->getWhere([
                "warehouse_transaction_buyer_type" => "stockist",
            ])->getRow('warehouse_transaction_total_price');
        return $sum ?? 0;
    }

    function saldoStockist()
    {
        $data = $this->db->query("SELECT SUM(ewallet_acc) as acc, SUM(ewallet_paid) as paid FROM sys_ewallet")->getRow();
        return $data;
    }

    function countSaldoEwallet()
    {
        $sql = "SELECT
                ewallet_serial_fee,
                ewallet_serial_omset_month,
                ewallet_serial_acc AS total_acc,
                ewallet_serial_paid AS total_paid
            FROM sys_ewallet_serial
        ";
        return $this->db->query($sql)->getRow();
    }

    function countTotalSerial($type = 'all', $status = "")
    {
        $query = "";
        if ($type == "all") {
            $query = "SELECT *
            FROM sys_serial
            UNION
            SELECT * 
            FROM sys_serial_ro";
        }
        if ($type == "activation") {
            $query = "SELECT *
            FROM sys_serial";
        }
        if ($type == "repeatorder") {
            $query = "SELECT * 
            FROM sys_serial_ro";
        }
        $where = " WHERE 1 ";
        if ($status == 'active') {
            $where .= " AND serial_is_active = 1 ";
        }
        if ($status == 'used') {
            $where .= " AND serial_is_used = 1 ";
        }
        $sql = "SELECT * FROM (
        {$query}
        ) t {$where}
        ";
        return count($this->db->query($sql)->getResult());
    }

    function countSoldSerial($type)
    {
        $sql = "SELECT *
        FROM sys_serial
        WHERE serial_is_active = 1
        UNION
        SELECT * 
        FROM sys_serial_ro
        WHERE serial_ro_is_active = 1
        ";
        return count($this->db->query($sql)->getResult());
    }

    function countUsedSerial($type)
    {
        $builder = $this->db->table($type . '_serial');
        $builder->where('serial_is_used', 1);
        return $builder->countAllResults();
    }

    function sumRegist()
    {
        return $this->db->query("SELECT
        SUM(income_log_value) AS total_regist FROM report_income_log WHERE income_log_type = 'membership'
        ")->getRow("total_regist");
    }

    function sumRO()
    {
        return $this->db->query("SELECT
        SUM(income_log_value) AS total_ro FROM report_income_log WHERE income_log_type = 'repearorder'
        ")->getRow("total_ro");
    }

    function sumActivation()
    {
        return $this->db->query("SELECT
        SUM(income_log_value) AS total_ro FROM report_income_log WHERE income_log_type = 'activation'
        ")->getRow("total_activation");
    }

    public function sumTotalTransfer()
    {
        $sql_out = "SELECT sum(bonus_transfer_total) as total_out FROM sys_bonus_transfer";
        $total_out = $this->db->query($sql_out)->getRow()->total_out;

        return $total_out;
    }

    public function sumTotalKomisi()
    {
        $sql = "SELECT SUM(bonus_log_value) as total FROM sys_bonus_log WHERE bonus_log_type = 'in' AND bonus_log_note in ('Komisi dari cashback','Komisi dari pasangan','Komisi dari ro', 'Komisi dari ron', 'Komisi dari sponsor', 'Komisi dari unilevel')";

        return $this->db->query($sql)->getRow()->total;
    }

    function countOmzetMonth()
    {
        $sql = "SELECT IFNULL(sum(comission_log_value), 0) as commision_total FROM sys_comission_log where MONTH(comission_log_datetime) = MONTH(CURDATE())
        AND YEAR(comission_log_datetime) = YEAR(CURDATE()) AND comission_log_type = 'out'
        ";
        return $this->db->query($sql)->getRow()->commision_total;
    }

    public function getIncome($month, $year)
    {
        return $this->db->query("SELECT SUM(income_log_value) AS total FROM report_income_log WHERE YEAR(income_log_datetime) = '{$year}' AND MONTH(income_log_datetime) = '$month'")->getRow("total");
    }

    public function getRoyalty($month, $year)
    {
        return $this->db->query("SELECT royalty_fee_acc - royalty_fee_paid AS total FROM report_royalty_fee")->getRow("total");
    }
}
