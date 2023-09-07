<?php

namespace App\Controllers\Admin;

use Config\Services;
use App\Models\Admin\TransferModel;

class Transfer extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->transferModel = new TransferModel();
        $this->admin_charge = array(
            'percent' => 0,
            'value' => 0,
        );
    }

    public function approval_daily()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Approval Transfer Komisi Harian' => 'transfer/approval_daily/'
        );

        $this->template->title('Approval Transfer Komisi Harian');
        $this->template->content("transferDailyApprovalView", $data);
        $this->template->show('Template/Admin/main');
    }
    
    public function approval_weekly()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Approval Transfer Komisi Mingguan' => 'transfer/approval_weekly/'
        );

        $this->template->title('Approval Transfer Komisi Mingguan');
        $this->template->content("transferWeeklyApprovalView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function recap_weekly()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Rekap Komisi Mingguan' => 'transfer/recap_weekly/'
        );

        $this->template->title('Rekap Transfer Komisi Mingguan');
        $this->template->content("transferRecapWeeklyView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function upload()
    {
        $data = array();
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Upload Transfer Komisi' => 'transfer/upload'
        );

        $this->template->title('Upload Transfer Komisi');
        $this->template->content("transferUploadView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function approvalHistory($periode = 'daily')
    {
        $kategori = '';
        if ($periode == 'daily') {
            $kategori = 'Harian';
        } elseif ($periode == 'weekly') {
            $kategori = 'Mingguan';
        } elseif ($periode == 'monthly') {
            $kategori = 'Bulanan';
        }
        $data['arrBreadcrumbds'] = array(
            'Komisi' => '#',
            'Approval Transfer Komisi ' . $kategori => 'transfer/approval/' . $periode,
            'Riwayat Approval Transfer Komisi ' . $kategori => 'transfer/approvalHistory/' . $periode
        );
        $this->template->title('Riwayat Approval Transfer Komisi ' . $kategori);
        $this->template->content('Admin/transferHistoryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function history_old($periode = 'daily')
    {
        $kategori = '';
        if ($periode == 'daily') {
            $kategori = 'Harian';
        } elseif ($periode == 'weekly') {
            $kategori = 'Mingguan';
        } elseif ($periode == 'monthly') {
            $kategori = 'Bulanan';
        }
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Histori Transfer Komisi ' . $kategori => 'transfer/history/' . $periode
        );
        $data['title'] = 'Histori Transfer Komisi ';

        $this->template->title('Histori Transfer Komisi ');
        $this->template->content("transferHistorySuccessView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function history($tanggal = '')
    {
        if ($tanggal == '') {
            $tanggal = date('Y-m-d H:i:s');
        }

        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Histori Transfer Komisi ' . $tanggal => 'transfer/history/' . $tanggal
        );
        $data['title'] = 'Histori Transfer Komisi ';
        $data['tf_code'] = $this->transferModel->get_data_tf_code_by_datetime($tanggal);

        $this->template->title('Histori Transfer Komisi ');
        $this->template->content("transferHistorySuccessView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function upload_excel_transfer_komisi()
    {
        $excel = $this->request->getFile('file_excel');
        if (empty($excel) || !$excel->isValid()) {
            $this->createRespon(200, 'ERROR', array('msg' => 'File tidak boleh kosong'));
        }

        if (!($excel->getExtension() == 'xlsx' || $excel->getExtension() == 'xls')) {
            $this->createRespon(200, 'ERROR', array('msg' => 'Format file tidak sesuai.'));
        }

        $new_name = $excel->getRandomName();
        $excel->move(UPLOAD_PATH.'file/excel/', $new_name);
        $datetime = date('Y-m-d H:i:s');
        $process_transfer = $this->transferModel->import_excel($new_name, $datetime, $this->session->administrator_id, $this->session->administrator_name);
        if ($process_transfer['status'] == 200) {
            $this->createRespon(200, 'OK', $process_transfer['data']);
        } else {
            $this->createRespon(200, 'ERROR', $process_transfer['msg']);
        }
    }

    public function excel_approval($periode)
    {
        $admin_charge= $this->admin_charge;
        $data = $this->transferModel->export_excel_approval($periode, $this->request, $admin_charge);
        if ($data == TRUE) {
            $this->createRespon(200, 'Rekap Komisi Mingguan Berhasil', $data);
        } else {
            $this->createRespon(400, 'Rekap Komisi Mingguan Gagal');
        }
    }

    public function excel_approval_sponsor_ro()
    {
        $table_name = 'bin_bonus_transfer';
        $table_head = array(
            'KODE TRANSFER',
            'BERHASIL',
            'KODE MEMBER',
            'NAMA MEMBER',
            'BANK',
            'NAMA REKENING',
            'NO REKENING',
            'NO HANDPHONE',
            'TANGGAL',
            'KOMISI SPONSOR',
            'KOMISI RO',
            'TOTAL KOMISI',
            'TOTAL BIAYA ADMIN',
            'TOTAL TRANSFER',
        );
        $where_condition = "bonus_transfer_status = 'pending' AND bonus_transfer_category = 'daily'";
        $join_table = "";
        $results = Services::DataTableLib()->getListDataExcel($this->request, $table_name, $join_table, $where_condition);
        $title = "Approval Transfer Komisi Sponsor dan RO";
        $this->transferModel->exportToExcelSponsorRo($table_head, $results, $title, 'pending');
    }

    public function export_excel_history_success($periode)
    {
        $data = $this->transferModel->export_excel_history_success($periode, $this->request);
    }

    public function exportHistoryTransferBonus($periode = 'daily')
    {
        $kategori = '';
        if ($periode == 'daily') {
            $kategori = 'Harian';
        } elseif ($periode == 'weekly') {
            $kategori = 'Mingguan';
        } elseif ($periode == 'monthly') {
            $kategori = 'Bulanan';
        }
        $title = 'Riwayat Approval Transfer Bonus ' . $kategori;

        $tableName = 'bin_bonus_transfer';
        $joinTable = '';
        $whereCondition = 'bonus_transfer_status="success" AND bonus_transfer_category="' . $periode . '"';
        $tableHead = array(
            'Kode Transfer',
            'Kode Member',
            'Nama',
            'Nama Bank',
            'Nama Akun Bank',
            'Nomor Akun Bank',
            'Biaya Admin',
            'Total Bonus',
            'Total Transfer'
        );

        $columns['columns'] = array(
            'bonus_transfer_code',
            'bonus_transfer_network_code',
            'bonus_transfer_member_name',
            'bonus_transfer_bank_name',
            'bonus_transfer_bank_account_name',
            'bonus_transfer_bank_account_no',
            'bonus_transfer_adm_charge_value',
            'bonus_transfer_total_bonus',
            'bonus_transfer_nett',
        );
        $columns['sort'] = $this->request->getPost('sort');
        $columns['filter'] = array();
        $columns['dir'] = $this->request->getPost('dir');
        $results = Services::DataTableLib()->getListDataExcelCustom($columns, $tableName, $joinTable, $whereCondition);
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
    }

    public function download()
    {
        $data['arrBreadcrumbs'] = array(
            'Komisi' => '#',
            'Histori Transfer Komisi' => '#'
        );
        $this->template->title('Histori Transfer Komisi');
        $this->template->content('Admin/transferDownloadView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function approval($periode = 'daily')
    {
        $kategori = '';
        if ($periode == 'daily') {
            $kategori = 'Harian';
        } elseif ($periode == 'weekly') {
            $kategori = 'Mingguan';
        } elseif ($periode == 'monthly') {
            $kategori = 'Bulanan';
        }
        $data['periode'] = $periode;
        $data['arrBreadcrumbs'] = array(
            'Transfer Komisi' => '#',
            'Approval Transfer Komisi ' . $kategori => 'transfer/approval/' . $periode
        );

        $this->template->title('Approval Transfer Komisi ' . $kategori);
        $this->template->content("transferApprovalView", $data);
        $this->template->show('Template/Admin/main');
    }

}
