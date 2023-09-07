<?php

namespace App\Controllers\Admin;

use Config\Services;
use App\Models\Admin\TransactionModel;


class Transaction extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->transactionModel = new TransactionModel();
        $this->upload_url = getEnv('UPLOAD_URL');
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi' => '/transaction',
            'Data Transaksi Stokis' => '/show',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'stockist';

        $this->template->title('Data Transaksi Stokis');
        $this->template->content('Admin/transactionView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function member_show()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi Mitra' => '/transaction',
            'Data Transaksi Mitra' => '/show',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'member';

        $this->template->title('Data Transaksi Mitra');
        $this->template->content('Admin/transactionView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function approval()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi' => '/transaction',
            'Approval Transaksi' => '/show',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'stockist';

        $this->template->title('Approval Transaksi');
        $this->template->content('Admin/transactionApprovalView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function member_approval()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi Mitra' => '/transaction',
            'Data Transaksi Mitra' => '/show',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'member';

        $this->template->title('Data Transaksi Mitra');
        $this->template->content('Admin/transactionApprovalView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function packaging()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi' => '/transaction',
            'Pengemasan' => '/packaging',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'stockist';

        $this->template->title('Pengemasan');
        $this->template->content('Admin/transactionPackagingView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function member_packaging()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi Mitra' => '/transaction',
            'Pengemasan' => '/packaging',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'member';

        $this->template->title('Pengemasan');
        $this->template->content('Admin/transactionPackagingView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function delivery()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi' => '/transaction',
            'Pengiriman' => '/delivery',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'stockist';

        $this->template->title('Pengiriman');
        $this->template->content('Admin/transactionDeliveryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function member_delivery()
    {
        $data['arrBreadcrumbs'] = array(
            'Transaksi Mitra' => '/transaction',
            'Pengiriman' => '/delivery',
        );

        $data['imagePath'] = UPLOAD_PATH . URL_IMG_CONTENT;
        $data['type'] = 'member';

        $this->template->title('Pengiriman');
        $this->template->content('Admin/transactionDeliveryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function excel($type = '', $status = '')
    {
        $for = "Stokis";

        if ($type == 'member') {
            $for = 'Mitra';
        }

        $tableHead = array(
            'Tanggal',
            'Kode Transaksi',
            'Nama Pembeli',
            'Jenis Pengiriman',
            'Status',
            'Keterangan',
            'Total'
        );

        $tableAlign = json_decode($this->request->getPost('align'));

        $tableName = 'inv_warehouse_transaction';

        $columns = array(
            'warehouse_transaction_datetime',
            'warehouse_transaction_code',
            'warehouse_transaction_buyer_name',
            'warehouse_transaction_delivery_method',
            'warehouse_transaction_status',
            'warehouse_transaction_delivery_awb',
            'warehouse_transaction_total_nett_price'
        );

        $joinTable = "";

        switch ($type) {
            case 'stockist':
                $whereCondition = "warehouse_transaction_buyer_type = '{$type}'";
                break;
            case 'member':
                $whereCondition = "warehouse_transaction_buyer_type = '{$type}'";
                break;
            default:
                $whereCondition = "1";
                break;
        }

        $title = "Daftar Histori Transaksi {$for}";
        if ($status != '') {
            $whereCondition .= " AND warehouse_transaction_status = '{$status}'";
            $title = "Daftar transaksi {$for} Dikemas";
        }

        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'warehouse_transaction_datetime';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            if (!empty($value['warehouse_transaction_status'])) {
                foreach (TRANSACTION_STATUS as $status_key => $val) {
                    if ($status_key ==  $value['warehouse_transaction_status']) {
                        $results[$key]['warehouse_transaction_status'] = $val;
                    }
                }
            }

            if ($value['warehouse_transaction_delivery_method'] == 'pickup') {
                $results[$key]['warehouse_transaction_delivery_awb'] = '-';
            } else {
                $results[$key]['warehouse_transaction_delivery_awb'] = 'No Resi : ' . $value['warehouse_transaction_delivery_awb'];
            }

            if ($status == 'paid') {
                unset($results[$key]['warehouse_transaction_delivery_awb']);
                unset($tableHead[5]);
            }
        }

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }
}
