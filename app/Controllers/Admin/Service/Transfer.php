<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;
use App\Models\Admin\TransferModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsx_reader;


class Transfer extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->transferModel = new TransferModel();
        $this->admin_charge = array(
            'percent' => 0,
            'value' => 0,
        );
    }

    public function bonus_transfer_list_daily()
    {
        $table_name = 'bin_bonus_transfer';
        $arr_bonus = array();
        $str_bonus = "";
        $columns = array(
            'bonus_transfer_network_code',
            'bonus_transfer_member_name',
            'bonus_transfer_code',
            'bonus_transfer_total_bonus',
            'bonus_transfer_adm_charge_percent',
            'bonus_transfer_adm_charge_value',
            'bonus_transfer_nett',
            'bonus_transfer_bank_id',
            'bonus_transfer_bank_name',
            'bonus_transfer_bank_account_name',
            'bonus_transfer_bank_account_no',
            'bonus_transfer_mobilephone',
            'bonus_transfer_status',
            'bonus_transfer_note',
            'bonus_transfer_datetime',
            'bonus_transfer_bonus_sponsor',
            'bonus_transfer_bonus_ro'
        );
        $join_table = "";
        $where_condition = "bonus_transfer_status = 'pending' AND bonus_transfer_category = 'daily'";
        $data = Services::DataTableLib()->getListDataTable($this->request, $table_name, $columns, $join_table, $where_condition);
        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($value['bonus_transfer_datetime']) {
                    $data['results'][$key]['bonus_transfer_datetime'] = $this->functionLib->convertDatetime($value['bonus_transfer_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function approval_transfer_list_weekly()
    {
        $table_name = 'bin_bonus_transfer';
        $arr_bonus = array();
        $str_bonus = "";
        $columns = array(
            'bonus_transfer_network_code',
            'bonus_transfer_member_name',
            'bonus_transfer_code',
            'bonus_transfer_total_bonus',
            'bonus_transfer_adm_charge_percent',
            'bonus_transfer_adm_charge_value',
            'bonus_transfer_nett',
            'bonus_transfer_bank_id',
            'bonus_transfer_bank_name',
            'bonus_transfer_bank_account_name',
            'bonus_transfer_bank_account_no',
            'bonus_transfer_mobilephone',
            'bonus_transfer_status',
            'bonus_transfer_note',
            'bonus_transfer_datetime',
            'bonus_transfer_bonus_match',
            'bonus_transfer_bonus_unilevel',
            'bonus_transfer_bonus_sponsor_ro',
            'bonus_transfer_bonus_unilevel_ro',
        );
        $join_table = "";
        $where_condition = "bonus_transfer_status = 'pending' AND bonus_transfer_category = 'weekly'";
        $data = Services::DataTableLib()->getListDataTable($this->request, $table_name, $columns, $join_table, $where_condition);
        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($value['bonus_transfer_datetime']) {
                    $data['results'][$key]['bonus_transfer_datetime'] = $this->functionLib->convertDatetime($value['bonus_transfer_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function bonus_transfer_list_weekly()
    {
        $periode = 'weekly';
        $table_name = 'bin_bonus';
        $bonus_config = json_decode(BIN_CONFIG_BONUS);
        $arr_bonus = array();
        $str_bonus = $str_paid = $str_where = "";
        foreach ($bonus_config as $key => $row) {
            if (($row->active_bonus == true) && ($row->name != 'sponsor' && $row->name != 'ro')) {
                $arr_bonus[] .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0) AS bonus_" . $row->name;
                $str_paid .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0)+";
                $str_where .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0)+";
            }
        }
        $arr_bonus[] .= 'IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0) AS bonus_old';
        $str_paid .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0)+";
        $str_where .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0)+";
        $where_periode = "";
        if ($str_paid != "") {
            $str_paid = substr($str_paid, 0, -1);
            $str_paid = "(" . $str_paid . ") AS bonus_total";
            $str_where = substr($str_where, 0, -1);
            if ($periode == 'weekly') {
                $where_periode = "(" . $str_where . ") >= 200000";
            } else if ($periode == 'daily') {
                $where_periode = "(" . $str_where . ") > 0";
            }
        }

        $arr_select = array(
            'bonus_network_id',
            'member_ref_network_code',
            'member_name',
            'member_bank_id',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_mobilephone',
            $str_paid
        );
        $columns = array_merge($arr_select, $arr_bonus);
        $join_table = "
            JOIN bin_member_ref ON bonus_network_id = member_ref_network_id
            JOIN sys_member ON member_ref_member_id = member_id
        ";
        $where_condition = $where_periode;
        $data = Services::DataTableLib()->getListDataTable($this->request, $table_name, $columns, $join_table, $where_condition);
        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $val) {
                $data['results'][$key]['bonus_admin_charge_percent'] = $this->admin_charge['percent'];
                if ($this->admin_charge > 0) {
                    $data['results'][$key]['bonus_admin_charge_value'] = floor(($val['bonus_total'] * $this->admin_charge['percent']) / 100);
                } else {
                    $data['results'][$key]['bonus_admin_charge_value'] = $this->admin_charge['value'];
                }
                $data['results'][$key]['bonus_transfer_nett'] = floor($val['bonus_total'] - $data['results'][$key]['bonus_admin_charge_value']);
            }
        }
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function bonus_total($periode)
    {
        $data = $this->transferModel->get_bonus_total($periode, $this->admin_charge);
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function bonus_total_daily()
    {
        $data = $this->transferModel->get_bonus_total_daily();
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function bonus_total_weekly()
    {
        $data = $this->transferModel->get_bonus_total_weekly();
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function approval_total_weekly()
    {
        $data = $this->transferModel->get_approval_total_weekly();
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function get_bank_option()
    {
        $data = $this->transferModel->get_bank_option();
        echo json_encode($data);
    }

    public function history_transfer_success($tf_code = '')
    {
        $table_name = 'bin_bonus_transfer_approved';
        $bonus_config = json_decode(BIN_CONFIG_BONUS);
        $arr_bonus = array();
        $str_bonus = "";
        foreach ($bonus_config as $key => $row) {
            if ($row->active_bonus == true) {
                $arr_bonus[] = "bonus_transfer_bonus_" . $row->name;
            }
        }
        $arr_select = array(
            'bonus_transfer_id',
            'bonus_transfer_network_code',
            'bonus_transfer_member_name',
            'bonus_transfer_code',
            'bonus_transfer_total_bonus',
            'bonus_transfer_adm_charge_percent',
            'bonus_transfer_adm_charge_value',
            'bonus_transfer_nett',
            'bonus_transfer_bank_id',
            'bonus_transfer_bank_name',
            'bonus_transfer_bank_account_name',
            'bonus_transfer_bank_account_no',
            'bonus_transfer_mobilephone',
            'bonus_transfer_note',
            'bonus_transfer_datetime',
        );
        $columns = array_merge($arr_select, $arr_bonus);
        $join_table = "";
        $where_condition = "bonus_transfer_code = '$tf_code'";
        if ($tf_code != '') {
            $where_condition = "bonus_transfer_code IN ($tf_code)";
        }
        $data = Services::DataTableLib()->getListDataTable($this->request, $table_name, $columns, $join_table, $where_condition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if (isset($row['bonus_transfer_datetime']) && $row['bonus_transfer_datetime'] != "") {
                    $data['results'][$key]['bonus_transfer_datetime'] = $this->functionLib->convertDatetime($row['bonus_transfer_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data History Transfer Komisi', $data);
    }

    public function get_data_download()
    {
        $limit = (int) $this->request->getGet('limit') <= 0 ? 10 : $this->request->getGet('limit');
        $page = (int) $this->request->getGet('page') <= 0 ? 1 : $this->request->getGet('page');
        $filter = (array) $this->request->getGet('filter');
        $sort = (string) $this->request->getGet('sort');
        $dir = strtoupper($this->request->getGet('dir'));
        $start = 0;
        $start = ($page - 1) * $limit;
        $arr_data = $arr_results['data'] = array();
        $data = $this->transferModel->get_data_download();
        if (!empty($data)) {
            foreach ($data as $key => $row) {
                if (!isset($arr_data[$row->bonus_transfer_datetime])) {
                    $arr_data[$row->bonus_transfer_datetime][] = array('status' => $row->bonus_transfer_status, 'nett_transfer' => $row->bonus_transfer_nett);
                } else {
                    $arr_data[$row->bonus_transfer_datetime][] = ['status' => $row->bonus_transfer_status, 'nett_transfer' => $row->bonus_transfer_nett];
                }
            }
        }
        if (!empty($arr_data)) {
            foreach ($arr_data as $key => $val) {
                $success = $pending = $rejected = $total_tf = 0;
                foreach ($val as $key1 => $val1) {
                    if ($val1['status'] == 'success') {
                        $success++;
                        $total_tf += $val1['nett_transfer'];
                    } else if ($val1['status'] == 'pending') {
                        $pending++;
                    } else if ($val1['status'] == 'failed') {
                        $rejected++;
                    }
                }
                $arr_results['data'][] = array(
                    'tanggal' => $key,
                    'success' => $success,
                    'pending' => $pending,
                    'rejected' => $rejected,
                    'success_transfer' => $total_tf,
                );
            }
        }
        $total = (int) count($arr_results['data']);
        $pagination = Services::DataTableLib()->pageGenerate($total, $page, $limit, $start);
        $data = [
            'results' => $arr_results['data'],
            'pagination' => $pagination
        ];
        $this->createRespon(200, 'Data History Transfer Komisi', $data);
    }

    public function download($status)
    {
        $tanggal = $this->request->getPost('tanggal');
        $table_name = 'bin_bonus_transfer';
        $bonus_config = json_decode(BIN_CONFIG_BONUS);
        $arr_bonus = array();
        $arr_bonus_head = array();
        $str_bonus = "";
        foreach ($bonus_config as $key => $row) {
            if ($row->active_bonus == true) {
                $arr_bonus[] = "bonus_transfer_bonus_" . $row->name;
                $arr_bonus_head[] = strtoupper($row->label);
            }
        }
        $arr_select = array(
            'bonus_transfer_code',
            'bonus_transfer_status',
            'bonus_transfer_network_code',
            'bonus_transfer_member_name',
            'bonus_transfer_total_bonus',
            'bonus_transfer_adm_charge_value',
            'bonus_transfer_nett',
            'bonus_transfer_bank_name',
            'bonus_transfer_bank_account_name',
            'bonus_transfer_bank_account_no',
            'bonus_transfer_mobilephone',
            'bonus_transfer_datetime'
        );
        $columns = array_merge($arr_select, $arr_bonus);
        $columns['columns'] = $columns;
        $table_head = array(
            'KODE TRANSFER',
            'BERHASIL',
            'KODE MEMBER',
            'NAMA MEMBER',
            'TOTAL KOMISI',
            'TOTAL BIAYA ADMIN',
            'TOTAL TRANSFER',
            'BANK',
            'NAMA REKENING',
            'NO REKENING',
            'NO HANDPHONE',
            'TANGGAL',
        );
        $table_head = array_merge($table_head, $arr_bonus_head);
        $columns['filter'] = array();
        $columns['sort'] = $this->request->getPost('sort');
        $columns['dir'] = $this->request->getPost('dir');
        $join_table = "";
        $title = "Approval Transfer Komisi";
        if ($status == 'pending') {
            $where_condition = "bonus_transfer_status = '$status' AND bonus_transfer_datetime = '$tanggal'";
            $results = Services::DataTableLib()->getListDataExcelCustom($columns, $table_name, $join_table, $where_condition);
            array_multisort(array_column($results, 'bonus_transfer_bank_name'), SORT_DESC, $results);
            $this->exportToExcel($table_head, $results, $title, $arr_bonus, $status);
        } else if ($status == 'all') {
            $where_condition = "bonus_transfer_datetime = '$tanggal'";
            $results = Services::DataTableLib()->getListDataExcelCustom($columns, $table_name, $join_table, $where_condition);
            array_multisort(array_column($results, 'bonus_transfer_bank_name'), SORT_DESC, $results);
            $this->exportToExcel($table_head, $results, $title, $arr_bonus, $status);
        }
    }

    public function exportToExcel($tableHead, $data, $title = '', $arr_bonus, $status)
    {
        $tableHeadStyle = [
            'font' => [
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '538ED5'
                ]
            ],
        ];

        //even row
        $even = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFE4C4'
                ]
            ],
        ];

        //odd row
        $odd = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '6495ED'
                ]
            ],
        ];
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $sheet = $spreadsheet->getActiveSheet();

        //set default font 
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
        //set font style 
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $spreadsheet->getActiveSheet()->setCellValue('A1', $title);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->setCellValue('A2', 'Tanggal : ' . date('d,M Y H:i:s'));
        //set cell alignment
        $spreadsheet->getActiveSheet()->getStyle('A:' . strtoupper(chr((count($tableHead) - 1) + 65)))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
        for ($i = 0; $i < count($tableHead); $i++) {
            $spreadsheet->getActiveSheet()->getColumnDimension(strtoupper(chr($i + 65)))->setAutoSize(true);
            $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($i + 65)) . '4', $tableHead[$i]);
        }
        $spreadsheet->getActiveSheet()->getStyle('A4:' . strtoupper(chr((count($tableHead) - 1) + 65)) . '4')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle('A4:' . strtoupper(chr((count($tableHead) - 1) + 65)) . '4')->getFont()->setBold(TRUE);
        $sheet->getCell('A4')->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //background color
        $spreadsheet->getActiveSheet()->getStyle('A4:' . strtoupper(chr((count($tableHead) - 1) + 65)) . '4')->applyFromArray($tableHeadStyle);
        $charColumnIndex = 5;
        foreach ($data as $value) {
            $charColumn = 0;
            if ($status == 'pending') {
                $validation = $sheet->getCell('B' . $charColumnIndex)->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setFormula1('"YA, TIDAK"');
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $sheet->getCell('B' . $charColumnIndex)->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            } else {
                $sheet->getCell('B' . $charColumnIndex)->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            foreach ($value as $key => $value2) {
                if ($value2 == 'success') {
                    $value2 = 'YA';
                } else if ($value2 == 'failed') {
                    $value2 = 'TIDAK';
                }
                $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, $value2);
                if ($key == 'bonus_transfer_datetime') {
                    $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, Services::FunctionLib()->convertDatetime($value2, 'id'));
                }

                if ($key == 'bonus_transfer_bank_account_no') {
                    $sheet->getCell('J' . $charColumnIndex)->getStyle()->getNumberFormat()->setFormatCode('#');
                }

                $charColumn++;
            }
            $charColumnIndex++;
        }
        $filename = strtolower(str_replace(' ', '', $title)) . '-' . date('dmy-H:i:s');
        $url = getenv('EXPORT_PATH') . 'file/excel/' . $filename . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($url);
        $res = array('url' => getenv('EXPORT_URL') . 'file/excel/' . $filename . '.xlsx');
        $this->createRespon(200, 'Data History Transfer Komisi', $res);
    }

    public function bonus_total_sponsor_ro()
    {
        $data = $this->transferModel->get_bonus_total_sponsor_ro($this->admin_charge);
        $this->createRespon(200, 'Data Transfer Komisi', $data);
    }

    public function approveTransfer()
    {
        $transfer_code = $this->request->getPost('data');

        $datetime = date('Y-m-d H:i:s');
        $administrator_id = session('admin')['admin_id'];
        $administrator_name = $this->session->administrator_name;
        if (is_array($transfer_code)) {
            $success = $failed = 0;
            foreach ($transfer_code as $value) {
                if ($this->transferModel->execute_approve($value, $datetime, $administrator_id, $administrator_name)) {
                    $network_code = $this->transferModel->get_member_target_bonus($value);
                    $no_telp = $network_code->bonus_transfer_mobilephone;
                    $message_bonus = $this->functionLib->templateMsgBonus($network_code->bonus_transfer_network_code);
                    $this->functionLib->sendSMS($message_bonus, $no_telp);
                    $this->functionLib->sendWA($message_bonus, $no_telp);
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataApproval = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil approval data transfer!';
            if ($success == 0) {
                $message = 'Gagal approval data transfer';
            }
            $this->createRespon(200, $message, $dataApproval);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diblokir!');
        }
    }
}
