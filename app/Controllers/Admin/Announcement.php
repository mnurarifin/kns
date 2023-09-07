<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Announcement extends BaseController
{
    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Pengelolaan Web' => '#',
            'Pengumuman' => 'banner/show'
        );

        $this->template->title('Pengumuman');
        $this->template->content('Admin/announcementView', $data);
        $this->template->show('Template/Admin/main');
    }
}
