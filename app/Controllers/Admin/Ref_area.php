<?php

namespace App\Controllers\Admin;

class Ref_area extends BaseController
{

    public function country()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Negara' => 'ref_area/country'
        );

        $this->template->title('Daftar Negara');
        $this->template->content('Admin/refAreaCountryView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function province()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Provinsi' => 'ref_area/province'
        );

        $this->template->title('Provinsi');
        $this->template->content('Admin/refAreaProvinceView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function city()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Kota/Kabupaten' => 'ref_area/city'
        );

        $this->template->title('Kota/Kabupaten');
        $this->template->content('Admin/refAreaCityView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function village()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Desa' => 'ref_area/village'
        );

        $this->template->title('Desa');
        $this->template->content("Admin/refAreaVillageView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function subdistrict()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Kecamatan' => 'ref_area/subdistrict',
        );

        $this->template->title('Kecamatan');
        $this->template->content("Admin/refAreaSubdistricView", $data);
        $this->template->show('Template/Admin/main');
    }
}
