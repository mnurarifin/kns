<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Login_model;
use Config\Services;

class Login_admin extends BaseController
{
    private $redirectUrl = '';

    public function __construct()
    {
        $this->login_model = new Login_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        if (session()->get('admin') && $this->session->get('admin')['is_logged_in'] == TRUE) {
            header("Location: " . getenv("app.baseURL") . "/admin/dashboard");
            die();
        } else {
            if ($this->request->getGet('redirect_url') && trim($this->request->getGet('redirect_url')) != '') {
                $data['redirect_url'] = $_GET['redirect_url'];
            } else {
                $data['redirect_url'] = '';
            }

            $data['formAction'] = base_url('/login-admin/verify');
            $this->template->title('Login');
            $this->template->content("Pub/loginAdminView", $data);
            $this->template->show('Template/Pub/login');
        }
    }

    public function verify()
    {
        validateMethod($this->request, 'post');
        $params = getRequestParamsData($this->request, 'post');

        $this->validation->setRules([
            'username' => [
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'captcha' => [
                'label' => 'Kode Unik',
                'rules' => 'required|check_captcha',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'check_captcha' => '{field} tidak sesuai',
                ],
            ],
        ]);
        if (!$this->validation->run((array) $params)) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        } else {
            $this->db->transBegin();
            try {
                $this->login((object)$params);

                $this->db->transCommit();
                $this->restLib->responseSuccess("Login sukses.", ["url" => '/admin/dashboard']);
            } catch (\Exception $e) {
                $this->db->transRollback();
                $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
            }
        }
    }

    public function login($params)
    {
        $obj_admin_account = $this->login_model->getAdminAccount($params->username);

        if (!$obj_admin_account || !password_verify($params->password, $obj_admin_account->administrator_password)) {
            throw new \Exception('Username atau Password tidak sesuai.', 1);
        }

        $admin_id = $obj_admin_account->administrator_id;
        $obj_admin = $this->login_model->getAdmin($admin_id);
        if ($obj_admin->administrator_is_active == 0) {
            throw new \Exception('Pengguna tidak aktif.', 1);
        }
        if ($obj_admin->administrator_image != '' && file_exists(UPLOAD_PATH . URL_IMG_ADMIN . $obj_admin->administrator_image)) {
            $obj_admin->administrator_image = UPLOAD_URL . URL_IMG_ADMIN . "{$obj_admin->administrator_image}";
        } else {
            $obj_admin->administrator_image = UPLOAD_URL . URL_IMG_ADMIN . "_default.png";
        }

        if ($obj_admin->administrator_group_type == 'superuser') {
            $admin_menu = $this->login_model->getSuperuserMenu();
        } else {
            $admin_menu = $this->login_model->getAdministratorMenu($obj_admin->administrator_group_id);
        }

        foreach ($admin_menu as $rowMenu) {
            $session_admin_menu[$rowMenu->administrator_menu_par_id][$rowMenu->administrator_menu_order_by] = (array)$rowMenu;
        }

        $obj_session = [
            'admin_id' => $obj_admin->administrator_id,
            'admin_account_username' => $params->username,
            'admin_name' => $obj_admin->administrator_name,
            'admin_last_login' => $obj_admin->administrator_last_login,
            'admin_image' => $obj_admin->administrator_image,
            'admin_menu' => $session_admin_menu,
            'is_logged_in' => TRUE,
        ];
        $this->session->set(['admin' => $obj_session]);

        $this->db->table("site_administrator")->update(["administrator_last_login" => $this->datetime], ["administrator_id" => $obj_admin->administrator_id]);

        $arr_user_agent = getUserAgent();
        $session_created = $this->datetime;
        $session_expired = date("Y-m-d H:i:s", strtotime($session_created) + JWT_LIFE_TIME);
        $arr_data = [
            'admin_access_admin_id' => $admin_id,
            'admin_access_session' => session_id(),
            'admin_access_session_created' => $session_created,
            'admin_access_session_expired' => $session_expired,
            'admin_access_ip_address' => $this->request->getIpAddress(),
            'admin_access_login_datetime' => $session_created,
            'admin_access_user_agent' => $arr_user_agent['user_agent'],
            'admin_access_platform' => $arr_user_agent['platform'],
            'admin_access_device' => $arr_user_agent['device'],
            'admin_access_app' => 'web',
        ];
        if (!$this->common_model->insertData('log_admin_access', $arr_data)) {
            throw new \Exception("Proses gagal silakan coba kembali.", 1);
        }
    }

    public function getCaptcha()
    {
        $config = array(
            'background_image' => 'captcha-widget-1.png',
            'image_width' => 296,
            'image_height' => 53,
        );
        $captcha = Services::Captcha();
        $captcha->generate_image($config);
    }

    public function getRedirect()
    {
        if (trim($this->redirectUrl) != '') {
            return '/login/show' . '?redirect_url' . $this->redirectUrl;
        } else {
            return '/login/show';
        }
    }
}
