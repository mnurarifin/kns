<?php

namespace App\Controllers\Admin;

class Testimony extends BaseController
{

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => 'member/show',
            'Testimonial' => 'testimony/show',
        );

        $this->template->title('Daftar testimonial');
        $this->template->content('Admin/testimonyView', $data);
        $this->template->show('Template/Admin/main');
    }
}
