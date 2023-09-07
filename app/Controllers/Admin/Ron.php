<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Ron extends BaseController
{

    public function show($type = 'bin')
    {
        $data['arrBreadcrumbs'] = array(
            'Daftar RON' => 'RON/show'
        );

        $data['netType'] = $type;
        $data['imagePath'] = UPLOAD_URL . URL_IMG_RON;

        $this->template->title('Daftar RON');
        $this->template->content("ronView", $data);
        $this->template->show('Template/Admin/main');
    }
}
