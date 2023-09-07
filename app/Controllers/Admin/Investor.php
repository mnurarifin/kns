<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Investor extends BaseController
{
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Investor' => '#',
            'Data Investor' => 'investor/investor',
        );

        $this->template->title('Data Investor');
        $this->template->content('Admin/investorView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function log()
    {
        $data['arrBreadcrumbs'] = array(
            'Investor' => '#',
            'Data Riwayat Investor' => 'investor/investor-log',
        );

        $this->template->title('Data Riwayat Investor');
        $this->template->content('Admin/investorLogView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function report()
    {
        $data['arrBreadcrumbs'] = array(
            'Investor' => '#',
            'Laporan Investor' => 'investor/investor-report',
        );

        $admin_id = session("admin")["admin_id"];

        $administrator_group_id = $this->db->table('site_administrator')->getWhere([
            'administrator_id' => $admin_id,
        ])->getRow('administrator_administrator_group_id');


        $findInvestor = $this->db->table('report_investor')->getWhere([
            'investor_administrator_group_id' => $administrator_group_id,
        ])->getRow('investor_id');

        $data['investor_id'] = $findInvestor;

        $this->template->title('Laporan Investor');
        $this->template->content('Admin/investorReportView', $data);
        $this->template->show('Template/Admin/main');
    }


    public function withdraw()
    {
        $data['arrBreadcrumbs'] = array(
            'Investor' => '#',
            'Withdrawal' => 'investor/withdrawal',
        );

        $this->template->title('Withdrawal Investor');
        $this->template->content('Admin/investorWithdrawalView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function approval()
    {
        $data['arrBreadcrumbs'] = array(
            'Investor' => '#',
            'Withdrawal Approval' => 'investor/withdrawal-approval',
        );

        $this->template->title('Withdrawal Approval');
        $this->template->content('Admin/investorWithdrawalViewApproval', $data);
        $this->template->show('Template/Admin/main');
    }
}
