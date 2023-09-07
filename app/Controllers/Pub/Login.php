<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;

use App\Models\Pub\Login_model;
use App\Models\Member\Otp_model;

use Config\Services;
use Firebase\JWT\JWT;

class Login extends BaseController
{
    private $redirectUrl = '';

    protected $login_model;
    protected $otp_model;

    public function __construct()
    {
        $this->login_model = new Login_model();
        $this->otp_model = new Otp_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        if (session()->get('member') && $this->session->get('member')['is_logged_in'] == TRUE) {
            header("Location: " . getenv("app.baseURL") . "/member/dashboard");
            die();
        } else {
            if ($this->request->getGet('redirect_url') && trim($this->request->getGet('redirect_url')) != '') {
                $data['redirect_url'] = $_GET['redirect_url'];
            } else {
                $data['redirect_url'] = '';
            }

            $data['formAction'] = base_url('/login/verify');
            $this->template->title('Login');
            $this->template->content("Pub/loginView", $data);
            $this->template->show('Template/Pub/login');
        }
    }

    public function sso($token)
    {
        $this->db->transBegin();
        try {
            $redirect = $this->getRedirect();
            $decode_token = JWT::decode($token, JWT_KEY, ['HS256']);
            $params = [
                'username' => $decode_token->member_account_username,
                'password' => $decode_token->member_account_password,
            ];
            $this->login((object)$params, TRUE);

            $this->db->transCommit();
            return redirect()->to('/member/dashboard');
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->session->setFlashdata('confirmation', $e->getMessage());
            return redirect()->to($redirect);
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
                $this->login((object)$params, FALSE);

                $this->db->transCommit();
                $this->restLib->responseSuccess("Login sukses.", ["url" => '/member/dashboard']);
            } catch (\Exception $e) {
                $this->db->transRollback();
                $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
            }
        }
    }

    public function login($params, $sso)
    {
        $params->username = addcslashes($params->username, "'");
        $obj_member_account = $this->login_model->getMemberAccount($params->username);

        if ($sso) {
            if (!$obj_member_account || $params->password != $obj_member_account->member_account_password) {
                throw new \Exception('Username atau Password tidak sesuai.', 1);
            }
        } else {
            if (!$obj_member_account || !password_verify($params->password, $obj_member_account->member_account_password)) {
                throw new \Exception('Username atau Password tidak sesuai.', 1);
            }
        }

        $member_id = $obj_member_account->member_account_member_id;
        $obj_member = $this->login_model->getMember($member_id);

        if ($obj_member->member_is_expired == 1 and $obj_member->member_status == 0) {
            throw new \Exception('Pengguna tidak aktif.', 1);
        }
        if ($obj_member->member_image != '' && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $obj_member->member_image)) {
            $obj_member->member_image = UPLOAD_URL . URL_IMG_MEMBER . "{$obj_member->member_image}";
        } else {
            $obj_member->member_image = UPLOAD_URL . URL_IMG_MEMBER . "_default.png";
        }

        $is_stockist = $this->db->table("inv_stockist")->getWhere(["stockist_member_id" => $obj_member->member_id])->getRow();

        $obj_session = [
            'member_id' => $obj_member->member_id,
            'member_parent_member_id' => $obj_member->member_parent_member_id,
            'network_rank_id' => $obj_member->network_rank_id,
            'member_account_username' => $params->username,
            'member_name' => $obj_member->member_name,
            'member_image' => $obj_member->member_image,
            'network_code' => $obj_member->network_code ?: $params->username,
            'member_is_network' => $obj_member->member_is_network,
            'is_logged_in' => TRUE,
            'is_stockist' => is_null($is_stockist) ? FALSE : TRUE,
            'network_slug' => $obj_member->network_slug
        ];

        if (!is_null($is_stockist)) {
            $obj_session['stockist_type'] = $is_stockist->stockist_type;
        } else {
            $obj_session['stockist_type'] = '';
        }

        $this->session->set(['member' => $obj_session]);

        // if (getenv('CI_ENVIRONMENT') && getenv('CI_ENVIRONMENT') == 'development') {
        $this->session->set(['otp' => TRUE]);
        // }

        $arr_user_agent = getUserAgent();
        $session_created = $this->datetime;
        $session_expired = date("Y-m-d H:i:s", strtotime($session_created) + JWT_LIFE_TIME);
        $arr_data = [
            'member_access_member_id' => $member_id,
            'member_access_session' => session_id(),
            'member_access_session_created' => $session_created,
            'member_access_session_expired' => $session_expired,
            'member_access_ip_address' => $this->request->getIpAddress(),
            'member_access_login_datetime' => $session_created,
            'member_access_user_agent' => $arr_user_agent['user_agent'],
            'member_access_platform' => $arr_user_agent['platform'],
            'member_access_device' => $arr_user_agent['device'],
            'member_access_app' => 'web',
            'member_access_is_from_admin' => $sso ? "1" : "0",
        ];
        if (!$this->common_model->insertData('log_member_access', $arr_data)) {
            throw new \Exception("Proses gagal silakan coba kembali.", 1);
        }
    }


    public function checkMemberAccount($username = '')
    {
        try {
            if (!$username) {
                throw new \Exception('Silahkan Masukkan ID Mitra.', 1);
            }

            $username = addcslashes($username, "'");
            $obj_member_account = $this->login_model->getMemberAccount($username);

            if (!$obj_member_account) {
                throw new \Exception('ID Mitra tidak ditemukan', 1);
            }

            $this->restLib->responseSuccess("Verifikasi Sukses.", []);
        } catch (\Exception $e) {
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function sendVerifyOtp($username = '')
    {
        try {
            if (!$username) {
                throw new \Exception('Silahkan Masukkan ID Mitra.', 1);
            }

            $username = addcslashes($username, "'");
            $obj_member_account = $this->login_model->getMemberAccount($username);

            if (!$obj_member_account) {
                throw new \Exception('ID Mitra tidak ditemukan', 1);
            }

            $member_id = $obj_member_account->member_account_member_id;

            $this->otp_model->generateOTP($member_id);

            return $this->restLib->responseSuccess("Otp Telah Dikirim.", []);
        } catch (\Throwable $e) {
            return $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function verifyOtp($username, $otp)
    {
        try {
            if (!$username) {
                throw new \Exception('Silahkan Masukkan ID Mitra.', 1);
            }

            if (!$otp) {
                throw new \Exception('Silahkan Masukkan OTP.', 1);
            }

            $username = addcslashes($username, "'");
            $obj_member_account = $this->login_model->getMemberAccount($username);

            if (!$obj_member_account) {
                throw new \Exception('ID Mitra tidak ditemukan', 1);
            }

            $member_id = $obj_member_account->member_account_member_id;

            $this->otp_model->verify($member_id, $otp);

            return $this->restLib->responseSuccess("Otp Telah Diverifikasi.", []);
        } catch (\Throwable $e) {
            return $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function changePassword()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "otp" => [
                "label" => "OTP",
                "rules" => "required|numeric",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "numeric" => "{field} harus berupa angka.",
                ],
            ],
            "account_username" => [
                "label" => "ID Mitra",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "account_password" => [
                "label" => "Password",
                "rules" => "required|max_length[20]|min_length[6]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                ],
            ],
            "account_password_confirm" => [
                "label" => "Konfirmasi Password",
                "rules" => "required|matches[account_password]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "matches" => "{field} tidak sama dengan password.",
                ],
            ],
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            return $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        try {
            $obj_member_account = $this->login_model->getMemberByOTP($params->otp, $params->account_username);

            if (!$obj_member_account) {
                throw new \Exception('ID Mitra tidak ditemukan', 1);
            }

            $member_id = $obj_member_account->member_id;

            $this->db->table('sys_member_account')->where('member_account_member_id', $member_id)->update(['member_account_password' => password_hash($params->account_password, PASSWORD_DEFAULT)]);

            if ($this->db->affectedRows() == 0) {
                throw new \Exception('Proses Gagal Silahkan Coba Kembali.', 1);
            }

            return $this->restLib->responseSuccess("Password Telah Diubah.", []);
        } catch (\Throwable $e) {
            return $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
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
