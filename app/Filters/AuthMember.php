<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthMember implements FilterInterface
{
    private $ipWhitelist;
    private $ipWhitelistEnabled;
    private $basicUsername;
    private $basicPassword;
    private $request;

    public function __construct()
    {
        $this->session = Services::session();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_null($this->session->member)) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }

}
