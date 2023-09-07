<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Bank_company extends BaseController
{
    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Bank Company' => 'Bank_company/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_BANK;

        $this->template->title('Bank Company');
        $this->template->content('Admin/bankCompanyView', $data);
        $this->template->show('Template/Admin/main');
    }
}
