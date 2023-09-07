<?php

namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsx_reader;

class TransferModel extends Model
{

    function execute_approve($transfer_code, $datetime, $administrator_id, $administrator_name)
    {
        $arr_response['status'] = 200;
        $status = 'success';
        $this->db->transBegin();
        try {
            $data_tf = $this->get_data_transfer($transfer_code);
            if (!empty($data_tf)) {
                $check_tf_code = $this->check_approved($transfer_code);
                if (empty($check_tf_code) && $data_tf['bonus_transfer_status'] == 'pending') {
                    $sql = "UPDATE bin_bonus_transfer
                    SET bonus_transfer_status = '{$status}',
                    bonus_transfer_status_update_administrator_id = '{$administrator_id}',
                    bonus_transfer_status_update_administrator_name = '{$administrator_name}',
                    bonus_transfer_status_update_datetime = '$datetime'
                    WHERE bonus_transfer_code = '{$transfer_code}'
                    ";
                    $update = $this->db->query($sql);
                    if (!$update) {
                        throw new \Exception("Gagal update status transfer", 1);
                    }
                    $arr_success_tf = array(
                        'bonus_transfer_network_id' => $data_tf['bonus_transfer_network_id'],
                        'bonus_transfer_network_code' => $data_tf['bonus_transfer_network_code'],
                        'bonus_transfer_member_name' => $data_tf['bonus_transfer_member_name'],
                        'bonus_transfer_category' => $data_tf['bonus_transfer_category'],
                        'bonus_transfer_code' => $data_tf['bonus_transfer_code'],
                        'bonus_transfer_total_bonus' => $data_tf['bonus_transfer_total_bonus'],
                        'bonus_transfer_adm_charge_percent' => $data_tf['bonus_transfer_adm_charge_percent'],
                        'bonus_transfer_adm_charge_value' => $data_tf['bonus_transfer_adm_charge_value'],
                        'bonus_transfer_nett' => $data_tf['bonus_transfer_nett'],
                        'bonus_transfer_bank_id' => $data_tf['bonus_transfer_bank_id'],
                        'bonus_transfer_bank_name' => $data_tf['bonus_transfer_bank_name'],
                        'bonus_transfer_bank_city' => $data_tf['bonus_transfer_bank_city'],
                        'bonus_transfer_bank_branch' => $data_tf['bonus_transfer_bank_branch'],
                        'bonus_transfer_bank_account_name' => $data_tf['bonus_transfer_bank_account_name'],
                        'bonus_transfer_bank_account_no' => $data_tf['bonus_transfer_bank_account_no'],
                        'bonus_transfer_mobilephone' => $data_tf['bonus_transfer_mobilephone'],
                        'bonus_transfer_note' => $data_tf['bonus_transfer_note'],
                        'bonus_transfer_datetime' => $datetime,
                        'bonus_transfer_status_administrator_id' => $administrator_id,
                        'bonus_transfer_status_administrator_name' => $administrator_name,
                        'bonus_transfer_bonus_sponsor' => $data_tf['bonus_transfer_bonus_sponsor'],
                        'bonus_transfer_bonus_node' => $data_tf['bonus_transfer_bonus_node'],
                        'bonus_transfer_bonus_match' => $data_tf['bonus_transfer_bonus_match'],
                        'bonus_transfer_bonus_gen_node' => $data_tf['bonus_transfer_bonus_gen_node'],
                        'bonus_transfer_bonus_gen_match' => $data_tf['bonus_transfer_bonus_gen_match'],
                        'bonus_transfer_bonus_gen_sponsor' => $data_tf['bonus_transfer_bonus_gen_sponsor'],
                        'bonus_transfer_bonus_ro' => $data_tf['bonus_transfer_bonus_ro'],
                        'bonus_transfer_bonus_unilevel' => $data_tf['bonus_transfer_bonus_unilevel'],
                        'bonus_transfer_bonus_sponsor_ro' => $data_tf['bonus_transfer_bonus_sponsor_ro'],
                        'bonus_transfer_bonus_unilevel_ro' => $data_tf['bonus_transfer_bonus_unilevel_ro'],
                        'bonus_transfer_bonus_old' => $data_tf['bonus_transfer_bonus_old'],
                    );
                    if (!empty($arr_success_tf)) {
                        if (!$this->db->table('bin_bonus_transfer_approved')->insert($arr_success_tf)) {
                            throw new \Exception("Gagal insert bonus transfer success", 1);
                        }
                    }
                } else {
                    throw new \Exception("Data transfer sudah pernah diapprove", 1);
                }
            } else {
                throw new \Exception("Data transfer tidak ditemukan", 1);
            }
        } catch (\Exception $e) {
            $arr_response['status'] = 400;
            $arr_response['msg'] = $e->getMessage();
        }
        if ($arr_response['status'] == 200 && $this->db->transStatus() == TRUE) {
            $this->db->transCommit();
        } else {
            $this->db->transRollback();
        }
        return $arr_response;
    }

    public function get_member_target_bonus($transfer_code)
    {
        $sql = "SELECT bonus_transfer_network_code, bonus_transfer_mobilephone FROM bin_bonus_transfer WHERE  bonus_transfer_code = '$transfer_code'";
        $data = $this->db->query($sql)->getRow();
        return $data;
    }

    public function get_bonus_total_sponsor_ro($admin_charge)
    {
        $arr_data = array(
            'paid' => 0,
            'admin_charge' => 0,
            'nett_paid' => 0,
        );
        $sql = "SELECT SUM((bonus_sponsor_acc - bonus_sponsor_paid) + (bonus_ro_acc - bonus_ro_paid)) AS total_saldo FROM bin_bonus";
        $data = $this->db->query($sql)->getRowArray();
        if (!empty($data)) {
            if ($admin_charge['percent'] > 0) {
                $admin = ($data['total_saldo'] * $admin_charge['percent']) / 100;
            } else {
                $admin = ($admin_charge['value']);
            }
            $arr_data = array(
                'paid' => $data['total_saldo'],
                'admin' => $admin,
                'nett_paid' => $data['total_saldo'] - $admin,
            );
        }
        return $arr_data;
    }
    public function get_bonus_total($periode, $admin_charge)
    {
        $arr_data = array(
            'paid' => 0,
            'admin' => 0,
            'nett_paid' => 0,
        );
        $bonus_config = json_decode(BIN_CONFIG_BONUS);
        $str_saldo = $str_where_weekly = "";
        foreach ($bonus_config as $key => $row) {
            if (($row->active_bonus == true) && ($row->transfer_periode == $periode)) {
                $str_saldo .= "(bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid)+";
            }
        }
        if ($str_saldo != "") {
            $str_saldo = substr($str_saldo, 0, -1);
            $str_where = $str_saldo;
            $str_saldo = "SUM(" . $str_saldo . ") AS total_saldo";
        }
        $where_condition = "";
        if ($periode == 'weekly') {
            $where_condition = "HAVING SUM(" . $str_where . ") >= 100000";
        }
        $sql = "
            SELECT $str_saldo
            FROM bin_bonus
            $where_condition
        ";
        $data = $this->db->query($sql)->getRowArray();
        if (!empty($data['total_saldo'])) {
            if ($admin_charge['percent'] > 0) {
                $admin = ($data['total_saldo'] * $admin_charge['percent']) / 100;
            } else {
                $admin = ($admin_charge['value']);
            }
            $arr_data = array(
                'paid' => $data['total_saldo'],
                'admin' => $admin,
                'nett_paid' => $data['total_saldo'] - $admin,
            );
        }
        return $arr_data;
    }

    public function get_bonus_total_weekly()
    {
        $bonus_config = json_decode(BIN_CONFIG_BONUS);
        $str_paid = $str_where = $where_condition = "";
        foreach ($bonus_config as $key => $row) {
            if (($row->active_bonus == true) && ($row->transfer_periode == "weekly")) {
                $str_paid .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0)+";
                $str_where .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0)+";
            }
        }
        $str_paid .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0)+";
        $str_where .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0)+";
        if ($str_paid != "") {
            $str_paid = substr($str_paid, 0, -1);
            $str_paid = "(" . $str_paid . ") AS bonus_total";
            $str_where = substr($str_where, 0, -1);
            $where_condition = "WHERE (" . $str_where . ") >= 200000 AND (member_bank_account_no != '' OR member_bank_account_no != NULL)";
        }
        $sql = "
            SELECT $str_paid
            FROM bin_bonus
            JOIN bin_member_ref ON bonus_network_id = member_ref_network_id
            JOIN sys_member ON member_ref_member_id = member_id
            $where_condition
        ";
        $data = $this->db->query($sql)->getResult();
        $paid = $admin = $nett_paid = 0;
        if (!empty($data)) {
            foreach ($data as $key => $row) {
                $paid += $row->bonus_total;
            }
        }
        $arr_data = array(
            'paid' => $paid,
            'admin' => 0,
            'nett_paid' => $paid - $admin,
        );
        return $arr_data;
    }

    public function get_bonus_total_daily()
    {
        $sql = "
            SELECT SUM(bonus_transfer_total_bonus) AS total
            FROM bin_bonus_transfer
            WHERE bonus_transfer_status = 'pending' AND bonus_transfer_category = 'daily'
        ";
        $data = $this->db->query($sql)->getRow('total');
        if (!empty($data)) {
            $total = $data;
        } else {
            $total = 0;
        }
        $arr_data = array(
            'paid' => $total,
            'admin' => 0,
            'nett_paid' => $total,
        );
        return $arr_data;
    }

    public function get_approval_total_weekly()
    {
        $sql = "
            SELECT SUM(bonus_transfer_total_bonus) AS total
            FROM bin_bonus_transfer
            WHERE bonus_transfer_status = 'pending' AND bonus_transfer_category = 'weekly'
        ";
        $data = $this->db->query($sql)->getRow('total');
        if (!empty($data)) {
            $total = $data;
        } else {
            $total = 0;
        }
        $arr_data = array(
            'paid' => $total,
            'admin' => 0,
            'nett_paid' => $total,
        );
        return $arr_data;
    }

    public function get_bank_option()
    {
        $options = array();
        $query = Services::functionLib()->getListBank();
        if (!empty($query)) {
            $i = 0;
            foreach ($query as $row) {
                $options[$i]['title'] = $row->bank_name;
                $options[$i]['value'] = $row->bank_id;
                $i++;
            }
        }
        return $options;
    }

    public function get_data_history_transfer_success($periode, $request)
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
            'bonus_transfer_datetime'
        );
        $columns = array_merge($arr_select, $arr_bonus);
        $join_table = "";
        $where_condition = "bonus_transfer_category = '$periode'";
        $data = Services::DataTableLib()->getListDataTable($request, $table_name, $columns, $join_table, $where_condition);
        return $data;
    }

    public function exportToExcelSponsorRo($tableHead, $data, $title = '', $status)
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

                if ($key == 'bonus_transfer_total_bonus' || $key == 'bonus_transfer_adm_charge_value' || $key == 'bonus_transfer_nett' || $key == 'bonus_transfer_bonus_ro' || $key == 'bonus_transfer_bonus_sponsor') {
                    $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, 'Rp. ' . number_format($value2, 0, ',', '.'));
                }

                if ($key == 'bonus_transfer_bank_account_no') {
                    $sheet->getCell('G' . $charColumnIndex)->getStyle()->getNumberFormat()->setFormatCode('#');
                }
                $charColumn++;
            }
            $charColumnIndex++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = strtolower(str_replace(' ', '', $title)) . '-' . date('dmy');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        $writer->save('php://output');
    }

    public function export_excel_approval($periode, $request, $admin_charge)
    {
        $datetime = date('Y-m-d H:i:s');
        $table_name = 'bin_bonus';
        $bonus_config = json_decode(BIN_CONFIG_BONUS);
        $arr_bonus_name = $arr_bonus = $arr_bonus_head = array();
        $str_paid = $str_where = "";
        foreach ($bonus_config as $key => $row) {
            if (($row->active_bonus == true) && $row->name != 'sponsor' && $row->name != 'ro') {
                $arr_bonus[] .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0) AS bonus_" . $row->name;
                $str_paid .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0)+";
                $str_where .= "IF(bonus_" . $row->name . "_acc > bonus_" . $row->name . "_paid, bonus_" . $row->name . "_acc - bonus_" . $row->name . "_paid, 0)+";
                $arr_bonus_head[] = strtoupper($row->label);
                $arr_bonus_name[] = $row->name;
            }
        }
        $arr_bonus[] .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0) AS bonus_old";
        $str_paid .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0)+";
        $str_where .= "IF(bonus_old_acc > bonus_old_paid, bonus_old_acc - bonus_old_paid, 0)+";
        $arr_bonus_head[] .= 'BONUS LAMA';
        $arr_bonus_name[] .= 'old';
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
            'member_bank_city',
            'member_bank_branch',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_mobilephone',
            $str_paid
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
        $columns['sort'] = $request->getPost('sort');
        $columns['dir'] = $request->getPost('dir');
        $join_table = "
            JOIN bin_member_ref ON bonus_network_id = member_ref_network_id
            JOIN sys_member ON member_ref_member_id = member_id
        ";
        $where_condition = $where_periode;
        $results = Services::DataTableLib()->getListDataExcelCustom($columns, $table_name, $join_table, $where_condition);
        $title = "Approval Transfer Komisi";
        $saved = $this->exportToExcel($table_head, $results, $title, $arr_bonus, $arr_bonus_name, $datetime, $admin_charge, $periode);
        if ($saved) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function exportToExcel($tableHead, $data, $title = '', $arr_bonus, $arr_bonus_name, $datetime, $admin_charge, $periode)
    {
        if (count($data) > 0) {
            $data_transfer = $this->save_transfer($data, $arr_bonus_name, $datetime, $admin_charge, $periode);
            if ($data_transfer['status'] != 200) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function save_transfer($data, $arr_bonus_name, $datetime, $admin_charge, $periode)
    {
        $this->db->transBegin();
        $arr_response['status'] = 200;
        try {
            foreach ($data as $key => $val) {
                if ($admin_charge['percent'] > 0) {
                    $admin_value = ($val['bonus_total'] * $admin_charge['percent']) / 100;
                } else {
                    $admin_value = $admin_charge['value'];
                }
                $transfer_nett = $val['bonus_total'] - $admin_value;
                $arr_trf = array(
                    'bonus_transfer_network_id' => $val['bonus_network_id'],
                    'bonus_transfer_network_code' => $val['member_ref_network_code'],
                    'bonus_transfer_member_name' => $val['member_name'],
                    'bonus_transfer_category' => $periode,
                    'bonus_transfer_status' => 'pending',
                    'bonus_transfer_code' => $this->get_transfer_code(),
                    'bonus_transfer_total_bonus' => $val['bonus_total'],
                    'bonus_transfer_bank_id' => $val['member_bank_id'],
                    'bonus_transfer_bank_name' => $val['member_bank_name'],
                    'bonus_transfer_bank_city' => $val['member_bank_city'],
                    'bonus_transfer_bank_branch' => $val['member_bank_branch'],
                    'bonus_transfer_bank_account_name' => $val['member_bank_account_name'],
                    'bonus_transfer_bank_account_no' => $val['member_bank_account_no'],
                    'bonus_transfer_mobilephone' => $val['member_mobilephone'],
                    'bonus_transfer_note' => "Bonus transfer " . $val['member_ref_network_code'],
                    'bonus_transfer_datetime' => $datetime,
                    'bonus_transfer_adm_charge_percent' => $admin_charge['percent'],
                    'bonus_transfer_adm_charge_value' => $admin_value,
                    'bonus_transfer_nett' => $val['bonus_total'] - $admin_value,
                );
                $arr_update_bonus = $bonus_response = array();
                $str_update = "";
                foreach ($arr_bonus_name as $key => $value) {
                    $arr_update_bonus['bonus_' . $value . "_paid"] = "bonus_" . $value . "_paid + " . $val['bonus_' . $value];
                    $arr_trf['bonus_transfer_bonus_' . $value] = $val['bonus_' . $value];
                    $bonus_response['bonus_transfer_bonus_' . $value] = $val['bonus_' . $value];
                    $str_update .= "bonus_" . $value . "_paid = bonus_" . $value . "_paid + " . $val['bonus_' . $value] . ",";
                }
                if ($str_update != "") {
                    $str_update = substr($str_update, 0, -1);
                }
                if (!$this->db->table('bin_bonus_transfer')->insert($arr_trf)) {
                    throw new \Exception("Gagal insert bonus transfer", 1);
                }
                $sql_update = "
                    UPDATE bin_bonus
                    SET bonus_total_paid = bonus_total_paid + {$val['bonus_total']}, $str_update
                    WHERE bonus_network_id = {$val['bonus_network_id']}
                ";
                if (!$this->db->query($sql_update)) {
                    throw new \Exception("Kesalahan pada database saat update total komisi.", 1);
                }
                $arr_trf_response = array(
                    'bonus_transfer_code' => $arr_trf['bonus_transfer_code'],
                    'bonus_transfer_status' => $arr_trf['bonus_transfer_status'],
                    'bonus_transfer_network_code' => $arr_trf['bonus_transfer_network_code'],
                    'bonus_transfer_member_name' => $arr_trf['bonus_transfer_member_name'],
                    'bonus_transfer_total_bonus' => $arr_trf['bonus_transfer_total_bonus'],
                    'bonus_transfer_adm_charge_value' => $arr_trf['bonus_transfer_adm_charge_value'],
                    'bonus_transfer_nett' => $arr_trf['bonus_transfer_nett'],
                    'bonus_transfer_bank_name' => $arr_trf['bonus_transfer_bank_name'],
                    'bonus_transfer_bank_account_name' => $arr_trf['bonus_transfer_bank_account_name'],
                    'bonus_transfer_bank_account_no' => $arr_trf['bonus_transfer_bank_account_no'],
                    'bonus_transfer_mobilephone' => $arr_trf['bonus_transfer_mobilephone'],
                    'bonus_transfer_datetime' => $arr_trf['bonus_transfer_datetime'],
                );
                $fixed_response = array_merge($arr_trf_response, $bonus_response);
                $arr_response['data'][] = $fixed_response;
            }
        } catch (\Exception $e) {
            $arr_response['status'] = 400;
            $arr_response['msg'] = $e->getMessage();
        }

        if ($this->db->transStatus() === TRUE && $arr_response['status'] == 200) {
            $this->db->transCommit();
        } else {
            $this->db->transRollback();
        }
        return $arr_response;
    }

    public function import_excel($filename, $datetime, $administrator_id, $administrator_name)
    {
        $arr_response['status'] = 200;
        $arr_response['data'] = array();
        $success = $pending = $rejected = 0;
        try {
            $this->db->transBegin();
            if (!$filename) {
                throw new \Exception("Format file salah.", 1);
            }

            $url = UPLOAD_PATH . 'file/excel/' . $filename;
            $reader = new Xlsx_reader();
            $spreadsheet = $reader->load($url);
            $spreadsheet->setActiveSheetIndex(0);

            $row = 5;

            $arr_data = [];
            while ($spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue() != NULL) {
                $tf_code = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
                $status = $spreadsheet->getActiveSheet()->getCell('B' . $row)->getValue();
                if ($status == 'YA') {
                    $arr_data['results'][] = [
                        'bonus_transfer_code' => $tf_code,
                        'bonus_transfer_status' => 'success',
                    ];
                    $success++;
                } else if ($status == 'TIDAK') {
                    $arr_data['results'][] = [
                        'bonus_transfer_code' => $tf_code,
                        'bonus_transfer_status' => 'failed',
                    ];
                    $rejected++;
                } else {
                    $pending++;
                }
                $row++;
            }

            if (!empty($arr_data)) {
                $arr_success_tf = array();
                $pending = count($arr_data['results']);
                foreach ($arr_data['results'] as $key => $row) {
                    $data_tf = $this->get_data_transfer($row['bonus_transfer_code']);
                    if ($row['bonus_transfer_status'] == 'success') {
                        if (!empty($data_tf)) {
                            $check_tf_code = $this->check_approved($data_tf['bonus_transfer_code']);
                            if (empty($check_tf_code) && $data_tf['bonus_transfer_status'] == 'pending') {
                                $sql = "UPDATE bin_bonus_transfer
                                    SET bonus_transfer_status = '" . $row['bonus_transfer_status'] . "',
                                    bonus_transfer_status_update_administrator_id = '$administrator_id',
                                    bonus_transfer_status_update_administrator_name = '$administrator_name',
                                    bonus_transfer_status_update_datetime = '$datetime'
                                    WHERE bonus_transfer_code = '" . $row['bonus_transfer_code'] . "'
                                ";
                                $update = $this->db->query($sql);
                                if (!$update) {
                                    throw new \Exception("Gagal update status transfer", 1);
                                }
                                $arr_success_tf = array(
                                    'bonus_transfer_network_id' => $data_tf['bonus_transfer_network_id'],
                                    'bonus_transfer_network_code' => $data_tf['bonus_transfer_network_code'],
                                    'bonus_transfer_member_name' => $data_tf['bonus_transfer_member_name'],
                                    'bonus_transfer_category' => $data_tf['bonus_transfer_category'],
                                    'bonus_transfer_code' => $data_tf['bonus_transfer_code'],
                                    'bonus_transfer_total_bonus' => $data_tf['bonus_transfer_total_bonus'],
                                    'bonus_transfer_adm_charge_percent' => $data_tf['bonus_transfer_adm_charge_percent'],
                                    'bonus_transfer_adm_charge_value' => $data_tf['bonus_transfer_adm_charge_value'],
                                    'bonus_transfer_nett' => $data_tf['bonus_transfer_nett'],
                                    'bonus_transfer_bank_id' => $data_tf['bonus_transfer_bank_id'],
                                    'bonus_transfer_bank_name' => $data_tf['bonus_transfer_bank_name'],
                                    'bonus_transfer_bank_city' => $data_tf['bonus_transfer_bank_city'],
                                    'bonus_transfer_bank_branch' => $data_tf['bonus_transfer_bank_branch'],
                                    'bonus_transfer_bank_account_name' => $data_tf['bonus_transfer_bank_account_name'],
                                    'bonus_transfer_bank_account_no' => $data_tf['bonus_transfer_bank_account_no'],
                                    'bonus_transfer_mobilephone' => $data_tf['bonus_transfer_mobilephone'],
                                    'bonus_transfer_note' => $data_tf['bonus_transfer_note'],
                                    'bonus_transfer_datetime' => $datetime,
                                    'bonus_transfer_status_administrator_id' => $administrator_id,
                                    'bonus_transfer_status_administrator_name' => $administrator_name,
                                    'bonus_transfer_bonus_sponsor' => $data_tf['bonus_transfer_bonus_sponsor'],
                                    'bonus_transfer_bonus_node' => $data_tf['bonus_transfer_bonus_node'],
                                    'bonus_transfer_bonus_match' => $data_tf['bonus_transfer_bonus_match'],
                                    'bonus_transfer_bonus_gen_node' => $data_tf['bonus_transfer_bonus_gen_node'],
                                    'bonus_transfer_bonus_gen_match' => $data_tf['bonus_transfer_bonus_gen_match'],
                                    'bonus_transfer_bonus_gen_sponsor' => $data_tf['bonus_transfer_bonus_gen_sponsor'],
                                    'bonus_transfer_bonus_ro' => $data_tf['bonus_transfer_bonus_ro'],
                                    'bonus_transfer_bonus_unilevel' => $data_tf['bonus_transfer_bonus_unilevel'],
                                    'bonus_transfer_bonus_sponsor_ro' => $data_tf['bonus_transfer_bonus_sponsor_ro'],
                                    'bonus_transfer_bonus_unilevel_ro' => $data_tf['bonus_transfer_bonus_unilevel_ro'],
                                    'bonus_transfer_bonus_old' => $data_tf['bonus_transfer_bonus_old'],
                                );
                                if (!empty($arr_success_tf)) {
                                    if (!$this->db->table('bin_bonus_transfer_approved')->insert($arr_success_tf)) {
                                        throw new \Exception("Gagal insert bonus transfer success", 1);
                                    }
                                }
                            }
                        }
                    } else if ($row['bonus_transfer_status'] == 'failed') {
                        if (!empty($data_tf)) {
                            $check_tf_code = $this->check_approved($data_tf['bonus_transfer_code']);
                            if (empty($check_tf_code) && $data_tf['bonus_transfer_status'] == 'pending') {
                                $sql = "UPDATE bin_bonus_transfer
                                    SET bonus_transfer_status = '" . $row['bonus_transfer_status'] . "',
                                    bonus_transfer_status_update_administrator_id = '$administrator_id',
                                    bonus_transfer_status_update_administrator_name = '$administrator_name',
                                    bonus_transfer_status_update_datetime = '$datetime'
                                    WHERE bonus_transfer_code = '" . $row['bonus_transfer_code'] . "'
                                ";
                                $update = $this->db->query($sql);
                                if (!$update) {
                                    throw new \Exception("Gagal update status transfer", 1);
                                }
                                $bonus_config = json_decode(BIN_CONFIG_BONUS);
                                $str_update = "";
                                foreach ($bonus_config as $key1 => $row1) {
                                    if ($row1->active_bonus == true) {
                                        $str_update .= "bonus_" . $row1->name . "_paid = bonus_" . $row1->name . "_paid - " . $data_tf["bonus_transfer_bonus_" . $row1->name] . ",";
                                    }
                                }
                                if ($data_tf['bonus_transfer_category'] == 'weekly') {
                                    $str_update .= "bonus_old_paid = bonus_old_paid - " . $data_tf["bonus_transfer_bonus_old"] . ",";
                                }
                                if ($str_update != "") {
                                    $str_update = substr($str_update, 0, -1);
                                }
                                $sql_update_bin_bonus = "
                                    UPDATE bin_bonus
                                    SET bonus_total_paid = (bonus_total_paid - {$data_tf['bonus_transfer_total_bonus']}), $str_update
                                    WHERE bonus_network_id = {$data_tf['bonus_transfer_network_id']}
                                ";
                                if (!$this->db->query($sql_update_bin_bonus)) {
                                    throw new \Exception("Gagal update bin bonus paid", 1);
                                }
                            }
                        }
                    }
                    $arr_response['data']['results'][] = $row;
                }
            }
            $arr_response['data']['pending'] = $pending - ($success + $rejected);
            $arr_response['data']['approved'] = $success;
            $arr_response['data']['rejected'] = $rejected;
        } catch (\Exception $e) {
            $arr_response['status'] = 400;
            $arr_response['msg'] = $e->getMessage();
        }
        if ($arr_response['status'] == 200 && $this->db->transStatus() == TRUE) {
            $this->db->transCommit();
        } else {
            $this->db->transRollback();
        }
        return $arr_response;
    }

    public function export_excel_history_success($periode, $request)
    {
        $table_name = 'bin_bonus_transfer_approved';
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
        $columns['sort'] = $request->getPost('sort');
        $columns['dir'] = $request->getPost('dir');
        $join_table = "";
        $where_condition = "bonus_transfer_category = '$periode'";
        $results = Services::DataTableLib()->getListDataExcelCustom($columns, $table_name, $join_table, $where_condition);
        $title = "Histori Transfer Komisi";
        $this->exportToExcelHistorySuccess($table_head, $results, $title, $arr_bonus);
    }

    public function exportToExcelHistorySuccess($tableHead, $data, $title = '', $arr_bonus)
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
            foreach ($value as $key => $value2) {
                $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, $value2);
                if ($key == 'bonus_transfer_datetime') {
                    $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, Services::FunctionLib()->convertDatetime($value2, 'id'));
                }

                if ($key == 'bonus_transfer_total_bonus' || $key == 'bonus_transfer_adm_charge_value' || $key == 'bonus_transfer_nett') {
                    $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, 'Rp. ' . number_format($value2, 0, ',', '.'));
                }

                foreach ($arr_bonus as $key1 => $row_bonus) {
                    if ($key == $row_bonus) {
                        $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, 'Rp. ' . number_format($value2, 0, ',', '.'));
                    }
                }

                $charColumn++;
            }
            $charColumnIndex++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = strtolower(str_replace(' ', '', $title)) . '-' . date('dmy H:i:s');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        $writer->save('php://output');
    }

    public function get_data_transfer($transfer_code)
    {
        $sql = "SELECT * FROM bin_bonus_transfer WHERE bonus_transfer_code = '$transfer_code' AND bonus_transfer_status = 'pending'";
        return $this->db->query($sql)->getRowArray();
    }

    public function check_approved($transfer_code)
    {
        $sql = "SELECT bonus_transfer_id FROM bin_bonus_transfer_approved WHERE bonus_transfer_code = '$transfer_code'";
        return $this->db->query($sql)->getRow('bonus_transfer_id');
    }

    private function get_transfer_code()
    {
        $sql = "SELECT bonus_transfer_code FROM bin_bonus_transfer ORDER BY bonus_transfer_id DESC";
        $code = $this->db->query($sql)->getRow('bonus_transfer_code');
        if (!empty($code)) {
            $last_code = substr($code, 3);
            $tf_code = "TF-" . ($last_code + 1);
        } else {
            $tf_code = "TF-1000001";
        }
        return $tf_code;
    }

    public function get_data_download()
    {
        $sql = "SELECT bonus_transfer_status, bonus_transfer_datetime, bonus_transfer_nett FROM bin_bonus_transfer";
        return $this->db->query($sql)->getResult();
    }

    public function get_data_tf_code_by_datetime($tanggal)
    {
        $sql = "SELECT bonus_transfer_code AS tf_code FROM bin_bonus_transfer WHERE bonus_transfer_status = 'success' AND bonus_transfer_datetime = '$tanggal'";
        $data = $this->db->query($sql)->getResult();
        $result = "";
        if (!empty($data)) {
            foreach ($data as $key => $row) {
                $result .= "'" . $row->tf_code . "',";
            }
        }
        if ($result != "") {
            $result = substr($result, 0, -1);
        }
        return $result;
    }

    // public function exportToExcelOld($tableHead, $data, $title = '', $arr_bonus, $arr_bonus_name, $datetime, $admin_charge, $periode)
    // {
    //     if (count($data) > 0) {
    //         foreach ($data as $key => $val) {
    //             $data_transfer = $this->save_transfer($data, $arr_bonus_name, $datetime, $admin_charge, $periode);
    //             if ($data_transfer['status'] == 200) {
    //                 $data = $data_transfer['data'];
    //             }
    //         }
    //     }

    //     $tableHeadStyle = [
    //         'font' => [
    //             'color' => [
    //                 'rgb' => 'FFFFFF'
    //             ],
    //         ],
    //         'fill' => [
    //             'fillType' => Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'rgb' => '538ED5'
    //             ]
    //         ],
    //     ];

    //     //even row
    //     $even = [
    //         'fill' => [
    //             'fillType' => Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'rgb' => 'FFE4C4'
    //             ]
    //         ],
    //     ];

    //     //odd row
    //     $odd = [
    //         'fill' => [
    //             'fillType' => Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'rgb' => '6495ED'
    //             ]
    //         ],
    //     ];
    //     $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

    //     $sheet = $spreadsheet->getActiveSheet();

    //     //set default font 
    //     $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
    //     //set font style 
    //     $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
    //     $spreadsheet->getActiveSheet()->setCellValue('A1', $title);
    //     $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
    //     $spreadsheet->getActiveSheet()->setCellValue('A2', 'Tanggal : ' . date('d,M Y H:i:s'));
    //     //set cell alignment
    //     $spreadsheet->getActiveSheet()->getStyle('A:' . strtoupper(chr((count($tableHead) - 1) + 65)))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
    //     for ($i = 0; $i < count($tableHead); $i++) {
    //         $spreadsheet->getActiveSheet()->getColumnDimension(strtoupper(chr($i + 65)))->setAutoSize(true);
    //         $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($i + 65)) . '4', $tableHead[$i]);
    //     }
    //     $spreadsheet->getActiveSheet()->getStyle('A4:' . strtoupper(chr((count($tableHead) - 1) + 65)) . '4')->getFont()->setSize(13);
    //     $spreadsheet->getActiveSheet()->getStyle('A4:' . strtoupper(chr((count($tableHead) - 1) + 65)) . '4')->getFont()->setBold(TRUE);
    //     $sheet->getCell('A4')->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //     //background color
    //     $spreadsheet->getActiveSheet()->getStyle('A4:' . strtoupper(chr((count($tableHead) - 1) + 65)) . '4')->applyFromArray($tableHeadStyle);
    //     $charColumnIndex = 5;
    //     foreach ($data as $value) {
    //         $charColumn = 0;
    //         $validation = $sheet->getCell('B'.$charColumnIndex)->getDataValidation();
    //         $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
    //         $validation->setFormula1('"YA, TIDAK"');
    //         $validation->setAllowBlank(false);
    //         $validation->setShowDropDown(true);
    //         $validation->setShowInputMessage(true);
    //         $sheet->getCell('B'.$charColumnIndex)->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //         foreach ($value as $key => $value2) {
    //             $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, $value2);
    //             if ($key == 'bonus_transfer_datetime') {
    //                 $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, Services::FunctionLib()->convertDatetime($value2, 'id'));
    //             }

    //             if ($key == 'bonus_transfer_total_bonus' || $key == 'bonus_transfer_adm_charge_percent' || $key == 'bonus_transfer_adm_charge_value') {
    //                 $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, 'Rp. '.number_format($value2, 0, ',', '.'));
    //             }

    //             if($key == 'bonus_transfer_bank_account_no'){
    //                 $sheet->getCell('G'.$charColumnIndex)->getStyle()->getNumberFormat()->setFormatCode('#');
    //             }


    //             foreach ($arr_bonus_name as $key1 => $row_bonus) {
    //                 if ($key == 'bonus_transfer_bonus_'.$row_bonus) {
    //                     $spreadsheet->getActiveSheet()->setCellValue(strtoupper(chr($charColumn + 65)) . $charColumnIndex, 'Rp. '.number_format($value2, 0, ',', '.'));
    //                 }
    //             }

    //             $charColumn++;
    //         }
    //         $charColumnIndex++;
    //     }
    //     $writer = new Xlsx($spreadsheet);
    //     $filename = strtolower(str_replace(' ', '', $title)) . '-' . date('dmy H:i:s');
    //     header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    //     $writer->save('php://output');
    // }

}
