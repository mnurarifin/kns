<?php

namespace App\Controllers\Admin;

use Config\Services;

class System_login extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        if ($this->session->administrator_logged_in == TRUE) {
            return redirect()->to('/dashboard');
        } else {
            if ($this->request->getGet('redirect_url') && trim($this->request->getGet('redirect_url')) != '') {
                $data['redirect_url'] = $_GET['redirect_url'];
            } else {
                $data['redirect_url'] = '';
            }

            $data['formAction'] = site_url('system_login/verify');
            $this->template->title('Login Administrator');
            $this->template->content('Admin/loginView', $data);
            $this->template->show('template/login');
        }
    }

    public function verify()
    {
        $this->curl = \Config\Services::Curl();
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'username' => [
                'label' => 'username',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Username harus diisi !',
                ],
            ],
            'password' => [
                'label' => 'password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi !',
                ],
            ],
            'captcha' => [
                'label' => 'captcha',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode unik harus diisi !',
                ],
            ],
        ]);

        $redirectUrl = $this->request->getPost('redirect_url');
        $redirect = "";

        $captcha = Services::Captcha();

        if ($validation->run($this->request->getPost()) == FALSE) {
            foreach ($validation->getErrors() as $key => $value) {
                $this->session->setFlashdata($key, $value);
            }

            if (trim($redirectUrl) != '') {
                $redirectUrl = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
                $redirect = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
            } else {
                $redirectUrl = BACKEND_LOGIN_URI;
                $redirect = BACKEND_LOGIN_URI;
            }
        } else {
            if (!$captcha->verify($this->request->getPost('captcha'))) {
                $this->session->setFlashdata('errorCaptcha', 'Kode Unik tidak sesuai!');

                if (trim($redirectUrl) != '') {
                    $redirectUrl = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
                    $redirect = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
                } else {
                    $redirectUrl = BACKEND_LOGIN_URI;
                    $redirect = BACKEND_LOGIN_URI;
                }
            } else {
                $username = addslashes($this->request->getPost('username'));
                $password = addslashes($this->request->getPost('password'));

                $sql = "SELECT * FROM site_administrator LEFT JOIN site_administrator_group ON administrator_group_id = administrator_administrator_group_id WHERE administrator_username = ?";
                $row = $this->db->query($sql, [$username])->getRow();
                if (!empty($row)) {
                    if (password_verify($password, $row->administrator_password)) {
                        if ($row->administrator_group_is_active == 0) {
                            $this->session->setFlashdata('confirmation', '<p>Akun Anda tidak aktif</p><p>Silakan menginformasikan Admin Anda terlebih dahulu agar akun Anda Aktif kembali</p>');
                            if (trim($redirectUrl) != '') {
                                $redirect = BACKEND_LOGIN_URI . '?redirect_url' . $redirectUrl;
                            } else {
                                $redirect = BACKEND_LOGIN_URI;
                            }
                        } elseif ($row->administrator_group_is_active == '0') {
                            $this->session->setFlashdata('confirmation', '<p>Akun Anda tidak aktif.</p><p>Silakan menginformasikan Admin Anda terlebih dahulu agar akun Anda Aktif kembali.</p>');
                            if (trim($redirectUrl) != '') {
                                $redirect = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
                            } else {
                                $redirect = BACKEND_LOGIN_URI;
                            }
                        } else {
                            $sql = "SELECT * FROM site_administrator ORDER BY administrator_last_login DESC LIMIT 1";
                            $rowLastLogin = $this->db->query($sql)->getRow();

                            $queryMenu = array();
                            if ($row->administrator_group_type == 'superuser') {
                                $queryMenu = $this->functionLib->getSuperuserMenu();
                            } else {
                                $queryMenu = $this->functionLib->getAdministratorMenu($row->administrator_group_id);
                            }

                            $array_items = array(
                                'administrator_id' => $row->administrator_id,
                                'administrator_group_id' => $row->administrator_group_id,
                                'administrator_group_title' => $row->administrator_group_title,
                                'administrator_group_type' => $row->administrator_group_type,
                                'administrator_username' => $row->administrator_username,
                                'administrator_name' => $row->administrator_name,
                                'administrator_email' => $row->administrator_email,
                                'administrator_image' => $row->administrator_image,
                                'administrator_last_login' => $row->administrator_last_login,
                                'administrator_logged_in' => TRUE,
                                'administrator_last_last_login' => $rowLastLogin->administrator_last_login,
                                'administrator_last_username' => $rowLastLogin->administrator_username,
                                'administrator_last_name' => $rowLastLogin->administrator_name,
                                'administrator_menu' => $queryMenu,
                                'filemanager' => TRUE
                            );

                            $this->session->set($array_items);

                            $data = array();
                            $data['administrator_last_login'] = date('Y-m-d H:i:s');
                            $this->functionLib->updateData('site_administrator', 'administrator_id', $row->administrator_id, $data);

                            $redirect = $redirectUrl;
                        }
                    } else {
                        $this->session->setFlashdata('confirmation', '<p><b>Username</b> atau <b>Password</b> yang Anda masukkan salah.</p>');
                        if (trim($redirectUrl) != '') {
                            $redirect = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
                        } else {
                            $redirect = BACKEND_LOGIN_URI;
                        }
                    }
                } else {
                    $this->session->setFlashdata('confirmation', '<p><b>Username</b> atau <b>Password</b> yang Anda masukkan salah.</p>');
                    if (trim($redirectUrl) != '') {
                        $redirect = BACKEND_LOGIN_URI . '?redirect_url=' . $redirectUrl;
                    } else {
                        $redirect = BACKEND_LOGIN_URI;
                    }
                }
            }
        }
        return redirect()->to($redirect);
    }

    public function get_captcha()
    {
        $config = array(
            'image_width' => 265,
            'image_height' => 54,
        );
        $captcha = Services::Captcha();
        $captcha->generate_image($config);
    }
}
