<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Komisi extends BaseController
{

    public function bonus_member()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Komisi Mitra' => 'bonus/bonus_member',
        );

        $this->template->title('Komisi Mitra');
        $this->template->content('Admin/komisiBonusMemberView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function transfer()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Transfer Komisi' => 'bonus/transfer',
        );

        $this->template->title('Transfer Komisi');
        $this->template->content('Admin/komisiTransferView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function history_transfer()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Riwayat Transfer' => 'bonus/transfer_history',
        );

        $this->template->title('Riwayat Transfer');
        $this->template->content('Admin/komisiTransferHistoryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function excelSummaryHistoryTransfer()
    {
        $tableHead = array(
            'Tanggal Transfer',
            'Total Komisi',
            'Total Admin',
            'Total Pajak',
            'Total Transfer',
            'Komisi Sponsor',
            'Komisi Generasi',
            'Komisi Power Leg',
            'Komisi Matching Leg',
            'Komisi Cash Reward',
        );

        $tableAlign = json_decode($this->request->getPost('align'));

        $tableName = 'sys_bonus_transfer';
        $columns = array(
            'bonus_transfer_datetime',
            'SUM(bonus_transfer_adm_charge_value)' => 'bonus_transfer_adm_charge_value',
            'SUM(bonus_transfer_tax_value)' => 'bonus_transfer_tax_value',
            'SUM(bonus_transfer_total)' => 'bonus_transfer_total',
            'SUM(bonus_transfer_sponsor)' => 'bonus_transfer_sponsor',
            'SUM(bonus_transfer_gen_node)' => 'bonus_transfer_gen_node',
            'SUM(bonus_transfer_power_leg)' => 'bonus_transfer_power_leg',
            'SUM(bonus_transfer_matching_leg)' => 'bonus_transfer_matching_leg',
            'SUM(bonus_transfer_cash_reward)' => 'bonus_transfer_cash_reward',
        );
        $joinTable = '';
        $whereCondition = '';
        $groupBy = 'GROUP BY bonus_transfer_datetime';

        $request['columns'] = $columns;
        $request['filter'] = (array) $this->request->getGet('filter');
        $request['dir'] = 'DESC';
        $request['sort'] = 'bonus_transfer_datetime';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition, 1000, $groupBy);

        if (!empty($results)) {
            foreach ($results as $key => $value) {
                array_splice($results[$key], 1, 0, ['bonus_transfer_subtotal' => $value['bonus_transfer_total'] + $value['bonus_transfer_adm_charge_value'] + $value['bonus_transfer_tax_value']]);
            }
        }

        $title = "Daftar Riwayat Transfer Komisi";

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelDataHistoryTransfer($date)
    {
        $tableHead = array(
            'Nama Mitra',
            'Nama Rekening',
            'Nama Bank',
            'No. Rekening',
            'Nominal Komisi',
            'Biaya Admin',
            'Nominal Transfer',
            'Komisi Sponsor',
            'Komisi Generasi',
            'Komisi Power Leg',
            'Komisi Matching Leg',
            'Komisi Cash Reward',
        );

        $tableName = 'sys_bonus_transfer';
        $columns = array(
            'member_name',
            'bonus_transfer_member_bank_account_name',
            'bonus_transfer_member_bank_name',
            'bonus_transfer_member_bank_account_no',
            'bonus_transfer_adm_charge_value',
            'bonus_transfer_total',
            'bonus_transfer_sponsor',
            'bonus_transfer_gen_node',
            'bonus_transfer_power_leg',
            'bonus_transfer_matching_leg',
            'bonus_transfer_cash_reward',
        );
        $joinTable = 'JOIN sys_member ON member_id = bonus_transfer_member_id';
        $whereCondition = '';
        if ($date != '') {
            $whereCondition .= 'bonus_transfer_datetime = "' . $date . '"';
        }
        $groupBy = '';

        $request['columns'] = $columns;
        $request['filter'] = (array) $this->request->getGet('filter');
        $request['dir'] = 'DESC';
        $request['sort'] = 'bonus_transfer_datetime';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition, 1000, $groupBy);
        if (!empty($results)) {
            foreach ($results as $key => $value) {
                array_splice($results[$key], 4, 0, ['bonus_transfer_subtotal' => $value['bonus_transfer_total'] + $value['bonus_transfer_adm_charge_value']]);
            }
        }

        $title = "Daftar Riwayat Transfer Komisi Tanggal " . $date;

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
    }
}
