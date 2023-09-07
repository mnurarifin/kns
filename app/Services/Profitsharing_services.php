<?php
namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Profitsharing_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function calculate($qualified_year_month, $qualified_date)
    {
        $omzet_value = $allocation = $bonus_value = 0;
        $sql_omzet = "
            SELECT SUM(income_log_value) AS omzet_value
            FROM report_income_log
            WHERE income_log_type IN ('activation', 'upgrade')
            AND LEFT(income_log_datetime, 7) = '{$qualified_year_month}'
        ";
        $row_omzet = $this->db->query($sql_omzet)->getRow();
        if(!is_null($row_omzet)) {
            if (!is_null($row_omzet->omzet_value)) {
                $omzet_value = $row_omzet->omzet_value;
            }

            $allocation = $omzet_value * CONFIG_PROFITSHARING_PERCENTAGE;

            $qualified_serial_type_id = 5; //Paket Executive Business
            $sql_qualified_member = "
                SELECT member_plan_activity_member_id AS member_id
                FROM sys_member_plan_activity
                INNER JOIN sys_network ON network_member_id = member_plan_activity_member_id
                WHERE member_plan_activity_serial_type_id = {$qualified_serial_type_id}
                AND network_is_free = 0
                AND member_plan_activity_type IN ('activation', 'upgrade')
                AND DATE(member_plan_activity_datetime) <= '{$qualified_date}'
            ";
            $query_qualified_member = $this->db->query($sql_qualified_member)->getResult();
            $qualified_member_count = count($query_qualified_member);
            if($qualified_member_count > 0) {
                $bonus_value = $allocation / $qualified_member_count;

                foreach ($query_qualified_member as $row_qualified_member) {
                    $arr_data = [];
                    $arr_data['profit_sharing_qualified_member_id'] = $row_qualified_member->member_id;
                    $arr_data['profit_sharing_qualified_bonus'] = $bonus_value;
                    $arr_data['profit_sharing_qualified_date'] = $qualified_date;

                    if($this->common_model->insertData('sys_profit_sharing_qualified', $arr_data) == FALSE) {
                        throw new \Exception("Gagal menambah data kualifikasi profit sharing.", 1);
                    }
                }
            }
        }

        $arr_data = [];
        $arr_data['netgrow_profit_sharing_omzet'] = $omzet_value;
        $arr_data['netgrow_profit_sharing_allocation'] = $allocation;
        $arr_data['netgrow_profit_sharing_qualified_member_count'] = $qualified_member_count;
        $arr_data['netgrow_profit_sharing_bonus'] = $bonus_value;
        $arr_data['netgrow_profit_sharing_date'] = $qualified_date;

        if($this->common_model->insertData('sys_profit_sharing', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data profit sharing.", 1);
        }
    }

}
