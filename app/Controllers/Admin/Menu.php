<?php

namespace App\Controllers\Admin;

class Menu extends BaseController
{

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Menu Admin' => 'menu/show'
        );

        if($this->request->getGet('menuPar')){
            $data['arrBreadcrumbs'] = array_merge($data['arrBreadcrumbs'], ['Sub Menu' => '#']);
        }

        $this->template->title('Menu Admin');
        $this->template->content("Admin/menuAdminView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function public_menu()
    {
        $data['arrBreadcrumbs'] = array(
            'Pengelolaan Web' => '#',
            'Menu Publik' => 'cms/public-menu'
        );
        $this->template->title('Menu Publik');
        $this->template->content('Admin/menuPublicView', $data);
        $this->template->show('Template/Admin/main');
    }
}
