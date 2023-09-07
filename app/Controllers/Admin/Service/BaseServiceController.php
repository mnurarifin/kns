<?php

namespace App\Controllers\Admin\Service;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use Config\Services;

class BaseServiceController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $template;
	protected $functionLib;
	protected $request;
	protected $session;
	protected $db;
	protected $helpers = ['common'];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();

		if (!$this->request->isAJAX()) {
			$this->createRespon(403, 'Forbidden');
		}

		$this->db = \Config\Database::connect();
		$this->session = Services::session();
		$this->template = Services::Template();
		$this->functionLib = Services::FunctionLib();
	}

	protected function createRespon($status, $message, $data = [])
	{
		$body = [
			'status' => $status,
			'message' => $message,
			'data' => $data
		];
		$this->response->setStatusCode($status)->setContentType('application/json')->setJSON($body)->send();
		exit;
	}
}
