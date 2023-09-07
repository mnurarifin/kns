<?php

namespace App\Controllers\Admin;

use Config\Services;

class Serial extends BaseController
{

    public function registration()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Serial Registrasi' => 'serial/registration',
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('Serial Registrasi');
        $this->template->content("Admin/serialListView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function repeatorder()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Serial Repeat Order' => 'serial/repeatorder',
        );

        $data['tipeSerial'] = 'bin';

        $this->template->title('Serial Repeat Order');
        $this->template->content("serialRoListView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function history_repeatorder_sales()
    {

        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Histori Kirim Serial RO' => 'serial/history_repeatorder_sales',
        );

        $this->template->title('Histori Kirim Serial Repeat Order');
        $this->template->content("serialSalesRoHistoryView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function sales_registration()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'History Kirim Serial Registrasi' => 'serial/sales_registration',
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('History Kirim Serial Registrasi');
        $this->template->content('Admin/serialSalesView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function sales_serial()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Penjualan Serial' => 'serial/sales_serial',
        );

        $data['tipeSerial'] = 'bin';

        $this->template->title('Penjualan Serial');
        $this->template->content('Admin/serialSalesView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function serial_upgrade()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Serial Upgrade' => 'serial/serial_upgrade',
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('Serial Upgrade');
        $this->template->content('Admin/serialUpgradeView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function history_serial_upgrade()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Histori Kirim Serial Upgrade' => 'serial/serial_upgrade',
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('Histori Kirim Serial Upgrade');
        $this->template->content('Admin/serialUpgradeHistoryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function cancel_registration()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Batal Jual Serial Registrasi' => 'serial/cancel_registration',
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('Form Pembatalan Penjualan Serial Registrasi');
        $this->template->content('Admin/serialCancelBuyView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function create_serial()
    {
        if ($this->session->administrator_administrator_group_id != 1) {
            redirect()->to('/dashboard');
        }

        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Tambah Serial' => 'serial/create_serial',
        );

        $this->template->title('Tambah Data Serial');
        $this->template->content('Admin/serialAddView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function excelCreate($type = 'bin', $start = '')
    {
        if ($start != '') {
            if ($type == 'sys') {
                $title = 'Serial Registrasi';
            } elseif ($type == 'uni') {
                $title = 'Serial Produk Unilevel';
            } else {
                $title = 'Serial Produk Binary';
            }

            $tableName = $type . '_serial';
            $joinTable = '';
            $request['columns'] = ['serial_id', 'serial_pin'];
            $request['filter'] = '';
            $request['dir'] = 'ASC';
            $request['sort'] = 'serial_id';
            $whereCondition = "serial_id > '{$start}'";
            $tableHead = ['Serial', 'PIN'];
            $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition, 10000);
            Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
        }
    }

    public function history_sales_registration()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Log Penjualan Serial Registrasi' => 'serial/history_sales_registration',
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('Log Penjualan Serial Registrasi');
        $this->template->content('Admin/serialSalesHistoryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function history_sales_activation()
    {
        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Log Penjualan Serial Produk' => 'serial/history_sales_registration',
        );

        $data['tipeSerial'] = 'bin';

        $this->template->title('Log Penjualan Serial Produk');
        $this->template->content('Admin/serialSalesHistoryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function topup_serial_wallet()
    {

        $data['arrBreadcrumbs'] = array(
            'Serial' => '#',
            'Top Up Saldo Perusahaan' => 'serial/topup_serial_wallet',
        );

        $this->template->title('Top Up Saldo Perusahaan');
        $this->template->content('Admin/serialWalletTopUp', $data);
        $this->template->show('Template/Admin/main');
    }
}
