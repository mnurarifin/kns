<?php

namespace App\Controllers\Admin;

class Dashboard extends BaseController
{

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
	}

	public function index()
	{
		$this->show();
	}

	public function show()
	{
		$data['arrBreadcrumbs'] = array(
			'Dashboard' => 'dashboard'
		);

		$data['admin_name'] = session("admin")["admin_name"];
		$data['last_login'] = session("admin")["admin_last_login"];

		$this->template->title('Dashboard');
		$this->template->content('Admin/welcomeView', $data);
		$this->template->show('Template/Admin/main');
	}
}
