<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;
use App\Models\Admin\DashboardModel;

class Ewallet extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->dashboardModel = new DashboardModel();

        helper(['form', 'url']);
    }

    public function getWalletBalance()
    {
        $filter = (array) $this->request->getGet('filter');
        $sort = (string) $this->request->getGet('sort');
        $dir = strtoupper($this->request->getGet('dir'));
        $page = (int) $this->request->getGet('page') <= 0 ? 1 : $this->request->getGet('page');
        $limit = (int) $this->request->getGet('limit') <= 0 ? 10 : $this->request->getGet('limit');
        $start = ($page - 1) * $limit;

        $querySearch = [];
        if (is_array($filter)) {
            $querySearch = Services::DataTableLib()->searchInput($filter, [
                'ewallet_member_id',
                'ewallet_acc',
                'ewallet_paid',
                'saldo',
                'member_name',
            ]);
        }

        $sql = "
            select ewallet_member_id, ewallet_acc, ewallet_paid, ewallet_acc-ewallet_paid as saldo, stockist_name
            FROM `sys_ewallet` join inv_stockist ON stockist_member_id = ewallet_member_id
            WHERE 1 {$querySearch['sqlSearch']}
            ";

        $data = ['results' => [], 'pagination' => []];
        $total = $this->db->query($sql, $querySearch['sqlSearchValue'])->getResultArray();

        $data['totalSaldo'] = 0;
        $data['totalKomisi'] = 0;
        $data['totalPaid'] = 0;

        if (count($querySearch['sqlSearchValue']) == 0) {
            $sql_saldo = "
            select sum(ewallet_acc) as bonus_acc, sum(ewallet_paid) as bonus_paid
            FROM `sys_ewallet`
            ";
            $total_saldo = $this->db->query($sql_saldo)->getRow();
            $data['totalKomisi'] = $total_saldo->bonus_acc;
            $data['totalPaid'] = $total_saldo->bonus_paid;
            $data['totalSaldo'] = $total_saldo->bonus_acc - $total_saldo->bonus_paid;
        }

        $data['results'] = $this->db
            ->query($sql . " ORDER BY {$sort} {$dir} LIMIT {$start}, {$limit}", $querySearch['sqlSearchValue'])
            ->getResultArray();

        $data['pagination'] = Services::DataTableLib()->pageGenerate(count($total), $page, $limit, $start);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($value['ewallet_acc']) {
                    $data['results'][$key]['ewallet_acc'] = $this->functionLib->setNumberFormat($value['ewallet_acc']);
                }
                if ($value['ewallet_paid']) {
                    $data['results'][$key]['ewallet_paid'] = $this->functionLib->setNumberFormat($value['ewallet_paid']);
                }

                if ($value['saldo']) {
                    $data['results'][$key]['saldo'] = $this->functionLib->setNumberFormat($value['saldo']);
                }

                if (count($querySearch['sqlSearchValue']) > 0) {
                    $data['totalSaldo'] += $value['saldo'];
                    $data['totalPaid'] += $value['ewallet_paid'];
                    $data['totalKomisi'] += $value['ewallet_acc'];
                }
            }
        }

        $data['totalSaldo'] = 'Rp. ' . $this->functionLib->setNumberFormat($data['totalSaldo']);
        $data['totalKomisi'] = 'Rp. ' . $this->functionLib->setNumberFormat($data['totalKomisi']);
        $data['totalPaid'] = 'Rp. ' . $this->functionLib->setNumberFormat($data['totalPaid']);

        $this->createRespon(200, 'Data Laporan saldo mitra', $data);
    }

    public function getLogWalletBalance($member_id)
    {
        $data = array();
        $data['results'] = [];

        try {

            if (!$member_id) {
                throw new \Exception("ID member tidak ditemukan", 1);
            }

            $whereCondition = "ewallet_product_log_member_id = '$member_id'";
            $joinTable = ' ';
            $tableName = 'sys_ewallet_product_log';
            $columns = array(
                'ewallet_product_log_id',
                'ewallet_product_log_member_id',
                'ewallet_product_log_ewallet_product_transfer_id', //Make The Filter Working 
                "ewallet_product_log_value",
                'ewallet_product_log_type',
                'ewallet_product_log_note',
                'ewallet_product_log_datetime'
            );

            $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

            if (count($data['results']) > 0) {
                foreach ($data['results'] as $key => $row) {
                    $data['results'][$key]['ewallet_product_log_value_formatted'] = $this->functionLib->format_nominal('Rp. ', $row['ewallet_product_log_value'], 2);
                    $data['results'][$key]['ewallet_product_log_datetime_formatted'] = $this->functionLib->convertDatetime($row['ewallet_product_log_datetime'], 'id');
                    $data['results'][$key]['ewallet_product_log_type_formatted'] = $data['results'][$key]['ewallet_product_log_type'] == 'in' ? 'Masuk' : 'Keluar';
                }
            }


            $this->createRespon(200, 'Data Laporan Saldo Mitra ', $data);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage(), $data);
        }
    }

    public function getTransferLogWalletBalance($member_id)
    {
        $data = array();
        $data['results'] = [];

        try {

            if (!$member_id) {
                throw new \Exception("ID member tidak ditemukan", 1);
            }

            $whereCondition = "(ewallet_product_transfer_sender_member_id = '$member_id' || ewallet_product_transfer_receiver_member_id = '$member_id')";

            $tableName = 'sys_ewallet_product_transfer';
            $columns = array(
                'ewallet_product_transfer_id',
                'ewallet_product_transfer_sender_member_id',
                'ewallet_product_transfer_sender_network_id',
                "ewallet_product_transfer_sender_network_code",
                "IF(ewallet_product_transfer_sender_member_id = $member_id, 'Keluar', 'Masuk') AS ewallet_product_transfer_type",
                'l1.member_name AS ewallet_product_transfer_sender_member_name',
                'ewallet_product_transfer_receiver_member_id',
                'ewallet_product_transfer_receiver_network_id',
                'ewallet_product_transfer_receiver_network_code',
                'l2.member_name AS ewallet_product_transfer_receiver_member_name',
                'ewallet_product_transfer_value',
                'ewallet_product_transfer_note',
                'ewallet_product_transfer_datetime'
            );

            $joinTable = ' 
                LEFT JOIN sys_member l1 on l1.member_id = ewallet_product_transfer_sender_member_id
                LEFT JOIN sys_member l2 on l2.member_id = ewallet_product_transfer_receiver_member_id
            ';
            $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
            if (count($data['results']) > 0) {
                foreach ($data['results'] as $key => $row) {
                    $data['results'][$key]['ewallet_product_transfer_sender_member_name'] = '(' . $row['ewallet_product_transfer_sender_network_code'] . ')' . ' ' . $row['ewallet_product_transfer_sender_member_name'];
                    $data['results'][$key]['ewallet_product_transfer_receiver_member_name'] = '(' . $row['ewallet_product_transfer_receiver_network_code'] . ')' . ' ' . $row['ewallet_product_transfer_receiver_member_name'];

                    $data['results'][$key]['ewallet_product_transfer_value_formatted'] = $this->functionLib->format_nominal('Rp. ', $row['ewallet_product_transfer_value'], 2);
                    $data['results'][$key]['ewallet_product_transfer_datetime_formatted'] = $this->functionLib->convertDatetime($row['ewallet_product_transfer_datetime'], 'id');
                }
            }

            $this->createRespon(200, 'Data Laporan Transfer Saldo Mitra ', $data);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage(), $data);
        }
    }
}
