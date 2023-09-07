<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;

class About extends BaseController
{
    public function index()
    {
        $data = [];

        $this->template->title("About");
        $this->template->breadcrumbs(["Home", "About"]);
        $this->template->content("Pub/About/aboutView", $data);
        $this->template->show("Template/Pub/main");
    }
}
