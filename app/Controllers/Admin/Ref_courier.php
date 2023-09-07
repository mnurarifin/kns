<?php

namespace App\Controllers\Admin;

class Ref_courier extends BaseController
{
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Kurir' => 'Ref_courier/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_COURIER;

        $this->template->title('Kurir');
        $this->template->content('Admin/refCourierView', $data);
        $this->template->show('Template/Admin/main');
    }
}
