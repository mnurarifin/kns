<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Member extends BaseController
{
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => '#',
            'Data Mitra' => 'member/show'
        );

        $this->template->title('Data Mitra');
        $this->template->content('Admin/memberListView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function pramitra()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => '#',
            'Data Pramitra' => 'member/pramitra'
        );

        $this->template->title('Data Pramitra');
        $this->template->content('Admin/membershipListView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function top_bonus()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => '#',
            'Top Komisi Mitra' => 'member/top_bonus'
        );

        $this->template->title('Top Komisi Mitra');
        $this->template->content('Admin/membershipTopBonus', $data);
        $this->template->show('Template/Admin/main');
    }

    public function top_sponsor()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => '#',
            'Top Sponsor Mitra' => 'member/top_sponsor'
        );

        $this->template->title('Top Sponsor Mitra');
        $this->template->content('Admin/membershipTopSponsor', $data);
        $this->template->show('Template/Admin/main');
    }

    public function stock()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => '#',
            'Data Mitra' => 'member/stok'
        );

        $this->template->title('Data Stok Mitra');
        $this->template->content('Admin/memberStockView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function stock_log()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => '#',
            'Data Mitra' => 'member/stock-log'
        );

        $this->template->title('Data Riwayat Mitra');
        $this->template->content('Admin/memberStockLogView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function pertumbuhan_jaringan()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => 'member/show',
            'Pertumbuhan Jaringan' => 'member/pertumbuhan-jaringan'
        );

        $this->template->title('Daftar Pertumbuhan Jaringan');
        $this->template->content('Admin/pertumbuhanJaringanView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function member_audit()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => 'member/show',
            'Data Member Auditrail' => 'member/member_audit'
        );

        $this->template->title('Data Member Auditrail');
        $this->template->content('Admin/memberAuditListView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function excel()
    {
        $this->functionLib = Services::FunctionLib();

        $tableName = 'sys_member';
        $joinTable = '
        JOIN sys_network ON network_member_id = member_id
        LEFT JOIN sys_member_account ON member_account_member_id = member_id
        ';
        $whereCondition = '';
        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));
        $columns = json_decode($this->request->getPost('columns'));

        $request = array();
        $request['columns'] = [
            'member_account_username',
            'member_name',
            'network_activation_datetime',
            'network_upline_member_id',
            'network_sponsor_member_id',
            'network_point',
            'network_is_active'
        ];
        $request['filter'] =   (array) $this->request->getGet('filter');
        $request['dir'] = json_decode(strtoupper($this->request->getPost('dir')));
        $request['sort'] = json_decode($this->request->getPost('sort'));

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        if (count($results) > 0) {
            foreach ($results as $key => $row) {
                if (array_key_exists('network_activation_datetime', $row) && $row['network_activation_datetime']) {
                    $results[$key]['network_activation_datetime'] = $this->functionLib->convertDatetime($row['network_activation_datetime'], 'id');
                }

                $results[$key]['network_upline_member_id'] = $this->db->table('sys_member_account')->select('member_account_username')->getWhere(['member_account_member_id' => $row['network_upline_member_id']])->getRow('member_account_username');
                $results[$key]['network_sponsor_member_id'] = $this->db->table('sys_member_account')->select('member_account_username')->getWhere(['member_account_member_id' => $row['network_sponsor_member_id']])->getRow('member_account_username');

                $member_id = $this->db->table('sys_member_account')->select('member_account_member_id')->getWhere(['member_account_username' => $row['member_account_username']])->getRow('member_account_member_id');
                // $results[$key]['network_total_point_left_b'] = $this->db->table('sys_b_reward_achievement')->selectSum('reward_achievement_point_left')->getWhere(['reward_achievement_member_id' => $member_id])->getRow('reward_achievement_point_left');
                // $results[$key]['network_total_point_right_b'] = $this->db->table('sys_b_reward_achievement')->selectSum('reward_achievement_point_right')->getWhere(['reward_achievement_member_id' => $member_id])->getRow('reward_achievement_point_right');

                if (array_key_exists('network_is_active', $row) && $row['network_is_active']) {
                    if ($row['network_is_active'] == 0) {
                        $results[$key]['network_is_active'] = "Tidak Aktif";
                    } else if ($row['network_is_active'] == 1) {
                        $results[$key]['network_is_active'] = "Aktif";
                    } else if ($row['network_is_active'] == 2) {
                        $results[$key]['network_is_active'] = "Diblokir";
                    }
                }

                $this->moveElement($results[$key], 11, 9);
                $this->moveElement($results[$key], 12, 10);
            }
        }

        $title = 'Data Mitra';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelMembership()
    {
        $this->functionLib = Services::FunctionLib();

        $tableName = 'sys_member_registration';
        $joinTable = '';
        $whereCondition = '';
        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));
        $columns = json_decode($this->request->getPost('columns'));

        $request = array();
        $request['columns'] = $columns;
        $request['filter'] =   (array) $this->request->getGet('filter');
        $request['dir'] = json_decode(strtoupper($this->request->getPost('dir')));
        $request['sort'] = json_decode($this->request->getPost('sort'));

        array_push($request['columns'], 'member_registration_transaction_id');
        array_push($request['columns'], 'member_registration_transaction_type');


        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            $results[$key]['member_mobilephone'] = ' ' . $value['member_mobilephone'];
            $results[$key]['member_registration_datetime'] = $this->functionLib->convertDatetime($value['member_registration_datetime'], 'id');

            if ($value["member_registration_transaction_type"] == "warehouse") {
                $results[$key]['invoice_url']  = $this->db
                    ->table("inv_warehouse_transaction")
                    ->join("inv_warehouse_transaction_payment", "warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id")
                    ->getWhere(["warehouse_transaction_id" => $value["member_registration_transaction_id"]])
                    ->getRow("warehouse_transaction_payment_invoice_url");
            } else if ($value["member_registration_transaction_type"] == "stockist") {
                $results[$key]['invoice_url']  = $this->db
                    ->table("inv_stockist_transaction")->join("inv_stockist_transaction_payment", "stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id")
                    ->getWhere(["stockist_transaction_id" => $value["member_registration_transaction_id"]])
                    ->getRow("stockist_transaction_payment_invoice_url");
            }

            // unset member_registration_transaction_id
            unset($results[$key]['member_registration_transaction_id']);
            unset($results[$key]['member_registration_transaction_type']);
        }

        array_push($tableHead, 'Invoice URL');

        $title = 'Data Pra-Mitra';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    function moveElement(&$array, $a, $b)
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }
}
