<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\ReportModel;
use Config\Services;

class Report extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->ReportModel = new ReportModel();
    }

    public function getRepeatOrderReport($type = 'repeatorder')
    {
        $tableName = 'sys_ro_personal';
        $columns = array(
            'ro_personal_id',
            'ro_personal_member_id',
            'ro_personal_bv',
            'month(ro_personal_datetime)' => 'ro_personal_datetime_month',
            'year(ro_personal_datetime)' => 'ro_personal_datetime_year',
            'ro_personal_datetime',
            'member_name',
            'member_account_username',
            'ro_personal_note'
        );
        $joinTable = '
        JOIN sys_member ON ro_personal_member_id = member_id
        JOIN sys_member_account ON member_account_member_id = member_id
        ';
        $whereCondition = "";
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, 'GROUP BY ro_personal_member_id, ro_personal_datetime');

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['ro_personal_datetime']) {
                    $data['results'][$key]['ro_personal_datetime_formatted'] = $this->functionLib->convertDatetime($row['ro_personal_datetime'], 'id');
                }
            }
        }

        $this->createRespon(200, 'Data Repeat Order', $data);
    }

    public function getRepeatOrderDetail()
    {
        $data = array();
        $data['results'] = [];

        try {
            //code...
            $sales_personal_id = $this->request->getVar('sales_personal_id');

            if (empty($sales_personal_id)) {
                throw new \Exception("Proses gagal dilakukan, silahkan coba beberapa saat lagi.", 1);
            }

            $data['results'] = $this->ReportModel->getDetailSerialRo($sales_personal_id);

            if (count($data['results'])) {
                foreach ($data['results'] as $key => $value) {
                    $data['results'][$key]->sales_group_bonus_formatted = $this->functionLib->format_nominal("Rp. ", $value->sales_group_bonus, 2);
                }
            }

            $this->createRespon(200, 'OK', $data);
        } catch (\Throwable $th) {
            //throw $th;
            $this->createRespon(400, $th->getMessage(), $data);
        }
    }

    public function getSalesSerialReport($type)
    {
        $tableName = $type . '_serial';
        $columns = array(
            'serial_id',
            'serial_pin',
            'serial_buyer_member_id',
            'serial_seller_administrator_id',
            'serial_is_sold',
            'serial_buy_datetime',
            'member_name',
            'administrator_name',
            'member_ref_network_code'
        );
        $joinTable = '
        JOIN sys_member ON serial_buyer_member_id = member_id
        JOIN site_administrator on administrator_id = serial_seller_administrator_id
        JOIN bin_member_ref on member_ref_member_id = member_id
        ';
        $whereCondition = "serial_is_sold = 1";
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Report Penjualan Serial', $data);
    }

    public function getDetailSerialReport($type)
    {
        $date = $this->request->getGet('date') ?: '';
        $member_id = $this->request->getGet('member_id') ?: '';

        if (empty($member_id) || empty($date)) {
            $result = array(
                "message" => "Data laporan penjualan serial tidak ditemukan"
            );
            $this->createRespon(400, 'Bad Request', $result);
        }

        $result = $this->ReportModel->getDetailSerialReport($date, $member_id, $type);
        if (!$result) {
            $this->createRespon(400, 'Gagal mendapatkan data detail laporan');
        }

        $this->createRespon(200, 'OK', $result);
    }

    public function getHistorySerialReport($type)
    {
        $columns = [
            'serial_transfer_log_id',
            'serial_transfer_log_serial_id',
            'serial_transfer_log_serial_buy_datetime',
            'serial_transfer_log_serial_buyer_member_id',
            'member_ref_network_code',
            'member_name'
        ];
        $limit = (int) $this->request->getGet('limit') <= 0 ? 10 : $this->request->getGet('limit');
        $page = (int) $this->request->getGet('page') <= 0 ? 1 : $this->request->getGet('page');
        $filter = (array) $this->request->getGet('filter');
        $sort = (string) $this->request->getGet('sort') ?: 'serial_transfer_log_serial_id';
        $dir = strtoupper($this->request->getGet('dir'));

        if ($dir != 'ASC' && $dir != 'DESC') $dir = 'ASC';

        $start = ($page - 1) * $limit;

        $search = Services::DataTableLib()->searchInput($filter, $columns);
        $fieldSearch = empty($columns) ? '*' : implode(',', $columns);
        $data = $this->ReportModel->getHistorySerialReport($type, $fieldSearch, $search, $limit, $page, $filter, $sort, $dir);
        $total = (int) $data['count'];
        $pagination = Services::DataTableLib()->pageGenerate($total, $page, $limit, $start);
        $result = [
            'results' => $data['data'],
            'pagination' => $pagination
        ];
        $this->createRespon(200, 'Data Report Penjualan Serial', $result);
    }

    public function getHistoryRoyaltyIT($year = '2022')
    {
        try {
            //code...
            $data_ewallet  = array();

            for ($i = 1; $i <= 12; $i++) {

                if ($i !== 1) {
                    $saldo_bulan_lalu = $data_ewallet[$i - 2]['balance'];
                } else {
                    $result_last_year = $this->ReportModel->getLastRoyalty($year);
                    $saldo_bulan_lalu = $result_last_year;
                }

                $result_report = $this->ReportModel->getMonthRoyalty($i, $year);

                $data_ewallet[$i - 1]['balance'] =   ($saldo_bulan_lalu -  $result_report->out) + $result_report->in;
                $data_ewallet[$i - 1]['saldo'] = $this->functionLib->format_nominal('Rp. ', $data_ewallet[$i - 1]['balance'], 2);
                $data_ewallet[$i - 1]['masuk'] =  $this->functionLib->format_nominal('Rp. ', $result_report->in, 2);
                $data_ewallet[$i - 1]['keluar'] =  $this->functionLib->format_nominal('Rp. ', $result_report->out, 2);
                $data_ewallet[$i - 1]['bulan'] = $this->functionLib->convertMonth($i);
            }

            $this->createRespon(200, 'Berhasil mendapatkan data laporan.', $data_ewallet);
        } catch (\Throwable $th) {
            $this->createRespon(400, 'Gagal mendapatkan data detail laporan');
        }
    }
    public function getYearByDataRoyalty()
    {
        $result =  $this->ReportModel->getYearByDataRoyalty();

        foreach ($result as $key => $value) {
            $result[$key]->val = $value->tahun;
            $result[$key]->name = $value->tahun;
        }

        $this->createRespon(200, 'Data Report Penjualan Serial', $result);
    }

    public function actAddRoyalty()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'comission_log_value' => [
                'label' => 'Nominal',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'numeric' => '{field} harus berupa angka.'
                ],
            ],
            'comission_log_date' => [
                'label' => 'Tanggal',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'valid_date' => '{field} harus berupa tanggal.'
                ],
            ]
        ]);


        if ($validation->run((array) $this->request->getVar()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $result = array();
            $result['data'] = [];

            try {
                $comission_log_value = $this->request->getVar('comission_log_value');
                $comission_log_date = $this->request->getVar('comission_log_date');
                $comission_log_note = $this->request->getVar('comission_log_note');

                $update_ewallet_serial = $this->ReportModel->updateKomisi($comission_log_value);

                if ($update_ewallet_serial == FALSE) {
                    throw new \Exception("Proses gagal, silahkan coba lagi.", 1);
                }

                $data_ewallet_serial_log = array();
                $data_ewallet_serial_log['comission_log_admin_id'] = session('admin')['admin_id'];
                $data_ewallet_serial_log['comission_log_value'] = $comission_log_value;
                $data_ewallet_serial_log['comission_log_type'] = 'in';
                $data_ewallet_serial_log['comission_log_fee'] = 0;
                $data_ewallet_serial_log['comission_log_note'] = $comission_log_note ?  $comission_log_note : "Top Up PT.FUN sebesar : {$this->functionLib->format_nominal('Rp. ',$comission_log_value, 2)}, pada tanggal : {$this->request->getVar('ewallet_serial_log_date')}";
                $data_ewallet_serial_log['comission_log_datetime'] = $comission_log_date;

                if (!$this->db->table('sys_comission_log')->insert($data_ewallet_serial_log)) {
                    throw new \Exception("Proses gagal, silahkan coba lagi.", 2);
                }

                $this->createRespon(200, 'Proses Berhasil !', $data_ewallet_serial_log);
            } catch (\Throwable $th) {
                $this->createRespon(400, $th->getMessage());
            }
        }
    }

    public function detailHistorySerialReport($type)
    {
        $serial = $this->request->getGet('serial') ?: '';

        if (empty($serial)) {
            $result = array(
                "message" => "Data laporan history serial tidak ditemukan"
            );
            $this->createRespon(400, 'Bad Request', $result);
        }

        $result = $this->ReportModel->detailHistorySerialReport($serial, $type);
        if (!$result) {
            $this->createRespon(400, 'Gagal mendapatkan data detail laporan');
        }

        $this->createRespon(200, 'OK', $result);
    }
}
