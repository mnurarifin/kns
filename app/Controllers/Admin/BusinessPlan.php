<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BusinessPlan extends BaseController
{
    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Pengelolaan Web' => '#',
            'Bisnis Plan' => 'banner/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_BANNER;

        $this->template->title('Bisnis Plan');
        $this->template->content('Admin/businessPlanView', $data);
        $this->template->show('Template/Admin/main');
    }
}
