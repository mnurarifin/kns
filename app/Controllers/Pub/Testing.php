<?php
namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use Config\Services;

/* urutan cron:
 * - cron/serial/update_all_serial_expired (daily )
 * - cron/network/calculate_upgrade (daily)
 * - cron/netgrow/calculate_master (daily)
 * - cron/profitsharing/calculate_profitsharing (tanggal 1)
 * - cron/bonus/calculate_bonus (daily)
 * - cron/report/calculate_profit_loss (daily)
 * - cron/network/generate_network_code (daily)
 * - cron/serial/generate_all_serial (daily)
 * - cron/rank/calculate_rank_qualified (daily)
 * - cron/common/delete_member_otp_expired (daily)
*/

class Testing extends BaseController
{
    public function __construct()
    {
        // $this->datetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $this->mlm_service = service('Mlm');
        $old_serial_type_id = $this->mlm_service->get_serial_type_id_by_member_id(1);
        $obj_old_serial_type = $this->mlm_service->get_serial_type_by_serial_id($old_serial_type_id);
        if($obj_old_serial_type == FALSE) {
            echo 'false';
        }
    }

    public function generate_network_code()
    {
        //SWK1000001
        $this->network_service = service('Network');
        $this->network_service->generate_stock_network_code(NETWORK_CODE_STOCK_MIN); //params: jumlah
    }

    public function generate_serial()
    {
        $this->serial_service = service('Serial');

        //generate serial untuk masing-masing tipe
        $sql = "SELECT serial_type_id FROM sys_serial_type ORDER BY serial_type_id ASC";
        $query = $this->db->query($sql)->getResult();
        if(count($query) > 0) {
            foreach ($query as $row) {
                $this->serial_service->set_datetime($this->datetime);
                $this->serial_service->generate_serial($row->serial_type_id, SERIAL_STOCK_MIN); //params: tipe, jumlah
            }
        }
    }

    public function generate_serial_ro()
    {
        $this->serial_service = service('Serial');

        //generate serial ro untuk masing-masing tipe
        $sql = "SELECT serial_ro_type_id FROM sys_serial_ro_type ORDER BY serial_ro_type_id ASC";
        $query = $this->db->query($sql)->getResult();
        if(count($query) > 0) {
            foreach ($query as $row) {
                $this->serial_service->set_datetime($this->datetime);
                $this->serial_service->generate_serial_ro($row->serial_ro_type_id, SERIAL_RO_STOCK_MIN); //params: tipe, jumlah
            }
        }
    }

    public function registration()
    {
        /*
         * proses:
         * - insert sys_member, sys_member_account
         * - insert sys_member_plan_activity (membership)
         * - insert sys_bonus, sys_ewallet, sys_ewallet_withdrawal_limit
         * - insert sys_network, sys_network_sponsor, sys_network_upline
         * - update sys_network (sponsor & upline)
         * - insert sys_member_plan_activity (activation)
         * - update sys_serial (used)
         * - delete sys_serial_member_stock
         * - insert sys_netgrow_wait, sys_netgrow_sponsor, sys_netgrow_node, sys_netgrow_match
         * - insert sys_b_ro_personal, sys_b_netgrow_sponsor
         * - insert sys_b_reward_achievement, sys_b_reward_netgrow
         * - hitung reward qualified
        */
        $this->registration_service = service('Registration');

        $params = (object)[];
        $params->name = 'Esoftdream';
        $params->email = 'admin@esoftdream.net';
        $params->mobilephone = '081234567890';
        $params->gender = 'Laki-laki';
        $params->birth_place = 'Yogyakarta';
        $params->birth_date = '2022-01-01';
        $params->address = 'Jl. Garuda No. 21';
        $params->subdistrict_id = 347114;
        $params->city_id = 3471;
        $params->province_id = 34;
        $params->bank_id = 1;
        $params->bank_account_name = 'Esoftdream';
        $params->bank_account_no = '1234567890';
        $params->identity_type = 'KTP';
        $params->identity_no = '1234567890123456';
        $params->tax_no = '1234567890';

        $params->account_username = 'esoftdream2022';
        $params->account_password = '123456';

        $serial_type_id = 5;
        $params->serial_id = $this->common_model->getOne("sys_serial", "serial_id", ['serial_serial_type_id' => $serial_type_id, 'serial_is_used' => 0]);

        $params->sponsor_network_code = 'SWK1000004';
        $params->upline_network_code = 'SWK1000004';
        $params->position = 'R';

        $this->db->transBegin();
        try {
            $this->registration_service->set_datetime($this->datetime);
            $this->registration_service->set_date($this->date);
            $result = $this->registration_service->execute($params);
            echo '<pre>';
            print_r($result);
            echo '</pre>';

            $this->db->transCommit();
            $return = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            echo $e->getMessage();
        }
    }

    public function upgrade()
    {
        /*
         * proses:
         * - update sys_network (sponsor & upline)
         * - insert sys_member_plan_activity (activation)
         * - update sys_serial (used)
         * - delete sys_serial_member_stock
         * - insert sys_netgrow_wait, sys_netgrow_sponsor, sys_netgrow_node, sys_netgrow_match
         * - insert sys_b_ro_personal, sys_b_netgrow_sponsor
         * - insert sys_b_reward_achievement, sys_b_reward_netgrow
         * - hitung reward qualified
        */
        $this->upgrade_service = service('Upgrade');

        $params = (object)[];
        $params->member_id = 1;

        $serial_type_id = 5;
        $params->serial_id = $this->common_model->getOne("sys_serial_member_stock", "serial_member_stock_serial_id", ['serial_member_stock_member_id' => $params->member_id, 'serial_member_stock_serial_type_id' => $serial_type_id, 'serial_member_stock_is_used' => 0]);
        if($params->serial_id == '') {
            die('stok serial tidak ada');
        }

        $this->db->transBegin();
        try {
            $this->upgrade_service->set_datetime($this->datetime);
            $this->upgrade_service->set_date($this->date);
            $result = $this->upgrade_service->execute($params);
            echo '<pre>';
            print_r($result);
            echo '</pre>';

            $this->db->transCommit();
            $return = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            echo $e->getMessage();
        }
    }

    public function stockist_transaction()
    {
        /*
         * proses:
         * - insert inv_stockist_transaction, inv_stockist_transaction_detail
         * - update inv_stockist_product_stock
         * - insert inv_stockist_product_stock_log
         * - insert sys_serial_member_stock / sys_serial_ro_member_stock
         * - update sys_serial / sys_serial_ro
         * - insert sys_member_plan_activity (activation)
         * - insert log_serial_distribution / log_serial_ro_distribution
        */
        $this->transaction_service = service('Transaction');

        $params = (object)[];
        $params->stockist_member_id = 1;
        $params->buyer_member_id = 1;
        $params->total_price = 64800000;
        $params->extra_discount_type = NULL;
        $params->extra_discount_percent = 0;
        $params->extra_discount_value = 0;
        $params->total_nett_price = 64800000;
        $params->payment_cash = 64800000;
        $params->payment_ewallet = 0;
        $params->status = 'complete';
        $params->note = '';
        $params->arr_item = [
            1 => [
                'product_id' => 1,
                'unit_price' => 300000,
                'discount_type' => NULL,
                'discount_percent' => 0,
                'discount_value' => 0,
                'unit_nett_price' => 300000,
                'quantity' => 1,
            ],
            2 => [
                'product_id' => 5,
                'unit_price' => 19500000,
                'discount_type' => NULL,
                'discount_percent' => 0,
                'discount_value' => 0,
                'unit_nett_price' => 19500000,
                'quantity' => 3,
            ],
            3 => [
                'product_id' => 7,
                'unit_price' => 1500000,
                'discount_type' => NULL,
                'discount_percent' => 0,
                'discount_value' => 0,
                'unit_nett_price' => 1500000,
                'quantity' => 4,
            ],
        ];

        $this->db->transBegin();
        try {
            $this->transaction_service->set_datetime(date("Y-m-d H:i:s"));
            $result = $this->transaction_service->execute($params);
            echo '<pre>';
            print_r($result);
            echo '</pre>';

            $this->db->transCommit();
            $return = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            echo $e->getMessage();
        }
    }

    public function calculate_upgrade()
    {
        $this->cron_service = service('Cron');
        $this->ewallet_service = service('Ewallet');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if($this->cron_service->check_log('calculate_upgrade', $this->yesterday)) {
            echo "Error! Cron calculate_upgrade periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            //ambil data upgrade member hari kemarin
            $sql = "
                SELECT member_plan_activity_member_id AS member_id,
                member_plan_activity_serial_type_id AS serial_type_id
                FROM sys_member_plan_activity
                WHERE member_plan_activity_type = 'upgrade'
                AND DATE(member_plan_activity_datetime) = '{$this->yesterday}'
            ";
            $query = $this->db->query($sql)->getResult();
            if(count($query) > 0) {
                foreach ($query as $row) {
                    //upgrade ewallet limit
                    $this->ewallet_service->upgrade_ewallet_limit($row->member_id, $row->serial_type_id); //params: member_id, serial_type_id
                }
            }
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_upgrade', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));

        echo $result;
    }

    public function calculate_master()
    {
        $this->cron_service = service('Cron');
        $this->netgrow_service = service('Netgrow');

        if($this->cron_service->check_log('calculate_master', $this->date)) {
            echo "Error! Cron calculate_master periode " . convertDate($this->date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->netgrow_service->calculate_master($this->yesterday);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_master', json_encode($result), $this->date, $this->datetime, date("Y-m-d H:i:s"));

        echo $result;
    }

    public function calculate_profitsharing()
    {
        $this->profitsharing_service = service('Profitsharing');
        $this->cron_service = service('Cron');
        $this->qualified_date = date('Y-m-d', strtotime('first day of previous month', strtotime($this->datetime))); //yyyy-mm-01 bulan kemarin
        $this->qualified_year_month = date('Y-m', strtotime('-1 month')); //yyyy-mm bulan kemarin

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if($this->cron_service->check_log('calculate_profitsharing', $this->qualified_date)) {
            echo "Error! Cron calculate_profitsharing periode " . convertDate($this->qualified_date) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->profitsharing_service->calculate($this->qualified_year_month, $this->qualified_date);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_profitsharing', json_encode($result), $this->qualified_date, $this->datetime, date("Y-m-d H:i:s"));

        echo $result;
    }

    public function calculate_bonus()
    {
        /*
         * proses:
         * - insert sys_bonus_log
         * - update sys_bonus
         * - insert sys_ewallet_log
         * - update sys_ewallet
        */
        $this->cron_service = service('Cron');
        $this->bonus_service = service('Bonus');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if($this->cron_service->check_log('calculate_bonus', $this->yesterday)) {
            echo "Error! Cron calculate_bonus periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->bonus_service->calculate($this->yesterday, $this->datetime); //params: bonus_date, run_datetime
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_bonus', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));

        echo $result;
    }

    public function calculate_profit_loss()
    {
        /*
         * proses:
         * - ambil data income dari tabel income log
         * - ambil data payout dari tabel bonus log
         * - data payout reward ambil dari tabel reward qualified
         * - ambil data fee it dari tabel royalti fee log
         * - simpan ke tabel profit loss
        */
        $this->cron_service = service('Cron');
        $this->report_service = service('Report');

        //cek jika cron sudah dieksekusi pada data cron log, agar tidak dobel eksekusi
        if($this->cron_service->check_log('calculate_profit_loss', $this->yesterday)) {
            echo "Error! Cron calculate_profit_loss periode " . convertDate($this->yesterday) . " sudah dieksekusi.";
            die();
        }

        $this->db->transBegin();
        try {
            $this->report_service->calculate_profit_loss($this->yesterday);
            $this->db->transCommit();
            $result = "OK";
        } catch (\Exception $e) {
            $this->db->transRollback();
            $result = $e->getMessage() . " (Line: {$e->getLine()})";
        }

        //insert cron log
        $this->cron_service->insert_log('calculate_profit_loss', json_encode($result), $this->yesterday, $this->datetime, date("Y-m-d H:i:s"));

        echo $result;
    }

    public function update_tax_member()
    {
        $this->tax_service = service('Tax');

        $member_id = 1;
        $income = 1000000;
        $tax_year = '2022';
        $tax_date = '2022-03-01';
        $tax_no = '1234567890';
        $datetime = $this->datetime;
        $arr_income = [
            50000,
            100000,
            200000,
            50000,
            300000,
            300000,
            200000,
            0,
            1000000,
            1200000,
            1500000,
            2000000,
            3000000,
            0,
            500000,
            800000,
            100000,
            0,
            50000,
            1000000,
            3000000,
            1000000,
            1500000,
            2000000,
            3000000,
            2000000,
            2000000,
            500000,
            800000,
            100000,
            50000,
            1000000,
            2000000,
            3000000,
            2000000,
            2200000,
            2600000,
            2000000,
            1500000,
            100000,
            2600000,
            50000,
            1000000,
            2000000,
            15000000,
            1000000,
            500000,
            300000,
            100000,
            50000,
            0,
            300000,
            0,
            50000,
            400000,
            0,
            0,
            100000,
            500000,
            1000000
        ];
        // $this->tax_service->init_tax_member($member_id, $tax_year, $tax_no, $datetime);
        foreach($arr_income as $key => $value) {
            $tax_date = date("Y-m-d", strtotime($tax_date . "+1 days"));
            $this->tax_service->update_tax_member($member_id, $value, $tax_year, $tax_date, $tax_no, $datetime);
        }
    }

    public function calculate_tax_member()
    {
        $this->tax_service = service('Tax');

        $member_id = 1;
        $income = 50000;
        $tax_year = '2022';
        $tax_date = '2022-03-01';
        $tax_no = '1234567890';
        $datetime = $this->datetime;
        $arr_data = $this->tax_service->update_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime);
        echo '<pre>';
        print_r($arr_data);
        echo '<br />' . json_encode($arr_data['arr_tax_data']);
    }

    public function update_ewallet_limit()
    {
        $this->ewallet_service = service('Ewallet');

        $member_id = 1;
        $serial_ro_id = 'SWKRO4BG8N8N9525';
        $this->ewallet_service->set_datetime($this->datetime);
        $this->ewallet_service->update_ewallet_limit($member_id, $serial_ro_id);
    }

    public function insert_reward()
    {
        $member_id = 1;
        $point = 1; // poin plan b
        $datetime = $this->datetime;
        $class = $this->common_model->getCount('sys_b_ro_personal', 'ro_personal_member_id', ['ro_personal_member_id' => $member_id]);
        $this->reward_service->insert_reward($member_id, $point, $class, $datetime);
    }

    public function update_network_outer_node()
    {
        $this->arr_network = [];
        $this->arr_network['network_member_id'] = 5;

        //update titik downline terluar
        if(empty($this->arr_network_upline)) {
            $this->arr_network_upline = $this->common_model->getOne('sys_network_upline', 'network_upline_arr_data', ['network_upline_member_id' => $this->arr_network['network_member_id']]);
            $this->arr_network_upline = (array)json_decode($this->arr_network_upline);
        }

        $i = 1;
        $prev_position = '';
        foreach($this->arr_network_upline as $arr_upline_data) {
            echo '<br />sort: ' . $i . ' | ID: ' . $arr_upline_data->id . ' | pos: ' . $arr_upline_data->pos;
            $arr_upline_data = (object) $arr_upline_data;


            if($arr_upline_data->level == 1) {
                $prev_position = $arr_upline_data->pos;
                if($arr_upline_data->pos == 'L') {
                    $this->common_model->updateData('sys_network_outer_node', 'network_outer_node_member_id', $arr_upline_data->id, ['network_outer_node_left_member_id' => $this->arr_network['network_member_id']]);
                    echo '<br />level 1: L';
                } elseif($arr_upline_data->pos == 'R') {
                    $this->common_model->updateData('sys_network_outer_node', 'network_outer_node_member_id', $arr_upline_data->id, ['network_outer_node_right_member_id' => $this->arr_network['network_member_id']]);
                    echo '<br />level 1: R';
                }
                $prev_position = $arr_upline_data->pos;
            } else {
                if($arr_upline_data->pos == $prev_position) {
                    if($arr_upline_data->pos == 'L') {
                        $this->common_model->updateData('sys_network_outer_node', 'network_outer_node_member_id', $arr_upline_data->id, ['network_outer_node_left_member_id' => $this->arr_network['network_member_id']]);
                        echo '<br />level ' . $arr_upline_data->level . ': L';
                    } elseif($arr_upline_data->pos == 'R') {
                        $this->common_model->updateData('sys_network_outer_node', 'network_outer_node_member_id', $arr_upline_data->id, ['network_outer_node_right_member_id' => $this->arr_network['network_member_id']]);
                        echo '<br />level ' . $arr_upline_data->level . ': R';
                    }
                    $prev_position = $arr_upline_data->pos;
                } else {
                    break;
                }
            }
            $i++;
        }
    }

    public function calculate_rank_qualified() {
        $this->rank_service = service('Rank');
        $this->rank_service->calculate_rank_qualified($this->datetime);
    }
}
