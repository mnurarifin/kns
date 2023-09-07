<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;

class Business extends BaseController
{
    public function show()
    {
        $data = [];

        $this->template->title("Bisnis Plan");
        $this->template->breadcrumbs(["Home", "Bisnis Plan"]);
        $this->template->content("Pub/Business/businessView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function ethic_code()
    {
        $data = [];

        $this->template->title("Kode Etik");
        $this->template->breadcrumbs(["Home", "Kode Etik"]);
        $this->template->content("Pub/Business/ethicView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function terms()
    {
        $data = [];

        $this->template->title("Syarat & Ketentuan");
        $this->template->breadcrumbs(["Home", "Syarat & Ketentuan"]);
        $this->template->content("Pub/Business/termsView", $data);
        $this->template->show("Template/Pub/main");
    }
}
