<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Reward extends BaseController
{

    //REWARD BINARY
    //=========================================================
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Reward' => 'reward/show',
            'Daftar Poin Reward' => 'reward/show'
        );

        $this->template->title('Daftar Poin Reward');
        $this->template->content("Admin/rewardCashView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function approval($type = 'sys')
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Reward' => 'reward/show',
            'Approval Poin Reward' => 'reward/approval'
        );

        $data['netType'] = $type;

        $this->template->title('Approval Poin Reward');
        $this->template->content("Admin/rewardApprovalView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function history($type = 'sys')
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Reward' => 'reward/show',
            'Riwayat Approval Poin Reward' => 'reward/history'
        );

        $data['netType'] = $type;

        $this->template->title('Riwayat Approval Poin Reward');
        $this->template->content("Admin/rewardHistoryView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function calon($type = 'sys')
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Reward' => 'reward/show',
            'Calon Penerima Reward' => 'reward/calon'
        );

        $data['netType'] = $type;

        $this->template->title('Calon Penerima Reward');
        $this->template->content("Admin/rewardCalonView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function excelApproval()
    {
        $tableName = 'sys_reward_qualified';

        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));

        $columns = json_decode($this->request->getPost('columns'));

        array_push($columns, 'reward_qualified_status');
        array_push($columns, 'reward_qualified_claim');

        $joinTable = '
        LEFT JOIN sys_member ON member_id = reward_qualified_member_id
        LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
        LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
        ';
        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'reward_qualified_datetime';

        $whereCondition = 'reward_qualified_status = "pending" && reward_qualified_claim != "unclaimed"';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            if (!empty($value['reward_qualified_status'])) {
                unset($results[$key]['reward_qualified_status']);
            }
            if (!empty($value['reward_qualified_claim'])) {
                unset($results[$key]['reward_qualified_claim']);
            }
        }

        $title = 'Data Approval Reward Cash';

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelApprovalTrip()
    {
        $tableName = 'sys_reward_trip_qualified';

        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));

        $columns = json_decode($this->request->getPost('columns'));

        array_push($columns, 'reward_qualified_status');
        array_push($columns, 'reward_qualified_claim');

        $joinTable = '
        LEFT JOIN sys_member ON member_id = reward_qualified_member_id
        LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
        LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
        ';
        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'reward_qualified_datetime';

        $whereCondition = 'reward_qualified_status = "pending" && reward_qualified_claim != "unclaimed"';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            if (!empty($value['reward_qualified_status'])) {
                unset($results[$key]['reward_qualified_status']);
            }
            if (!empty($value['reward_qualified_claim'])) {
                unset($results[$key]['reward_qualified_claim']);
            }
        }

        $title = 'Data Approval Reward Trip';

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelHistory()
    {
        $tableName = 'sys_reward_qualified';

        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));

        $columns = json_decode($this->request->getPost('columns'));

        array_push($columns, 'reward_qualified_status');
        array_push($columns, 'reward_qualified_claim');

        $joinTable = '
        LEFT JOIN sys_member ON member_id = reward_qualified_member_id
        LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
        LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
        ';
        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'reward_qualified_datetime';

        $whereCondition = 'reward_qualified_status != "pending"';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            unset($results[$key]['reward_qualified_claim']);
            $results[$key]['reward_qualified_status'] = $results[$key]['reward_qualified_status'] == 'approved' ? 'Diterima' : 'Ditolak';
        }

        $title = 'Daftar Riwayat Approval Reward Cash';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelCalonReward()
    {
        $tableName = 'sys_reward_point';

        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));

        $columns = json_decode($this->request->getPost('columns'));

        $joinTable = '
        LEFT JOIN sys_member ON member_id = reward_point_member_id
        LEFT JOIN sys_network ON network_member_id = reward_point_member_id 
        LEFT JOIN sys_reward_qualified ON reward_qualified_reward_title = reward_point_member_id        
        ';
        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'member_name';

        $whereCondition = 'reward_point_acc >= 54'; // add WHERE condition here

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        $title = 'Daftar Calon Penerima Reward';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelHistoryTrip()
    {
        $tableName = 'sys_reward_trip_qualified';

        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));

        $columns = json_decode($this->request->getPost('columns'));

        array_push($columns, 'reward_qualified_status');
        array_push($columns, 'reward_qualified_claim');

        $joinTable = '
        LEFT JOIN sys_member ON member_id = reward_qualified_member_id
        LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
        LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
        ';
        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'reward_qualified_datetime';

        $whereCondition = 'reward_qualified_status != "pending"';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            unset($results[$key]['reward_qualified_claim']);
            $results[$key]['reward_qualified_status'] = $results[$key]['reward_qualified_status'] == 'approved' ? 'Diterima' : 'Ditolak';
        }

        $title = 'Daftar Riwayat Approval Reward Trip';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    //REWARD RO
    //=========================================================
    public function ro_show($type = 'bin')
    {
        $data['arrBreadcrumbs'] = array(
            'Reward' => 'reward/show',
            'Reward RO' => 'reward/show_ro'
        );

        $data['netType'] = $type;

        $data['imagePath'] = UPLOAD_URL . URL_IMG_REWARD;

        $this->template->title('Reward RO');
        $this->template->content("rewardRoView", $data);
        $this->template->show('Template/Admin/main');
    }


    public function ro_approval($type = 'bin')
    {
        $data['arrBreadcrumbs'] = array(
            'Reward' => 'reward/show',
            'Approval Reward RO' => 'reward/approval_ro'
        );

        $data['netType'] = $type;

        $this->template->title('Approval Reward RO');
        $this->template->content("rewardRoApprovalView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function ro_history($type = 'bin')
    {
        $data['arrBreadcrumbs'] = array(
            'Reward' => 'reward/show',
            'Riwayat Approval Reward RO' => 'reward/history_ro',
        );

        $data['netType'] = $type;

        $this->template->title('Riwayat Approval Reward RO');
        $this->template->content("rewardRoHistoryView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function ro_calon($type = 'bin')
    {
        $data['arrBreadcrumbs'] = array(
            'Reward' => 'reward/show',
            'Calon Penerima Reward RO' => 'reward/calon_ro',
        );

        $data['netType'] = $type;

        $this->template->title('Calon Penerima Reward RO');
        $this->template->content("rewardCalonRoView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function excelApprovalRo($type = 'bin')
    {
        $tableName = $type . '_reward_ro_qualified';

        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));

        $columns = json_decode($this->request->getPost('columns'));

        array_push($columns, 'reward_qualified_status');
        array_push($columns, 'reward_qualified_claim');

        $joinTable = '
        LEFT JOIN ' . $type . '_network ON network_id = reward_qualified_network_id
        LEFT JOIN sys_member ON member_id = network_member_id
        LEFT JOIN site_administrator ON administrator_id = reward_qualified_administrator_id
        ';
        $request = array();
        $request['columns'] = $columns;
        $request['filter'] = '';
        $request['dir'] = 'DESC';
        $request['sort'] = 'reward_qualified_date';

        $whereCondition = 'reward_qualified_status = "pending" && reward_qualified_claim != "unclaimed"';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $value) {
            if (!empty($value['reward_qualified_status'])) {
                unset($results[$key]['reward_qualified_status']);
            }
            if (!empty($value['reward_qualified_claim'])) {
                unset($results[$key]['reward_qualified_claim']);
            }
        }

        $title = 'Data Approval Reward RO';

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function excelHistoryRo($type = 'bin')
    {
        $tableName = $type . '_reward_ro_qualified';
        $joinTable = '
                LEFT JOIN ' . $type . '_network ON network_id = reward_qualified_network_id
                LEFT JOIN sys_member ON member_id = network_member_id
                LEFT JOIN site_administrator ON administrator_id = reward_qualified_administrator_id
            ';
        $whereCondition = 'reward_qualified_status != "pending"';
        $tableHead = json_decode($this->request->getPost('display'));
        $tableAlign = json_decode($this->request->getPost('align'));
        $results = Services::DataTableLib()->getListDataExcel($this->request, $tableName, $joinTable, $whereCondition);
        $fieldToReplace = 'reward_qualified_status';
        $valueToReplace = array('approved' => 'Terima', 'rejected' => 'Tolak');
        $results = Services::DataTableLib()->replaceArrayValue($results, $fieldToReplace, $valueToReplace);
        $title = 'Daftar Riwayat Approval Reward';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function trip_list()
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Trip' => 'reward/show',
            'Daftar Poin Trip' => 'reward/trip_list'
        );

        $this->template->title('Daftar Poin Trip');
        $this->template->content("Admin/rewardTripView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function trip_approval($type = 'sys')
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Trip' => 'reward/show',
            'Approval Poin Trip' => 'reward/approval'
        );

        $data['netType'] = $type;

        $this->template->title('Approval Poin Trip');
        $this->template->content("Admin/rewardApprovalTripView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function trip_history($type = 'sys')
    {
        $data['arrBreadcrumbs'] = array(
            'Poin Trip' => 'reward/show',
            'Riwayat Approval Poin Trip' => 'reward/history'
        );

        $data['netType'] = $type;

        $this->template->title('Riwayat Approval Poin Trip');
        $this->template->content("Admin/rewardHistoryTripView", $data);
        $this->template->show('Template/Admin/main');
    }
}
