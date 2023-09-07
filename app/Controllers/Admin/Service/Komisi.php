<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\KomisiModel;
use App\Libraries\Notification;
use Config\Services;

class Komisi extends BaseServiceController
{
    protected $min_transfer;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->KomisiModel = new KomisiModel();
        $this->notification_lib = new Notification();
        $this->db = \Config\Database::connect();
        $this->transfer_condition = " saldo > 0 AND member_bank_id > 0 AND network_is_active = 1 AND network_is_suspended = 0 AND member_bank_account_no IS NOT NULL";
        $this->min_transfer = CONFIG_WITHDRAWAL_MIN_VALUE;
        $this->tax_service = service('Tax');
        $this->payment_service = service("Payment");
    }

    public function getDataBonus($type = '')
    {
        $tableName = 'sys_bonus';
        $columns = [
            "bonus_member_id",
            "bonus_sponsor_acc",
            "bonus_sponsor_paid",
            "bonus_match_acc",
            "bonus_match_paid",
            "bonus_ro_match_acc",
            "bonus_ro_match_paid",
            "bonus_ro_sponsor_acc",
            "bonus_ro_sponsor_paid",
            "bonus_royalty_leadership_acc",
            "bonus_royalty_leadership_paid",
            "bonus_royalty_qualified_acc",
            "bonus_royalty_qualified_paid",
            "network_code",
            "member_name",
            "bonus_sponsor_acc +
            bonus_ro_sponsor_acc +
            bonus_match_acc +
            bonus_ro_match_acc +
            bonus_royalty_leadership_acc +
            bonus_royalty_qualified_acc AS bonus_acc",
            "bonus_sponsor_paid +
            bonus_ro_sponsor_paid +
            bonus_match_paid +
            bonus_ro_match_paid +
            bonus_royalty_leadership_paid +
            bonus_royalty_qualified_paid AS bonus_paid",
            "(bonus_sponsor_acc - bonus_sponsor_paid) +
            (bonus_ro_sponsor_acc - bonus_ro_sponsor_paid) +
            (bonus_match_acc - bonus_match_paid) +
            (bonus_ro_match_acc - bonus_ro_match_paid) +
            (bonus_royalty_leadership_acc - bonus_royalty_leadership_paid) +
            (bonus_royalty_qualified_acc - bonus_royalty_qualified_paid) AS saldo",
        ];
        $joinTable = '
        JOIN sys_member ON member_id = bonus_member_id
        JOIN sys_network ON member_id = network_member_id
        ';
        $whereCondition = '';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                $data['results'][$key]['bonus_acc_formatted'] = $this->functionLib->format_nominal("Rp ", $row['bonus_acc'], 2);
                $data['results'][$key]['bonus_paid_formatted'] = $this->functionLib->format_nominal("Rp ", $row['bonus_paid'], 2);
                $data['results'][$key]['saldo_formatted'] = $this->functionLib->format_nominal("Rp ", $row['saldo'], 2);
            }
        }

        $this->createRespon(200, 'Data Bonus Member', $data);
    }

    public function getDataBonusTransfer()
    {
        $tableName = "sys_bonus";
        $columns = [
            "bonus_member_id",
            "bonus_limit",
            "member_account_username",
            "bonus_sponsor_acc",
            "bonus_sponsor_paid",
            "bonus_sponsor_acc - bonus_sponsor_paid" => "bonus_sponsor_balance",
            "bonus_gen_node_acc",
            "bonus_gen_node_paid",
            "bonus_gen_node_acc - bonus_gen_node_paid" => "bonus_gen_node_balance",
            "bonus_power_leg_acc",
            "bonus_power_leg_paid",
            "bonus_power_leg_acc - bonus_power_leg_paid" => "bonus_power_leg_balance",
            "bonus_matching_leg_acc",
            "bonus_matching_leg_paid",
            "bonus_matching_leg_acc - bonus_matching_leg_paid" => "bonus_matching_leg_balance",
            "bonus_cash_reward_acc",
            "bonus_cash_reward_paid",
            "bonus_cash_reward_acc - bonus_cash_reward_paid" => "bonus_cash_reward_balance",
            "bonus_sponsor_acc +
            bonus_gen_node_acc +
            bonus_power_leg_acc +
            bonus_matching_leg_acc +
            bonus_cash_reward_acc" => "bonus_total_acc",
            "bonus_sponsor_paid +
            bonus_gen_node_paid +
            bonus_power_leg_paid +
            bonus_matching_leg_paid +
            bonus_cash_reward_paid" => "bonus_total_paid",
            "(bonus_sponsor_acc - bonus_sponsor_paid) +
            (bonus_gen_node_acc - bonus_gen_node_paid) +
            (bonus_power_leg_acc - bonus_power_leg_paid) +
            (bonus_matching_leg_acc - bonus_matching_leg_paid) +
            (bonus_cash_reward_acc - bonus_cash_reward_paid)" => "saldo",
            "member_name",
            "member_bank_id",
            "member_bank_name",
            "member_bank_account_name",
            "member_bank_account_no",
            "member_tax_no",
            "tax_member_year",
            "tax_member_last_update_datetime"
        ];
        $joinTable = " 
        JOIN sys_member ON member_id = bonus_member_id 
        JOIN sys_member_account ON member_account_member_id = member_id
        LEFT JOIN tax_member ON member_id = tax_member_id";
        $whereCondition = " member_bank_id != 0 AND member_bank_account_name != '' AND member_bank_account_no != ''
        AND (
            (bonus_sponsor_acc - bonus_sponsor_paid) +
            (bonus_gen_node_acc - bonus_gen_node_paid) +
            (bonus_power_leg_acc - bonus_power_leg_paid) +
            (bonus_matching_leg_acc - bonus_matching_leg_paid) +
            (bonus_cash_reward_acc - bonus_cash_reward_paid)
        ) >= {$this->min_transfer}
        AND (
            bonus_sponsor_paid + 
            bonus_gen_node_paid +
            bonus_power_leg_paid +
            bonus_matching_leg_paid
        ) < bonus_limit";
        $groupBy = "";
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                $total_paid_without_reward = $data['results'][$key]["bonus_sponsor_paid"] + $data['results'][$key]["bonus_gen_node_paid"] + $data['results'][$key]["bonus_power_leg_paid"] + $data['results'][$key]["bonus_matching_leg_paid"];
                if ($data['results'][$key]['bonus_sponsor_balance'] + $data['results'][$key]['bonus_gen_node_balance'] + $data['results'][$key]['bonus_power_leg_balance'] + $data['results'][$key]['bonus_matching_leg_balance'] > $data['results'][$key]['bonus_limit'] - $total_paid_without_reward) {
                    if ($data['results'][$key]['bonus_sponsor_balance'] >= ($data['results'][$key]['bonus_limit'] - $total_paid_without_reward)) {
                        $data['results'][$key]['bonus_sponsor_balance'] = $data['results'][$key]['bonus_limit'] - $total_paid_without_reward;
                        $data['results'][$key]['bonus_gen_node_balance'] = 0;
                        $data['results'][$key]['bonus_power_leg_balance'] = 0;
                        $data['results'][$key]['bonus_matching_leg_balance'] = 0;
                    } else if ($data['results'][$key]['bonus_gen_node_balance'] >= ($data['results'][$key]['bonus_limit'] - $total_paid_without_reward - $data['results'][$key]['bonus_sponsor_balance'])) {
                        $data['results'][$key]['bonus_gen_node_balance'] = $data['results'][$key]['bonus_limit'] - $total_paid_without_reward - $data['results'][$key]['bonus_sponsor_balance'];
                        $data['results'][$key]['bonus_gen_node_balance'] = ($data['results'][$key]['bonus_gen_node_balance'] < 0) ? 0 : $data['results'][$key]['bonus_gen_node_balance'];
                        $data['results'][$key]['bonus_power_leg_balance'] = 0;
                        $data['results'][$key]['bonus_matching_leg_balance'] = 0;
                    } else if ($data['results'][$key]['bonus_power_leg_balance'] >= ($data['results'][$key]['bonus_limit'] - $total_paid_without_reward - $data['results'][$key]['bonus_sponsor_balance'] - $data['results'][$key]['bonus_gen_node_balance'])) {
                        $data['results'][$key]['bonus_power_leg_balance'] = $data['results'][$key]['bonus_limit'] - $total_paid_without_reward - $data['results'][$key]['bonus_sponsor_balance'] - $data['results'][$key]['bonus_gen_node_balance'];
                        $data['results'][$key]['bonus_power_leg_balance'] = ($data['results'][$key]['bonus_power_leg_balance'] < 0) ? 0 : $data['results'][$key]['bonus_power_leg_balance'];
                        $data['results'][$key]['bonus_matching_leg_balance'] = 0;
                    } else if ($data['results'][$key]['bonus_matching_leg_balance'] >= ($data['results'][$key]['bonus_limit'] - $total_paid_without_reward - $data['results'][$key]['bonus_sponsor_balance'] - $data['results'][$key]['bonus_gen_node_balance'] - $data['results'][$key]['bonus_power_leg_balance'])) {
                        $data['results'][$key]['bonus_matching_leg_balance'] = $data['results'][$key]['bonus_limit'] - $total_paid_without_reward - $data['results'][$key]['bonus_sponsor_balance'] - $data['results'][$key]['bonus_gen_node_balance'] - $data['results'][$key]['bonus_power_leg_balance'];
                        $data['results'][$key]['bonus_matching_leg_balance'] = ($data['results'][$key]['bonus_matching_leg_balance'] < 0) ? 0 : $data['results'][$key]['bonus_matching_leg_balance'];
                    }
                }
                $data['results'][$key]['saldo'] = $data['results'][$key]['bonus_sponsor_balance'] + $data['results'][$key]['bonus_gen_node_balance'] + $data['results'][$key]['bonus_power_leg_balance'] + $data['results'][$key]['bonus_matching_leg_balance'] + $data['results'][$key]['bonus_cash_reward_balance'];

                $data['results'][$key]['bonus_acc_formatted'] = $this->functionLib->format_nominal("Rp ", $data['results'][$key]['bonus_total_acc'], 2);
                $data['results'][$key]['bonus_paid_formatted'] = $this->functionLib->format_nominal("Rp ", $data['results'][$key]['bonus_total_paid'], 2);
                $data['results'][$key]['saldo_formatted'] = $this->functionLib->format_nominal("Rp ", $data['results'][$key]['saldo'], 2);
                $data["results"][$key]["bonus_total_balance"] = $data['results'][$key]["bonus_sponsor_balance"] + $data['results'][$key]["bonus_gen_node_balance"] + $data['results'][$key]["bonus_power_leg_balance"] + $data['results'][$key]["bonus_matching_leg_balance"] + $data['results'][$key]["bonus_cash_reward_balance"];
                $data["results"][$key]["bonus_adm_charge_value"] = CONFIG_WITHDRAWAL_ADM_CHARGE_TYPE == "percent" ? CONFIG_WITHDRAWAL_ADM_CHARGE_PERCENT / 100 * $data["results"][$key]["bonus_total_balance"] : CONFIG_WITHDRAWAL_ADM_CHARGE_VALUE;
                $data["results"][$key]["bonus_percent_tax"] = $data["results"][$key]["member_tax_no"] != '' ? 5  : 6;
                $data['results'][$key]['bonus_total_tax'] = 0;
                // if ($data['results'][$key]['member_tax_no'] != '') {
                //     $data['results'][$key]['bonus_total_tax'] = $this->tax_service->calculate_tax_member($data['results'][$key]['bonus_member_id'], ($data['results'][$key]['saldo'] - $data["results"][$key]["bonus_adm_charge_value"]), $data['results'][$key]['tax_member_year'], $data['results'][$key]['tax_member_last_update_datetime'], $data['results'][$key]['member_tax_no'], date("Y-m-d H:i:s"))['tax_npwp_value'];
                // } else {
                //     $data['results'][$key]['bonus_total_tax'] = $this->tax_service->calculate_tax_member($data['results'][$key]['bonus_member_id'], ($data['results'][$key]['saldo'] - $data["results"][$key]["bonus_adm_charge_value"]), $data['results'][$key]['tax_member_year'], $data['results'][$key]['tax_member_last_update_datetime'], $data['results'][$key]['member_tax_no'], date("Y-m-d H:i:s"))['tax_nonnpwp_value'];
                // }

                $data["results"][$key]["bonus_total_transfer"] = ($data["results"][$key]["bonus_total_balance"] - $data["results"][$key]["bonus_adm_charge_value"]) - $data["results"][$key]["bonus_total_tax"];
            }
        }
        $this->createRespon(200, 'Data Bonus Member', $data);
    }

    public function getDetailBonus()
    {
        $arr_response['status'] = 200;
        $arr_response['results'] = array();
        $member_id = $this->request->getGet('member_id');
        $detail_komisi = $this->db->table("sys_bonus")->getWhere(["bonus_member_id" => $member_id])->getRowArray();
        $bonus = ["sponsor" => "Sponsor", "match" => "Pasangan", "ro_sponsor" => "Sponsor RO", "ro_match" => "Pasangan RO", "royalty_leadership" => "Royalty Leadership", "royalty_qualified" => "Royalty Qualified"];
        foreach ($bonus as $key => $value) {
            $arr_response["results"][] = [
                'label' => strtoupper($value),
                'name_acc' => "DITERIMA",
                'value_acc' => $detail_komisi['bonus_' . $key . '_acc'],
                'name_paid' => "DIBAYARKAN",
                'value_paid' => $detail_komisi['bonus_' . $key . '_paid'],
                'saldo' => $detail_komisi['bonus_' . $key . '_acc'] - $detail_komisi['bonus_' . $key . '_paid'],
            ];
        }
        $this->createRespon(200, 'Data Bonus Member', $arr_response);
    }

    public function getDataHistoryBonus()
    {
        $tableName = 'sys_bonus_log';
        $columns = array(
            'bonus_log_id',
            'bonus_log_value',
            'bonus_log_type',
            'bonus_log_date',
            'bonus_log_note',
            'bonus_log_member_id',
            'member_ref_network_code',
        );
        $joinTable = '
        JOIN sys_member ON sys_member.member_id = sys_bonus_log.bonus_log_member_id
        JOIN bin_member_ref ON bin_member_ref.member_ref_member_id = sys_bonus_log.bonus_log_member_id
        ';
        $whereCondition = '';
        $member_ref_network_code = $this->request->getGet('member_ref_network_code');
        if ($member_ref_network_code) {
            $whereCondition .= 'member_ref_network_code = ' . $member_ref_network_code;
        }
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['bonus_log_date']) {
                    $data['results'][$key]['bonus_log_date_formatted'] = $this->functionLib->convertDatetime($row['bonus_log_date']);
                }
                $data['results'][$key]['bonus_log_value_formatted'] = $this->functionLib->format_nominal("Rp ", $row['bonus_log_value'], 2);
            }
        }
        $this->createRespon(200, 'Data Riwayat Bonus Member', $data);
    }

    public function getSummaryHistoryTransfer()
    {
        $tableName = 'sys_bonus_transfer';
        $columns = array(
            'bonus_transfer_id',
            'bonus_transfer_datetime',
            'bonus_transfer_date',
            'SUM(bonus_transfer_sponsor)' => 'bonus_transfer_sponsor',
            'SUM(bonus_transfer_gen_node)' => 'bonus_transfer_gen_node',
            'SUM(bonus_transfer_power_leg)' => 'bonus_transfer_power_leg',
            'SUM(bonus_transfer_matching_leg)' => 'bonus_transfer_matching_leg',
            'SUM(bonus_transfer_cash_reward)' => 'bonus_transfer_cash_reward',
            'SUM(bonus_transfer_total)' => 'bonus_transfer_total',
            'SUM(bonus_transfer_adm_charge_value)' => 'bonus_transfer_adm_charge_value',
            'SUM(bonus_transfer_tax_value)' => 'bonus_transfer_tax_value'
        );
        $joinTable = '';
        $whereCondition = '';
        $groupBy = 'GROUP BY bonus_transfer_datetime';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                $data['results'][$key]['bonus_transfer_datetime_formatted'] = $this->functionLib->convertDatetime($row['bonus_transfer_datetime'], 'id', 'text', '.', ':');
                $data['results'][$key]['bonus_transfer_subtotal'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_total'] + $row['bonus_transfer_adm_charge_value'] + $row['bonus_transfer_tax_value'], 2);
                $data['results'][$key]['bonus_transfer_total'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_total'], 2);
                $data['results'][$key]['bonus_transfer_adm_charge_value'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_adm_charge_value'], 2);
                $data['results'][$key]['bonus_transfer_tax_value'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_tax_value'], 2);
                $data['results'][$key]['bonus_transfer_sponsor'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_sponsor'], 2);
                $data['results'][$key]['bonus_transfer_matching_leg'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_matching_leg'], 2);
                $data['results'][$key]['bonus_transfer_gen_node'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_gen_node'], 2);
                $data['results'][$key]['bonus_transfer_power_leg'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_power_leg'], 2);
                $data['results'][$key]['bonus_transfer_cash_reward'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_cash_reward'], 2);
            }
        }
        $this->createRespon(200, 'Data Riwayat Transfer Bonus Member', $data);
    }

    public function getDataHistoryTransfer()
    {
        $tableName = 'sys_bonus_transfer';
        $columns = array(
            'bonus_transfer_id',
            'bonus_transfer_datetime',
            'bonus_transfer_total',
            'bonus_transfer_adm_charge_value',
            'member_name' => 'bonus_transfer_member_name',
            'member_mobilephone' => 'bonus_transfer_mobilephone',
            'bonus_transfer_member_bank_name',
            'bonus_transfer_member_bank_account_name',
            'bonus_transfer_member_bank_account_no',
            'bonus_transfer_sponsor',
            'bonus_transfer_gen_node',
            'bonus_transfer_power_leg',
            'bonus_transfer_matching_leg',
            'bonus_transfer_cash_reward',
            'bonus_transfer_member_id',
            'bonus_transfer_status'
        );
        $joinTable = 'JOIN sys_member ON member_id = bonus_transfer_member_id';
        $whereCondition = '';
        $date = $this->request->getGet('date');
        if ($date) {
            $whereCondition .= 'bonus_transfer_datetime = "' . $date . '"';
        }
        $groupBy = '';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);
        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                $data['results'][$key]['bonus_transfer_datetime'] = $this->functionLib->convertDatetime($row['bonus_transfer_datetime'], 'id', 'text', '.', ':', false);
                $data['results'][$key]['bonus_transfer_subtotal'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_total'] + $row['bonus_transfer_adm_charge_value'], 2);
                $data['results'][$key]['bonus_transfer_adm_charge_value'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_adm_charge_value'], 2);
                $data['results'][$key]['bonus_transfer_total'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_total'], 2);
                $data['results'][$key]['bonus_transfer_sponsor'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_sponsor'], 2);
                $data['results'][$key]['bonus_transfer_matching_leg'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_matching_leg'], 2);
                $data['results'][$key]['bonus_transfer_gen_node'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_gen_node'], 2);
                $data['results'][$key]['bonus_transfer_power_leg'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_power_leg'], 2);
                $data['results'][$key]['bonus_transfer_cash_reward'] = $this->functionLib->format_nominal("Rp ", $row['bonus_transfer_cash_reward'], 2);
            }
        }
        $this->createRespon(200, 'Data Riwayat Transfer Bonus Member', $data);
    }

    public function addTransfer()
    {
        try {
            $this->db->transBegin();

            $data = $this->request->getPost('data') ?: [];
            $success = $failed = 0;

            // create disbursement
            $this->db->table("payment_disbursement")->insert(["disbursement_datetime" => date("Y-m-d H:i:s")]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambah data disbursement.", 1);
            }

            $disbursement_id = $this->db->insertID();

            $disbursement_uploaded_count = $disbursement_uploaded_amount = 0;

            $arr_success = [];
            $arr_disbursements = [];
            $datetime = date('Y-m-d H:i:s');
            foreach ($data as $arr_bonus_transfer) {
                // check saldo untuk multi hit
                $sql = "SELECT *, (bonus_sponsor_acc - bonus_sponsor_paid) AS sponsor_balance,
                (bonus_gen_node_acc - bonus_gen_node_paid) AS gen_node_balance,
                (bonus_power_leg_acc - bonus_power_leg_paid) AS power_leg_balance,
                (bonus_matching_leg_acc - bonus_matching_leg_paid) AS matching_leg_balance,
                (bonus_cash_reward_acc - bonus_cash_reward_paid) AS cash_reward_balance FROM sys_bonus
                WHERE bonus_member_id = {$arr_bonus_transfer["bonus_transfer_member_id"]}";
                $saldo = $this->db->query($sql)->getRow();
                if (
                    $saldo->sponsor_balance < $arr_bonus_transfer["bonus_transfer_sponsor"]
                    or $saldo->gen_node_balance < $arr_bonus_transfer["bonus_transfer_gen_node"]
                    or $saldo->power_leg_balance < $arr_bonus_transfer["bonus_transfer_power_leg"]
                    or $saldo->matching_leg_balance < $arr_bonus_transfer["bonus_transfer_matching_leg"]
                    or $saldo->cash_reward_balance < $arr_bonus_transfer["bonus_transfer_cash_reward"]
                ) {
                    throw new \Exception("Saldo {$arr_bonus_transfer["bonus_transfer_member_bank_account_name"]} tidak mencukupi.", 1);
                }
                if (
                    $saldo->bonus_sponsor_acc - $saldo->bonus_sponsor_paid < 0
                    or $saldo->bonus_gen_node_acc - $saldo->bonus_gen_node_paid < 0
                    or $saldo->bonus_power_leg_acc - $saldo->bonus_power_leg_paid < 0
                    or $saldo->bonus_matching_leg_acc - $saldo->bonus_matching_leg_paid < 0
                    or $saldo->bonus_cash_reward_acc - $saldo->bonus_cash_reward_paid < 0
                ) {
                    throw new \Exception("Saldo {$arr_bonus_transfer["bonus_transfer_member_bank_account_name"]} tidak mencukupi.", 1);
                }

                $params = [
                    "insert" => [
                        "bonus_transfer_member_id" => $arr_bonus_transfer["bonus_transfer_member_id"],
                        "bonus_transfer_sponsor" => $arr_bonus_transfer["bonus_transfer_sponsor"],
                        "bonus_transfer_gen_node" => $arr_bonus_transfer["bonus_transfer_gen_node"],
                        "bonus_transfer_power_leg" => $arr_bonus_transfer["bonus_transfer_power_leg"],
                        "bonus_transfer_matching_leg" => $arr_bonus_transfer["bonus_transfer_matching_leg"],
                        "bonus_transfer_cash_reward" => $arr_bonus_transfer["bonus_transfer_cash_reward"],
                        "bonus_transfer_total" => $arr_bonus_transfer["bonus_transfer_total"],
                        "bonus_transfer_member_bank_id" => $arr_bonus_transfer["bonus_transfer_member_bank_id"],
                        "bonus_transfer_member_bank_name" => $arr_bonus_transfer["bonus_transfer_member_bank_name"],
                        "bonus_transfer_member_bank_account_name" => $arr_bonus_transfer["bonus_transfer_member_bank_account_name"],
                        "bonus_transfer_member_bank_account_no" => $arr_bonus_transfer["bonus_transfer_member_bank_account_no"],
                        "bonus_transfer_adm_charge_type" => $arr_bonus_transfer["bonus_transfer_adm_charge_type"],
                        "bonus_transfer_adm_charge_percent" => $arr_bonus_transfer["bonus_transfer_adm_charge_percent"],
                        "bonus_transfer_adm_charge_value" => $arr_bonus_transfer["bonus_transfer_adm_charge_value"],
                        "bonus_transfer_tax_percent" => $arr_bonus_transfer["bonus_transfer_tax_percent"],
                        "bonus_transfer_tax_value" => $arr_bonus_transfer["bonus_transfer_tax_value"],
                        "bonus_transfer_date" => date("Y-m-d", strtotime($datetime)),
                        "bonus_transfer_datetime" => $datetime,
                    ],
                    "update" => [
                        "bonus_sponsor_paid" => $arr_bonus_transfer["bonus_transfer_sponsor"],
                        "bonus_gen_node_paid" => $arr_bonus_transfer["bonus_transfer_gen_node"],
                        "bonus_power_leg_paid" => $arr_bonus_transfer["bonus_transfer_power_leg"],
                        "bonus_matching_leg_paid" => $arr_bonus_transfer["bonus_transfer_matching_leg"],
                        "bonus_cash_reward_paid" => $arr_bonus_transfer["bonus_transfer_cash_reward"],
                    ],
                    "where" => ["bonus_member_id" => $arr_bonus_transfer["bonus_transfer_member_id"]],
                ];


                // print_r($params);
                // die();

                $processTransfer = $this->KomisiModel->processBonusTransfer($params, $datetime, session('admin')['admin_id'], session('admin')['admin_name']);

                if ($processTransfer['status'] == true) {
                    $amount = $arr_bonus_transfer["bonus_transfer_total"];

                    array_push($arr_success, [
                        'sponsor' => $arr_bonus_transfer["bonus_transfer_sponsor"],
                        'gen_node' => $arr_bonus_transfer["bonus_transfer_gen_node"],
                        'power_leg' => $arr_bonus_transfer["bonus_transfer_power_leg"],
                        'matching_leg' => $arr_bonus_transfer["bonus_transfer_matching_leg"],
                        'cash_reward' => $arr_bonus_transfer["bonus_transfer_cash_reward"],
                        'member_id' => $arr_bonus_transfer["bonus_transfer_member_id"],
                        'bonus_transfer_total' => $arr_bonus_transfer["bonus_transfer_total"],
                        'adm_charge' => $arr_bonus_transfer["bonus_transfer_adm_charge_value"],
                        'tax' => $arr_bonus_transfer["bonus_transfer_tax_value"]
                    ]);

                    // print_r($arr_success);
                    // die();

                    $arr_insert_disbursement_detail = [
                        'disbursement_detail_disbursement_id' => $disbursement_id,
                        'disbursement_detail_internal_id' => $processTransfer['id'],
                        'disbursement_detail_amount' => $amount,
                        'disbursement_detail_bank_code' => $arr_bonus_transfer["bonus_transfer_member_bank_name"],
                        'disbursement_detail_bank_account_name' => $arr_bonus_transfer["bonus_transfer_member_bank_account_name"],
                        'disbursement_detail_bank_account_number' => $arr_bonus_transfer["bonus_transfer_member_bank_account_no"],
                        'disbursement_detail_description' => PAYMENT_NOTE,
                    ];

                    $this->db->table("payment_disbursement_detail")->insert($arr_insert_disbursement_detail);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal menambah detail disbursement", 1);
                    }

                    $disbursement_detail_id = $this->db->insertID();

                    array_push($arr_disbursements, [
                        'amount' => (int)$amount,
                        'bank_code' => $this->db->table("ref_bank")->getWhere(["bank_id" => $arr_bonus_transfer["bonus_transfer_member_bank_id"]])->getRow("bank_code"),
                        'bank_account_name' => $arr_bonus_transfer["bonus_transfer_member_bank_account_name"],
                        'bank_account_number' => $arr_bonus_transfer["bonus_transfer_member_bank_account_no"],
                        'description' => PAYMENT_NOTE,
                        'external_id' => (string) $disbursement_detail_id,
                        'email_to' => [$this->db->table("sys_member")->getWhere(["member_id" => $arr_bonus_transfer["bonus_transfer_member_id"]])->getRow("member_email")],
                    ]);

                    $disbursement_uploaded_amount += $amount;
                    $disbursement_uploaded_count++;
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataArchive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil transfer bonus!';
            if ($success == 0) {
                $message = 'Gagal transfer bonus';
            }

            $disbursements_params = [
                'reference' => (string) $disbursement_id,
                'disbursements' => $arr_disbursements
            ];
            $disbursement = $this->payment_service->createDisbursementBatch($disbursements_params);

            $this->db->table("payment_disbursement")->where('disbursement_id', $disbursement_id)->update([
                'disbursement_total_uploaded_count' => $disbursement_uploaded_count,
                'disbursement_total_uploaded_amount' => $disbursement_uploaded_amount,
                'disbursement_external_id' => $disbursement['id'],
                'disbursement_created' => $disbursement['created'],
                'disbursement_status' => $disbursement['status'],
                'disbursement_request_json' => json_encode($disbursements_params),
            ]);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah disbursement", 1);
            }

            $this->db->transCommit();

            if (count($arr_success) > 0) {
                $client_name = COMPANY_NAME;
                $project_name = PROJECT_NAME;
                $client_wa_cs_number = WA_CS_NUMBER;
                $fmt = numfmt_create('id_ID', \NumberFormatter::CURRENCY);

                foreach ($arr_success as $key =>  $value) {
                    $member = $this->db->table("sys_member")->select("member_name, member_mobilephone")->getWhere(["member_id" => $value['member_id']])->getRow();
                    $sponsor =  numfmt_format_currency($fmt, $value['sponsor'], "IDR");
                    $gen_node =  numfmt_format_currency($fmt, $value['gen_node'], "IDR");
                    $power_leg =  numfmt_format_currency($fmt, $value['power_leg'], "IDR");
                    $matching_leg =  numfmt_format_currency($fmt, $value['matching_leg'], "IDR");
                    $cash_reward =  numfmt_format_currency($fmt, $value['cash_reward'], "IDR");
                    $adm_charge =  numfmt_format_currency($fmt, $value['adm_charge'], "IDR");
                    $tax =  numfmt_format_currency($fmt, $value['tax'], "IDR");
                    $total_komisi = numfmt_format_currency($fmt, $value['bonus_transfer_total'], "IDR");
                    if (WA_NOTIFICATION_IS_ACTIVE) {
                        $content = "*TRANSFER KOMISI BERHASIL*
Hai {$member->member_name}
Komisi anda berhasil ditransfer.

Detail komisi sebagai berikut:

Sponsor : {$sponsor}
Generasi : {$gen_node}
Power Leg : {$power_leg}
Matching Leg : {$matching_leg}
Cash Reward : {$cash_reward}

Potongan Admin : {$adm_charge}
Komisi Diterima : {$total_komisi}

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}

*-- {$project_name} --*";

                        // $this->notification_lib->send_waone($content, $member->member_mobilephone);
                        // print_r($content);
                        // die();
                    }
                }
            }

            $this->createRespon(200, $message, $dataArchive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), ['trace' => $th->getTrace()]);
        }
    }
}
