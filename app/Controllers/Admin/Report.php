<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\ReportModel;
use Config\Services;

/**
 * 
 */
class Report extends BaseController
{

    public function show()
    {
        $config = $this->functionLib->getOne('app_configuration', 'configuration_value', array('configuration_type' => 'bonus'));
        $data['config'] = json_decode($config);

        $data['arrBreadcrumbs'] = array(
            'Laporan' => '#',
            'Riwayat Bonus' => 'report/show'
        );

        $this->template->title('Daftar Riwayat Bonus');
        $this->template->content("report/reportBonusListView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function dashboard()
    {
        $data['arrBreadcrumbs'] = array(
            'Dashboard' => 'dashboard'
        );


        $this->template->title('Dashboard');
        $this->template->content('Admin/dashboardView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function payout()
    {
        $data['arrBreadcrumbs'] = array(
            'Payout' => 'payout'
        );
        $data['month'] = date('m');
        $data['year'] = date('Y');

        $this->template->title('Payout');
        $this->template->content('Admin/incomeStatementView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function repeatorder()
    {
        $data['arrBreadcrumbs'] = array(
            'Repeat Order' => 'admin/report/repeatorder'
        );

        $this->template->title('Repeat Order');
        $this->template->content("Admin/repeatOrderView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function voucher_point()
    {
        $data['arrBreadcrumbs'] = array(
            'Voucher Point' => 'report/voucher_point'
        );

        $this->template->title('Voucher Point');
        $this->template->content("voucherPointView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function serial_sales_registration()
    {
        $data['arrBreadcrumbs'] = array(
            'Penjualan Serial Registrasi' => 'report/serial-sales-registration'
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('Penjualan Serial Registrasi');
        $this->template->content("reportPenjualanSerial", $data);
        $this->template->show('Template/Admin/main');
    }

    public function serial_sales_membership()
    {
        $data['arrBreadcrumbs'] = array(
            'Penjualan Serial Membership' => 'report/serial-sales-membership'
        );

        $data['tipeSerial'] = 'bin';

        $this->template->title('Penjualan Serial Membership');
        $this->template->content("reportPenjualanSerial", $data);
        $this->template->show('Template/Admin/main');
    }

    public function serial_history_registration()
    {
        $data['arrBreadcrumbs'] = array(
            'History Serial Registrasi' => 'report/serial-history-registration'
        );

        $data['tipeSerial'] = 'sys';

        $this->template->title('History Serial Registrasi');
        $this->template->content("reportHistorySerial", $data);
        $this->template->show('Template/Admin/main');
    }

    public function serial_history_membership()
    {
        $data['arrBreadcrumbs'] = array(
            'History Serial Membership' => 'report/serial-history-membership'
        );

        $data['tipeSerial'] = 'bin';

        $this->template->title('History Serial Membership');
        $this->template->content("reportHistorySerial", $data);
        $this->template->show('Template/Admin/main');
    }

    public function history_royalty_it()
    {
        $data['arrBreadcrumbs'] = array(
            'Laporan' => 'report',
            'History Royalty IT' => 'report/history-royalty-it'
        );

        $this->template->title('History Royalty IT');
        $this->template->content("reportRoyaltyIT", $data);
        $this->template->show('Template/Admin/main');
    }


    public function excel_repeatorder()
    {
        $tableName = 'bin_sales_personal';
        $joinTable = '        
        JOIN sys_member ON sales_personal_member_id = member_id
        JOIN bin_member_ref ON member_ref_member_id = member_id';

        $whereCondition = '';

        $request = array();
        $request['columns'] =  [
            'sales_personal_datetime',
            'member_ref_network_code',
            'member_name'
        ];
        $request['display'] =  [
            'Tanggal',
            'Kode',
            'Nama'
        ];
        $request['align'] = [
            'left',
            'left',
            'left'
        ];
        $request['dir'] = 'DESC';
        $request['sort'] = 'sales_personal_datetime';
        $request['filter'] = [];

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        $fieldToReplace = '';
        $valueToReplace = '';

        $title = 'List Repeat Order';
        Services::DataTableLib()->exportToExcel($request['display'], $results, $title, []);
    }

    public function excel()
    {
        $tableName = 'sys_bonus_log';
        $joinTable = 'JOIN sys_member ON member_network_id = bonus_log_network_id';
        $whereCondition = '';
        $tableHead = json_decode($this->request->getPost('display'));
        $results = Services::DataTableLib()->getListDataExcel($this->request, $tableName, $joinTable, $whereCondition);
        $fieldToReplace = '';
        $valueToReplace = '';
        /*multiple replace*/
        $fieldToReplace = array('bonus_log_is_transferred');
        $valueToReplace = array(
            'bonus_log_is_transferred' => array('1' => 'Sudah Ditransfer', '0' => 'Belum Ditransfer'),
        );
        $results = Services::DataTableLib()->replaceArrayValue($results, $fieldToReplace, $valueToReplace);
        /* print_r($results);exit;*/

        $title = 'List Data Histori Bonus';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
    }
}
