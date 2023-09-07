<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;

class Error_404 extends BaseController
{
    public function index()
    {
        $this->template->title("404");
        $this->template->breadcrumbs(["404", "404"]);
        $this->template->content("404", []);
        $this->template->show("Template/Pub/main");
    }
}
