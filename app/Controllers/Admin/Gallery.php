<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Gallery extends BaseController
{
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Pengelolaan Web' => '#',
            'Galeri' => 'gallery/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_GALLERY;

        $this->template->title('Galeri');
        $this->template->content('Admin/galleryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function category()
    {
        $data['arrBreadcrumbs'] = array(
            'Pengelolaan Web' => '#',
            'Kategori Galeri' => 'gallery/category'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_GALLERY_CATEGORY;

        $this->template->title('Kategori Galeri');
        $this->template->content('Admin/galleryCategoryView', $data);
        $this->template->show('Template/Admin/main');
    }
}
