<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Saldo extends BaseController
{
    public function index()
    {
        $this->data();
    }

    public function data()
    {
        $data['arrBreadcrumbs'] = array(
            'Saldo' => '#',
            'Saldo Stokis' => 'saldo/data',
        );

        $this->template->title('Saldo Stokis');
        $this->template->content('Admin/saldoStockistView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function history()
    {
        $data['arrBreadcrumbs'] = array(
            'Saldo' => '#',
            'Riwayat Transfer' => 'saldo/history',
        );

        $this->template->title('Riwayat Transfer');
        $this->template->content('Admin/saldoStockistTransferHistoryView', $data);
        $this->template->show('Template/Admin/main');
    }
}
