<?php

namespace App\Controllers\Admin;


use App\Models\Admin\ProductModel;


class Stockist extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->productModel = new ProductModel;
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master/Stokis' => 'stockist/show',
            'Daftar Master/Stokis' => 'stockist/show'
        );

        $this->template->title('Daftar Master/Stokis');
        $this->template->content("Admin/stockistListView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function approval()
    {
        $data['arrBreadcrumbs'] = array(
            'Stokis' => 'stockist/show',
            'Approval Stokis' => 'stockist/approval'
        );

        $this->template->title('Approval Stokis');
        $this->template->content("stockistApprovalView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function transaction()
    {
        $data['arrBreadcrumbs'] = array(
            'Stockis' => 'stockist/show',
            'Transaksi Stokis' => 'stockist/transaction'
        );

        $this->template->title('Transaksi Stokis');
        $this->template->content("Admin/stockistTransactionView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function excel()
    {
        $tableName = 'sys_transaction';
        $joinTable = 'JOIN sys_member ON transaction_member_id = member_id 
            LEFT JOIN bin_member_ref ON transaction_member_id = member_ref_member_id
            LEFT JOIN sys_stockist ON stockist_member_id=transaction_seller_id';
        $whereCondition = "transaction_category = 'stockist'";
        $tableHead = json_decode($this->request->getPost('display'));

        $results = Services::DataTableLib()->getListDataExcel($this->request, $tableName, $joinTable, $whereCondition);
        $fieldToReplace = '';
        $valueToReplace = '';

        $title = 'Daftar Transaksi Stockist';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
    }

    public function summary()
    {
        $data['arrBreadcrumbs'] = array(
            'Master/Stokis' => '#',
            'Stok Master/Stokis' => 'stock/show'
        );

        $this->template->title('Stok Master/Stokis');
        $this->template->content("Admin/stockistStockView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function stock_log()
    {
        $data['arrBreadcrumbs'] = array(
            'Master/Stokis' => '#',
            'Riwayat Stok Master/Stokis' => 'stock-log/show'
        );

        $this->template->title('Riwayat stok Master/Stokis');
        $this->template->content("Admin/stockistStockLogView", $data);
        $this->template->show('Template/Admin/main');
    }
}
