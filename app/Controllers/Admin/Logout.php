<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        $this->session->remove('admin');
        if($this->session->get('admin')) {
            $this->session->destroy();
        }
        return redirect()->to('/login-admin');
    }
}
