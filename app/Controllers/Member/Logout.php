<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        $this->session->remove('member');
        if($this->session->get('member')) {
            $this->session->destroy();
        }
        $this->session->remove('otp');
        if($this->session->get('otp')) {
            $this->session->destroy();
        }
        return redirect()->to('/login');
    }
}
