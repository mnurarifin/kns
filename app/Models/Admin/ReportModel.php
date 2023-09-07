<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ReportModel extends Model
{

    public function getDetailSerialReport($date, $member_id, $type)
    {
        $data = $this->db->table($type . '_serial')->select('serial_pin, serial_id')->getWhere([
            'serial_buy_datetime' => $date,
            'serial_buyer_member_id' => $member_id,
        ])->getResult();

        return $data;
    }

    public function getDetailSerialRo($sales_personal_id)
    {
        $sql = "
        SELECT 
            sales_group_id,
            sales_group_sales_personal_id,
            sales_group_level,
            sales_group_network_id,
            r1.member_ref_network_code AS sales_group_network_code,
            m1.member_name AS sales_group_member_name,
            sales_group_line_network_id, 
            r2.member_ref_network_code AS sales_group_line_network_code,
            m2.member_name AS sales_group_line_member_name,
            IF(sales_group_level <= 15, 1000, 2000) AS sales_group_bonus
        FROM bin_sales_group
        JOIN bin_member_ref r1 ON r1.member_ref_network_id = sales_group_network_id
        JOIN sys_member m1 ON r1.member_ref_member_id = m1.member_id
        JOIN bin_member_ref r2 ON r2.member_ref_network_id = sales_group_line_network_id
        JOIN sys_member m2 ON r2.member_ref_member_id = m2.member_id
        WHERE sales_group_sales_personal_id = '{$sales_personal_id}'
        ORDER BY sales_group_level ASC
        ";

        return $this->db->query($sql)->getResult();
    }

    public function getHistorySerialReport($type, $field, $search = [], $limit, $page, $filter, $sort, $dir)
    {
        $start = ($page - 1) * $limit;
        $sqlNumRows = "SELECT COUNT(*) as total FROM " . $type . "_serial_transfer_log
        LEFT JOIN sys_member ON serial_transfer_log_serial_buyer_member_id = member_id
        LEFT JOIN bin_member_ref ON member_ref_member_id = member_id
        WHERE
        serial_transfer_log_id IN(
        SELECT
        MAX(serial_transfer_log_id)
        FROM
        " . $type . "_serial_transfer_log
        WHERE 1
        " . $search['sqlSearch'] . "
        GROUP BY
        serial_transfer_log_serial_id) ";
        $results['count'] = $this->db->query($sqlNumRows, $search['sqlSearchValue'])->getRow()->total;
        $query = "SELECT $field
        FROM " . $type . "_serial_transfer_log
        LEFT JOIN sys_member ON serial_transfer_log_serial_buyer_member_id = member_id
        LEFT JOIN bin_member_ref ON member_ref_member_id = member_id
        WHERE
        serial_transfer_log_id IN (
        SELECT
        MAX(serial_transfer_log_id)
        FROM
        " . $type . "_serial_transfer_log
        WHERE 1
        " . $search['sqlSearch'] . "
        GROUP BY
        serial_transfer_log_serial_id
        ) 
        ORDER BY $sort $dir LIMIT $start, $limit";
        $results['data'] = $this->db->query($query, $search['sqlSearchValue'])->getResultArray();

        return $results;
    }

    public function getSummaryRoyalty($year)
    {

        $sql_ewallet_serial_log = "
            SELECT 
            YEAR(ewallet_serial_log_datetime) AS tahun, 
            MONTH(ewallet_serial_log_datetime) as bulan,
            (
                SELECT
                    IFNULL(SUM(ewallet_serial_log_value),0)
                FROM sys_ewallet_serial_log
                WHERE ewallet_serial_log_type = 'in'
                AND month(ewallet_serial_log_datetime) = bulan
                AND YEAR(ewallet_serial_log_datetime) = tahun
            ) AS masuk,
            (
                SELECT
                    IFNULL(SUM(ewallet_serial_log_value),0)
                FROM sys_ewallet_serial_log
                WHERE ewallet_serial_log_type = 'out'
                AND month(ewallet_serial_log_datetime) = bulan
                AND YEAR(ewallet_serial_log_datetime) = tahun
            ) AS keluar
        FROM sys_ewallet_serial_log 
        WHERE YEAR(ewallet_serial_log_datetime) = '$year' 
        GROUP BY YEAR(ewallet_serial_log_datetime), 
        MONTH(ewallet_serial_log_datetime)
        ";


        return $this->db->query($sql_ewallet_serial_log)->getResult();
    }

    public function getSummaryRoyaltyLastMonth($last_year)
    {
        $sql_ewallet_serial_log = "
            SELECT 
                YEAR(ewallet_serial_log_datetime) AS tahun, 
                MONTH(ewallet_serial_log_datetime) as bulan,
                (
                    SELECT
                        IFNULL(SUM(ewallet_serial_log_value),0)
                    FROM sys_ewallet_serial_log
                    WHERE ewallet_serial_log_type = 'in'
                    AND month(ewallet_serial_log_datetime) = bulan
                    AND YEAR(ewallet_serial_log_datetime) = tahun
                ) -
                (
                    SELECT
                        IFNULL(SUM(ewallet_serial_log_value),0)
                    FROM sys_ewallet_serial_log
                    WHERE ewallet_serial_log_type = 'out'
                    AND month(ewallet_serial_log_datetime) = bulan
                    AND YEAR(ewallet_serial_log_datetime) = tahun
                ) AS saldo
                FROM sys_ewallet_serial_log 
                WHERE YEAR(ewallet_serial_log_datetime) = '{$last_year}'
                AND MONTH(ewallet_serial_log_datetime) = '12'
            GROUP BY YEAR(ewallet_serial_log_datetime),
            MONTH(ewallet_serial_log_datetime)
        ";

        return $this->db->query($sql_ewallet_serial_log)->getRow('saldo');
    }

    public function getMonthRoyalty($month = '', $year = '')
    {
        $where_in = "";
        $where_out = "";
        if ($month != '' && $year != '') {
            $where_out = " AND MONTH(t.serial_used_datetime) = '{$month}' AND YEAR(t.serial_used_datetime) = '{$year}'";
            $where_in = " AND YEAR(comission_log_datetime) = '{$year}' AND MONTH(comission_log_datetime) = '{$month}'";
        }

        $sql_out = "
        SELECT 
            IFNULL(SUM(t.serial_stockist_serial_price),0) AS total
        FROM(
        SELECT
            serial_stockist_serial_id,
            serial_stockist_serial_price,
            serial_used_datetime,
            serial_is_used
        FROM bin_serial_stockist
        JOIN bin_serial ON serial_id = serial_stockist_serial_id
        UNION
        SELECT
            serial_stockist_serial_id,
            serial_stockist_serial_price,
            serial_used_datetime,
            serial_is_used
        FROM sys_serial_stockist
        JOIN sys_serial ON serial_id = serial_stockist_serial_id
        ) t
        WHERE t.serial_is_used = '1' 
        {$where_out}
        ";

        $result_out = $this->db->query($sql_out)->getRow('total') * (5 / 100) ?? 0;

        $sql_in = "
        SELECT 
            IFNULL(SUM(comission_log_value),0) AS total
        FROM sys_comission_log
        WHERE comission_log_type = 'in'
        {$where_in}
        GROUP BY MONTH(comission_log_datetime)
        ";

        $result_in = $this->db->query($sql_in)->getRow('total') ?? 0;

        $data = new \stdClass;
        $data->in = $result_in;
        $data->out = $result_out;

        return $data;
    }

    public function getLastRoyalty($year)
    {
        # code...
        $sql_ewallet_paid = "
        SELECT 
            IFNULL(SUM(t.serial_stockist_serial_price),0) AS total
        FROM(
        SELECT
            serial_stockist_serial_id,
            serial_stockist_serial_price,
            serial_used_datetime,
            serial_is_used
        FROM bin_serial_stockist
        JOIN bin_serial ON serial_id = serial_stockist_serial_id
        UNION
        SELECT
            serial_stockist_serial_id,
            serial_stockist_serial_price,
            serial_used_datetime,
            serial_is_used
        FROM sys_serial_stockist
        JOIN sys_serial ON serial_id = serial_stockist_serial_id
        ) t
        WHERE t.serial_is_used = '1' 
        AND YEAR(t.serial_used_datetime) < '{$year}'
        ";

        $result_out = $this->db->query($sql_ewallet_paid)->getRow('serial_type_price') * (5 / 100) ?? 0;

        $sql_in = "
        SELECT 
            IFNULL(SUM(comission_log_value),0) AS total
        FROM sys_comission_log
        WHERE YEAR(comission_log_datetime) < $year 
        AND comission_log_type = 'in'
        ";

        $result_in = $this->db->query($sql_in)->getRow('total') ?? 0;

        return $result_in - $result_out;
    }

    public function getYearByDataRoyalty()
    {
        $sql_komisi_it_year = "
        SELECT 
            YEAR(serial_used_datetime) AS tahun
        FROM(
        SELECT
         serial_used_datetime,
         serial_is_used
        FROM bin_serial_stockist
        JOIN bin_serial ON serial_id = serial_stockist_serial_id
        UNION
        SELECT
         serial_used_datetime,
         serial_is_used
        FROM sys_serial_stockist
        JOIN sys_serial ON serial_id = serial_stockist_serial_id
        ) t
       WHERE t.serial_is_used = '1' 
       GROUP BY tahun
        ";

        return $this->db->query($sql_komisi_it_year)->getResult();
    }

    public function detailHistorySerialReport($serial, $type)
    {
        $data['serial'] = $this->db->table($type . '_serial')
            ->select('member_name, member_ref_network_code, ' . $type . '_serial.*')
            ->join('sys_member', 'serial_used_member_id = member_id', 'left')
            ->join('bin_member_ref', 'member_ref_member_id = member_id', 'left')
            ->getWhere([
                'serial_id' => $serial
            ])->getRow();

        $data['log'] = $this->db->table($type . '_serial_transfer_log')
            ->select('serial_transfer_log_serial_buy_datetime, member_ref_network_code, member_name')
            ->orderBy('serial_transfer_log_id', 'ASC')
            ->join('sys_member', 'serial_transfer_log_serial_buyer_member_id = member_id')
            ->join('bin_member_ref', 'member_ref_member_id = member_id')
            ->getWhere([
                'serial_transfer_log_serial_id' => $serial
            ])->getResult();
        return $data;
    }

    public function updateKomisi($nominal)
    {
        $sql_ewallet_serial = "
        UPDATE sys_comission
        SET comission_paid = comission_paid + {$nominal}
        ";

        $this->db->query($sql_ewallet_serial);

        return $this->db->affectedRows() == 0 ? FALSE : TRUE;
    }

    public function getSaldoRoyalty()
    {
        $sql_out = "
        SELECT 
            IFNULL(SUM(t.serial_stockist_serial_price),0) AS total
        FROM(
        SELECT
            serial_stockist_serial_id,
            serial_stockist_serial_price,
            serial_used_datetime,
            serial_is_used
        FROM bin_serial_stockist
        JOIN bin_serial ON serial_id = serial_stockist_serial_id
        UNION
        SELECT
            serial_stockist_serial_id,
            serial_stockist_serial_price,
            serial_used_datetime,
            serial_is_used
        FROM sys_serial_stockist
        JOIN sys_serial ON serial_id = serial_stockist_serial_id
        ) t
        WHERE t.serial_is_used = '1' 
        ";

        $result_out = $this->db->query($sql_out)->getRow('total') * (5 / 100) ?? 0;

        $sql_in = "
        SELECT 
            IFNULL(SUM(comission_log_value),0) AS total
            FROM sys_comission_log
            WHERE comission_log_type = 'in'
        ";

        $result_in = $this->db->query($sql_in)->getRow('total') ?? 0;

        $data = new \stdClass;
        $data->in = $result_in;
        $data->out = $result_out;

        return $data;
    }
}
