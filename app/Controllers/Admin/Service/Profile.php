<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\ProfileModel;
use Config\Services;



class Profile extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->profileModel = new ProfileModel();
    }


    public function getMyProfile()
    {
        $administrator_id = session('admin')['admin_id'];

        $data = array();
        $data['results'] = $this->profileModel->getAdminProfile($administrator_id);

        $this->createRespon(200, 'Data Profile ', $data);
    }

    public function updateMyProfile()
    {
        $administrator_id = session('admin')['admin_id'];

        $validation = \Config\Services::validation();
        $validation->setRules([
            'administrator_username' => [
                'label' => 'administrator_username',
                'rules' => "required|min_length[3]|is_unique[site_administrator.administrator_username,site_administrator.administrator_id,$administrator_id]",
                'errors' => [
                    'required' => 'Username tidak boleh kosong',
                    'min_length' => 'Username Minimal 3 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ],
            ],
            'administrator_name' => [
                'label' => 'administrator_name',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama tidak boleh kosong',
                    'min_length' => 'Nama Minimal 3 karakter'
                ],
            ],
            'administrator_email' => [
                'label' => 'administrator_email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email tidak boleh kosong',
                    'valid_email' => 'Format Email tidak valid'
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $data = array();

            try {
                $date = date('Y-m-d H:i:s');

                $data['administrator_username'] = $this->request->getPost('administrator_username');
                $data['administrator_name'] = $this->request->getPost('administrator_name');
                $data['administrator_email'] = $this->request->getPost('administrator_email');
                $data['administrator_image'] = $this->request->getPost('administrator_image');

                $this->profileModel->updateAdminProfile($data, ['administrator_id' =>  $administrator_id]);
                $this->session->set($data);

                $message = 'Profile berhasil diubah';
                $this->createRespon(200, $message, $data);
            } catch (\Throwable $th) {
                $this->createRespon(400, 'CODE_ERROR', $th);
            }
        }
    }

    public function updateMyPassword()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'administrator_old_password' => [
                'label' => 'administrator_old_password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password lama tidak boleh kosong',
                ],
            ],
            'administrator_password' => [
                'label' => 'administrator_password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password Baru tidak boleh kosong',
                    'min_length' => 'Password Baru Minimal 6 Karakter'
                ],
            ],
            'administrator_new_password' => [
                'label' => 'administrator_new_password',
                'rules' => 'required|matches[administrator_password]',
                'errors' => [
                    'required' => 'Konfirmasi Password tidak boleh kosong',
                    'matches' => 'Konfirmasi Password tidak sama dengan Password Baru'
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            try {
                $data = array();
                $administrator_id = session('admin')['admin_id'];
                $administrator_old_password =  $this->request->getPost('administrator_old_password');
                $administrator_password =  $this->request->getPost('administrator_password');

                $user = $this->profileModel->getAdminProfile($administrator_id);
                $password = $user->administrator_password;


                if ($user && password_verify($administrator_old_password, $password)) {
                    $data['administrator_password'] = password_hash($administrator_password, PASSWORD_DEFAULT);

                    $this->profileModel->updateAdminProfile($data, ['administrator_id' => $administrator_id]);

                    $message = 'Berhasil mengganti password';
                    $this->createRespon(200, $message, []);
                } else {
                    $data = ['validationMessage' => ['administrator_old_password' => 'Password lama tidak sesuai']];
                    $this->createRespon(400, 'validationError', $data);
                }
            } catch (\Throwable $th) {
                //throw $th;
                $this->createRespon(400, 'CODE_ERROR', $th);
            }
        }
    }

    public function uploadImage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'file' => [
                'label' => 'administrator_email',
                'rules' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/png]|max_size[file,3072]',
                'errors' => [
                    'uploaded' => 'File tidak boleh kosong',
                    'mime_in' => 'File format tidak valid',
                    'max_size' => 'File harus tidak lebih dari 3 mb'
                ],
            ]
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            try {
                //code...   
                $img = $this->request->getFile('file');
                $filename = $img->getRandomName();

                $img->move(APPPATH . '../public/upload/' . URL_IMG_ADMIN, $filename);

                $data = [
                    'name' =>  $img->getName(),
                    'type'  => $img->getClientMimeType(),
                    'temp' => $img->getTempName()
                ];
                $message = "Berhasil Mengupload File";

                $this->createRespon(200, $message, $data);
            } catch (\Throwable $th) {
                //throw $th;
                $this->createRespon(400, $th->getMessage(), $th);
            }
        }
    }
}
