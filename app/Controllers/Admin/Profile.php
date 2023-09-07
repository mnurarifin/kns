<?php

namespace App\Controllers\Admin;


class Profile extends BaseController
{

    public function edit()
    {
        $data['arrBreadcrumbs'] = array(
            'Administrator' => 'administrator/show',
            'Ubah Profil' => 'profile/edit'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_ADMIN;

        $this->template->title('Profil Administrator');
        $this->template->content('Admin/profileEditView',$data);
        $this->template->show('Template/Admin/main');
    }

    public function password()
    {
        $data['arrBreadcrumbs'] = array(
            'Administrator' => 'administrator/show',
            'Ubah Password' => 'profile/password'
        );

        $this->template->title('Ubah Password');
        $this->template->content('Admin/profilePasswordView',$data);
        $this->template->show('Template/Admin/main');
    }



}
