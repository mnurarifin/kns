<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
//start addition
use App\Libraries\Rest;
use App\Libraries\Notification;
use App\Models\Common_model;
//end addition

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    //start addition
    protected $db;
    protected $session;
    protected $template;
    protected $common_model;
    protected $datetime;
    protected $date;
    protected $year;
    protected $yesterday;
    protected $validation;
    protected $restLib;
    //end addition

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    //protected $helpers = [];
    //start addition
    protected $helpers = ['common', 'validations', 'core'];
    //end addition

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
        //start addition
        $this->restLib = new Rest();
        $this->restLib->setAllowOrigin('*');
        $this->restLib->setAllowHeaders('*');
        $this->restLib->setAllowMethods(true);

        $this->common_model = new Common_model();

        $this->validation = \Config\Services::validation();

        $this->db = \Config\Database::connect();

        $this->session = service('session');

        $this->template = service('Template');
        $this->template->set('session', $this->session);

        $this->datetime = date('Y-m-d H:i:s');
        $this->date = date('Y-m-d', strtotime($this->datetime));
        $this->year = date('Y', strtotime($this->datetime));
        $this->yesterday = date("Y-m-d", strtotime($this->date . "-1 day"));
        //$this->checkMaintenance();
        //end addition
    }
}
