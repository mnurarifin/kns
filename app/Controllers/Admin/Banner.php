<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Banner extends BaseController
{
    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Pengelolaan Web' => '#',
            'Banner' => 'banner/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_BANNER;

        $this->template->title('Banner');
        $this->template->content('Admin/bannerView', $data);
        $this->template->show('Template/Admin/main');
    }
}
