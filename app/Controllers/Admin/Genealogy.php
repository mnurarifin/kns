<?php

namespace App\Controllers\Admin;

class Genealogy extends BaseController
{
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Kemitraan' => 'member/show',
            'Genealogy' => 'genealogy/show'
        );

        $data['extraHeadContent'] = '<link rel="stylesheet" href="/app-assets/vendors/jorgchart/css/jorgchart.css"/>';
        $data['extraHeadContent'] .= '<link rel="stylesheet" href="/app-assets/vendors/jquery-qtip/css/jquery.qtip.min.css"/>';
        $data['extraFootContent'] = '<script type="text/javascript" src="/app-assets/vendors/jquery-migrate.min.js"></script>';
        $data['extraFootContent'] .= '<script type="text/javascript" src="/app-assets/vendors/jorgchart/js/jorgchart.js"></script>';
        $data['extraFootContent'] .= '<script type="text/javascript" src="/app-assets/vendors/jquery-qtip/js/jquery.qtip.min.js"></script>';

        $data['imagePath'] = getenv('UPLOAD_URL') . 'images/profile';

        $this->template->title('Genealogy');
        $this->template->content('Admin/genealogyView', $data);
        $this->template->show('Template/Admin/main');
    }
}
