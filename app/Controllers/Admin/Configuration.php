<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Configuration extends BaseController
{
    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Konfigurasi' => '#',
        );

        $this->template->title('Konfigurasi');
        $this->template->content('Admin/configurationView', $data);
        $this->template->show('Template/Admin/main');
    }
}
