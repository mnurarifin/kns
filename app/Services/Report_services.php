<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Report_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function insert_income_log($type, $value, $member_id, $datetime, $serial_type_id = null)
    {
        //simpan ke tabel income log
        $arr_data = [];
        $arr_data['income_log_type'] = $type;
        $arr_data['income_log_value'] = $value;
        $arr_data['income_log_member_id'] = $member_id;
        $arr_data['income_log_datetime'] = $datetime;
        if ($this->common_model->insertData('report_income_log', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data income log.", 1);
        }

        //simpan ke tabel royalti fee log
        // $network_code = $this->common_model->getOne('sys_network', 'network_code', ['network_member_id' => $member_id]);
        // if($type == 'repeatorder') {
        //     $royalty_fee = ARR_CONFIG_FEE_IT_PERCENTAGE['repeatorder'] * $value;
        //     $note = "Repeat Order {$network_code} senilai Rp " . setNumberFormat($value);
        // } elseif($type == 'activation') {
        //     $royalty_fee = ARR_CONFIG_FEE_IT_PERCENTAGE[$serial_type_id] * $value;
        //     $note = "Aktivasi {$network_code} senilai Rp " . setNumberFormat($value);
        // } elseif($type == 'upgrade') {
        //     $royalty_fee = ARR_CONFIG_FEE_IT_PERCENTAGE[$serial_type_id] * $value;
        //     $note = "Upgrade {$network_code} senilai Rp " . setNumberFormat($value);
        // }

        // $arr_data = [];
        // $arr_data['royalty_fee_log_value'] = $royalty_fee;
        // $arr_data['royalty_fee_log_type'] = 'out';
        // $arr_data['royalty_fee_log_note'] = $note;
        // $arr_data['royalty_fee_log_input_datetime'] = $datetime;
        // if($this->common_model->insertData('report_royalty_fee_log', $arr_data) == FALSE) {
        //     throw new \Exception("Gagal menambah data fee royalti log.", 1);
        // }

        // //update ke tabel royalti fee
        // $sql = "
        //     UPDATE report_royalty_fee
        //     SET royalty_fee_paid = royalty_fee_paid + {$royalty_fee},
        //     royalty_fee_last_updated_datetime = '{$datetime}'
        //     WHERE royalty_fee_id = 1
        // ";
        // $this->db->query($sql);
        // if ($this->db->affectedRows() <= 0) {
        //     throw new \Exception("Gagal mengubah data fee royalti log.", 1);
        // }
    }

    public function calculate_profit_loss($date)
    {
        //ambil data income dari tabel income log
        $arr_income = [
            'activation' => 0,
            'upgrade' => 0,
            'repeatorder' => 0
        ];
        $sql_income = "
            SELECT income_log_type,
            SUM(income_log_value) AS income_log_value
            FROM report_income_log
            WHERE DATE(income_log_datetime) = '{$date}'
            AND income_log_type IN ('activation','upgrade','repeatorder')
            GROUP BY income_log_type
        ";
        $query_income = $this->db->query($sql_income)->getResult();
        if (count($query_income) > 0) {
            foreach ($query_income as $row_income) {
                $arr_income[$row_income->income_log_type] = $row_income->income_log_value;
            }
        }
        $arr_income['total'] = array_sum($arr_income);

        //ambil data payout dari tabel bonus log
        //data payout reward ambil dari tabel reward qualified
        $arr_payout = [
            'sponsor' => 0,
            'match' => 0,
            'gen_match' => 0,
            'b_sponsor' => 0,
            'b_reward' => 0,
            'profit_sharing' => 0
        ];
        $sql_payout = "
            SELECT SUM(bonus_log_sponsor) AS sponsor,
            SUM(bonus_log_match) AS `match`,
            SUM(bonus_log_gen_match) AS gen_match,
            SUM(bonus_log_b_sponsor) AS b_sponsor,
            SUM(bonus_log_profit_sharing) AS profit_sharing
            FROM sys_bonus_log
            WHERE bonus_log_date = '{$date}'
        ";
        $row_payout = $this->db->query($sql_payout)->getRow();
        if (!is_null($row_payout)) {
            $arr_payout['sponsor'] = $row_payout->sponsor;
            $arr_payout['match'] = $row_payout->match;
            $arr_payout['gen_match'] = $row_payout->gen_match;
            $arr_payout['b_sponsor'] = $row_payout->b_sponsor;
            $arr_payout['profit_sharing'] = $row_payout->profit_sharing;
        }
        $arr_payout['b_reward'] = $this->common_model->getSum("sys_b_reward_qualified", "reward_qualified_reward_value", ['DATE(reward_qualified_datetime) = ' => $date]);
        $arr_payout['total'] = array_sum($arr_payout);

        //ambil data fee it dari tabel royalti fee log
        $arr_cost = [
            'cogs' => 0,
            'shipping' => 0,
            'fee_it' => 0,
        ];
        $arr_cost['fee_it'] = $this->common_model->getSum("report_royalty_fee_log", "royalty_fee_log_value", ['royalty_fee_log_type' => 'out', 'DATE(royalty_fee_log_input_datetime) = ' => $date]);
        $arr_cost['total'] = array_sum($arr_cost);

        $payout_cost_total = $arr_payout['total'] + $arr_cost['total'];
        $profit_loss = $arr_income['total'] - $payout_cost_total;

        $arr_data = [];
        $arr_data['profit_loss_date'] = $date;
        $arr_data['profit_loss_income_activation_value'] = $arr_income['activation'];
        $arr_data['profit_loss_income_upgrade_value'] = $arr_income['upgrade'];
        $arr_data['profit_loss_income_repeat_order_value'] = $arr_income['repeatorder'];
        $arr_data['profit_loss_income_total_value'] = $arr_income['total'];
        $arr_data['profit_loss_payout_sponsor_value'] = $arr_payout['sponsor'] ?: 0;
        $arr_data['profit_loss_payout_match_value'] = $arr_payout['match'] ?: 0;
        $arr_data['profit_loss_payout_gen_match_value'] = $arr_payout['gen_match'] ?: 0;
        $arr_data['profit_loss_payout_b_sponsor_value'] = $arr_payout['b_sponsor'] ?: 0;
        $arr_data['profit_loss_payout_b_reward_value'] = $arr_payout['b_reward'] ?: 0;
        $arr_data['profit_loss_payout_profit_sharing_value'] = $arr_payout['profit_sharing'] ?: 0;
        $arr_data['profit_loss_payout_total_value'] = $arr_payout['total'];
        $arr_data['profit_loss_cost_cogs'] = $arr_cost['cogs'];
        $arr_data['profit_loss_cost_shipping'] = $arr_cost['shipping'];
        $arr_data['profit_loss_cost_fee_it_value'] = $arr_cost['fee_it'];
        $arr_data['profit_loss_cost_total_value'] = $arr_cost['total'];
        $arr_data['profit_loss_payout_cost_total_value'] = $payout_cost_total;
        $arr_data['profit_loss_value'] = $profit_loss;

        if ($this->common_model->insertData('report_profit_loss', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data laba rugi.", 1);
        }
    }
}
