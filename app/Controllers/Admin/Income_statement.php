<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
// use App\Models\Admin\IncomeStatementModel;
use Config\Services;

/**
* 
*/
class Income_statement extends BaseController
{

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Laporan' => '#',
            'Laporan Laba/Rugi' => 'income_statement/show'
        );
        $data['month'] = date('m');
        $data['year'] = date('Y');
        
        $this->template->title('Laporan Laba/Rugi');
        $this->template->content("incomeStatementView", $data);
        $this->template->show('Template/Admin/main');
    }
}