<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;

class Saldo extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->dataTable = Services::DataTableLib();
    }

    public function getDataSaldo()
    {
        $tableName = 'sys_ewallet';
        $columns = array(
            'ewallet_member_id',
            'ewallet_acc',
            'ewallet_paid',
            'ewallet_acc-ewallet_paid AS ewallet_balance',
            'member_name' => 'ewallet_member_name',
        );
        $joinTable = 'JOIN sys_member ON member_id = ewallet_member_id';
        $whereCondition = '';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                $data['results'][$key]['ewallet_acc_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_acc'], 2);
                $data['results'][$key]['ewallet_paid_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_paid'], 2);
                $data['results'][$key]['ewallet_balance_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_balance'], 2);
            }
        }

        $this->createRespon(200, 'Data Saldo', $data);
    }

    public function getDataTransferHistory()
    {
        $tableName = 'sys_ewallet_withdrawal';
        $columns = array(
            'ewallet_withdrawal_id',
            'ewallet_withdrawal_member_id',
            'ewallet_withdrawal_member_name',
            'ewallet_withdrawal_value',
            'ewallet_withdrawal_adm_charge_percent',
            'ewallet_withdrawal_adm_charge_value',
            'ewallet_withdrawal_subtotal',
            'ewallet_withdrawal_tax_percent',
            'ewallet_withdrawal_tax_value',
            'ewallet_withdrawal_nett',
            'ewallet_withdrawal_bank_id',
            'ewallet_withdrawal_bank_name',
            'ewallet_withdrawal_bank_account_name',
            'ewallet_withdrawal_bank_account_no',
            'ewallet_withdrawal_note',
            'ewallet_withdrawal_datetime',
            'ewallet_withdrawal_status',
            'ewallet_withdrawal_status_administrator_id',
            'ewallet_withdrawal_status_datetime',
        );
        $joinTable = '';
        $whereCondition = "ewallet_withdrawal_status <> 'pending'";

        $groupBy = 'GROUP BY ewallet_withdrawal_status_datetime';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                $data['results'][$key]['ewallet_withdrawal_value_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_withdrawal_value'], 2);
                $data['results'][$key]['ewallet_withdrawal_nett_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_withdrawal_nett'], 2);
                $data['results'][$key]['ewallet_withdrawal_subtotal_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_withdrawal_subtotal'], 2);
                $data['results'][$key]['ewallet_withdrawal_adm_charge_value_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_withdrawal_adm_charge_value'], 2);
                $data['results'][$key]['ewallet_withdrawal_tax_value_formatted'] = $this->functionLib->format_nominal("Rp ", $value['ewallet_withdrawal_tax_value'], 2);

                $data['results'][$key]['ewallet_withdrawal_datetime_formatted'] = $this->functionLib->convertDatetime($value['ewallet_withdrawal_datetime']);
                $data['results'][$key]['ewallet_withdrawal_status_datetime_formatted'] = $this->functionLib->convertDatetime($value['ewallet_withdrawal_status_datetime']);
            }
        }

        $this->createRespon(200, 'Data riwayat transfer Saldo', $data);
    }
}
