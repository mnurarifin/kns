<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Contact_model;

class Contact extends BaseController
{
    public function __construct()
    {
        $this->bonus_model = new Contact_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Contact");
        $this->template->breadcrumbs(["Kontak Kami", "Kontak Kami"]);
        $this->template->content("Pub/Contact/contactView", $data);
        $this->template->show("Template/Pub/main");
    }
}
