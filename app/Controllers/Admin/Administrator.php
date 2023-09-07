<?php

namespace App\Controllers\Admin;

class Administrator extends BaseController
{

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Administrator' => 'administrator/show'
        );

        $this->template->title('Administrator');
        $this->template->content("Admin/administratorView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function group()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Grup Administrator' => 'administrator/show',
        );

        $data['administrator_group_type'] = $this->session->administrator_group_type;

        $this->template->title('Grup Administrator');
        $this->template->content("Admin/administratorGroupView", $data);
        $this->template->show('Template/Admin/main');
    }
}
