<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class InvestorModel extends Model
{
    public function getTotal($percentage)
    {
        $percentage = $percentage / 100;

        // ditambah 400000 karena root tidak transaksi
        $sql = "
        SELECT 
            COUNT(*) AS count,
            (transaction_total_price + 400000) * {$percentage} AS total_price_percent,
            transaction_total_price + 400000 AS total_price
        FROM sys_member
        JOIN (
            SELECT SUM(transaction_total_price) AS transaction_total_price FROM (
                SELECT SUM(stockist_transaction_total_price) AS transaction_total_price FROM inv_stockist_transaction
                WHERE stockist_transaction_status IN ('paid', 'complete', 'delivered', 'approved')
                AND stockist_transaction_buyer_type = 'member'
                UNION
                SELECT SUM(warehouse_transaction_total_price) AS transaction_total_price FROM inv_warehouse_transaction
                WHERE warehouse_transaction_status IN ('paid', 'complete', 'delivered', 'approved')
                AND warehouse_transaction_buyer_type = 'member'
            ) t3
        ) t4;
        ";

        return $this->db->query($sql)->getRow();
    }


    public function getTotalPending()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM report_investor_withdrawal
            WHERE investor_withdrawal_status = 'pending'
        ";

        return $this->db->query($sql)->getRow('total') ?? 0;
    }
}
