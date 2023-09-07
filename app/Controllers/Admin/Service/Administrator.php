<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\AdministratorModel;
use Config\Services;

class Administrator extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->administratorModel = new AdministratorModel();
    }

    /*
    //DAFTAR ADMINISTRATOR
    //================================================================================
    */
    public function getDataAdministrator()
    {
        $tableName = 'site_administrator';
        $columns = array(
            'administrator_id',
            'administrator_username',
            'administrator_name',
            'administrator_last_login',
            'administrator_is_active',
            'administrator_email',
            'administrator_group_type',
            'administrator_administrator_group_id',
            'administrator_group_title'
        );
        $joinTable = 'join site_administrator_group on administrator_group_id = administrator_administrator_group_id';
        $whereCondition = '1 ';

        if ($this->session->administrator_group_type !== 'superuser') {
            $whereCondition .= "AND administrator_group_id != '1'";
        }

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['administrator_last_login']) {
                    $data['results'][$key]['administrator_last_login'] = $this->functionLib->convertDatetime($row['administrator_last_login'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Administrator Action', $data);
    }

    public function getDetailMenuAdministratorGroup()
    {
        $id = $this->request->getPost('administratorGroupId');
        $data = $this->administratorModel->getDetailMenuAdministratorGroup($id);

        $this->createRespon(200, 'Data Detail Administrator Group', $data);
    }

    function getSuperuserMenu()
    {
        $queryMenu = $this->administratorModel->getSuperuserMenu();

        $this->createRespon(200, 'Data Menu', $queryMenu);
    }

    public function getDetailAdministrator()
    {
        $id = $this->request->getPost('administratorId');
        $data = $this->administratorModel->getDetailAdministrator($id);
        $this->createRespon(200, 'Data Detail Administrator', $data);
    }

    public function actAddAdministrator()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'administratorUsername' => [
                'label' => 'username',
                'rules' => 'required|is_unique[site_administrator.administrator_username]|alpha_dash',
                'errors' => [
                    'required' => 'Username tidak boleh kosong',
                    'is_unique' => 'Username sudah digunakan',
                    'alpha_dash' => 'Username tidak sesuai'
                ],
            ],
            'administratorName' => [
                'label' => 'nama',
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Nama tidak boleh kosong',
                    'alpha_space' => 'Pengisian nama tidak sesuai'
                ],
            ],
            'administratorPassword' => [
                'label' => 'password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password tidak boleh kosong',
                ],
            ],
            'administratorEmail' => [
                'label' => 'email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email tidak boleh kosong',
                    'valid_email' => 'Email tidak valid',
                ],
            ],
            'optionGroup' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Title tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorId = $this->request->getPost('administratorId');
            $optionGroup = $this->request->getPost('optionGroup');
            $administratorUsername = $this->request->getPost('administratorUsername');
            $administratorName = $this->request->getPost('administratorName');
            $administratorEmail = $this->request->getPost('administratorEmail');
            $passHash = password_hash($this->request->getPost('administratorPassword'), PASSWORD_DEFAULT);

            $data = array();
            $data['administrator_id'] = $administratorId;
            $data['administrator_administrator_group_id'] = $optionGroup;
            $data['administrator_username'] = $administratorUsername;
            $data['administrator_name'] = $administratorName;
            $data['administrator_email'] = $administratorEmail;
            $data['administrator_password'] = $passHash;
            $res = $this->functionLib->insertData('site_administrator', $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Administrator berhasil ditambah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Administrator gagal ditambah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actUpdateAdministrator()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'administratorUsername' => [
                'label' => 'username',
                'rules' => 'required',
                'errors' => ['required' => 'username tidak boleh kosong'],
            ],
            'administratorName' => [
                'label' => 'nama',
                'rules' => 'required',
                'errors' => ['required' => 'nama tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorId = $this->request->getPost('administratorId');
            $optionGroup = $this->request->getPost('optionGroup');
            $administratorUsername = $this->request->getPost('administratorUsername');
            $administratorName = $this->request->getPost('administratorName');
            $administratorEmail = $this->request->getPost('administratorEmail');

            $data = array();
            $data['administrator_id'] = $administratorId;
            $data['administrator_administrator_group_id'] = $optionGroup;
            $data['administrator_username'] = $administratorUsername;
            $data['administrator_name'] = $administratorName;
            $data['administrator_email'] = $administratorEmail;
            $res = $this->functionLib->updateData('site_administrator', 'administrator_id', $administratorId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Administrator berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Administrator gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actEditPasswordAdministrator()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'password' => [
                'label' => 'password',
                'rules' => 'required|matches[passwordConf]',
                'errors' => ['required' => 'Password tidak boleh kosong!'],
            ],
            'passwordConf' => [
                'label' => 'Ulangi Password Baru',
                'rules' => 'required',
                'errors' => ['required' => 'Password tidak sama'],
            ],

        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorId = $this->request->getPost('id');
            $administratorPassword = $this->request->getPost('password');

            $data = array();
            $data['administrator_id'] = $administratorId;
            $passHash = password_hash($administratorPassword, PASSWORD_DEFAULT);
            $data['administrator_password'] = $passHash;

            $res = $this->functionLib->updateData('site_administrator', 'administrator_id', $administratorId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Password Administrator berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Password Administrator gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function removeAdministrator()
    {
        $dataAdministrator = $this->request->getPost('data');

        if (is_array($dataAdministrator)) {
            $success = $failed = 0;
            foreach ($dataAdministrator as $administratorId) {
                if ($this->administratorModel->removeAdministrator($administratorId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Administrator';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Administrator';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function activeAdministrator()
    {
        $dataAdministrator = $this->request->getPost('data');

        if (is_array($dataAdministrator)) {
            $success = $failed = 0;
            foreach ($dataAdministrator as $administratorId) {
                if ($this->administratorModel->activeAdministrator($administratorId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil aktifkan data Administrator!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Administrator';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function notActiveAdministrator()
    {
        $dataAdministrator = $this->request->getPost('data');

        if (is_array($dataAdministrator)) {
            $success = $failed = 0;
            foreach ($dataAdministrator as $administratorId) {
                if ($this->administratorModel->nonActiveAdministrator($administratorId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataNotAktive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil nonaktifkan data Administrator!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Administrator';
            }
            $this->createRespon(200, $message, $dataNotAktive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang dinonaktifkan!');
        }
    }

    /*
    //GROUP ADMINISTRATOR
    //================================================================================
    */
    public function getAdministratorGroup()
    {
        $data['results'] = $this->administratorModel->getDetailadministratorGroup($this->session->administrator_group_type);
        $this->createRespon(200, 'Data Administrator Action', $data);
    }

    function getDataEditGroup()
    {
        $id = $this->request->getGet('administrator_group_id');
        $queryEdit = $this->administratorModel->getDataEdit($id);

        $this->createRespon(200, 'Data Menu', $queryEdit);
    }

    public function getDataGroup()
    {
        $tableName = 'site_administrator_group';
        $columns = array(
            'administrator_group_id',
            'administrator_group_type',
            'administrator_group_title',
            'administrator_group_is_active',
        );
        $joinTable = '';
        $whereCondition = "1 ";

        if ($this->session->administrator_type == 'administrator') {
            $whereCondition .= "AND administrator_group_type = 'administrator' ";
        }

        if ($this->session->administrator_group_type !== 'superuser') {
            $whereCondition .= "AND administrator_group_id != '1' ";
        }

        $query = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Data Group', $query);
    }

    function addGroup()
    {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'title' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul Grup tidak boleh kosong',
                ],
            ],
            'item' => [
                'label' => 'item',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Hak akses Grup belum dipilih',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorGroupTitle = $this->request->getPost('title');
            $administratorGroupType = $this->request->getPost('type');
            $administratorGroupPrevilliage = $this->request->getPost('item');

            $data = array();
            $data['administrator_group_title'] = $administratorGroupTitle;
            $data['administrator_group_type'] = $administratorGroupType;
            $administratorGroupId = $this->functionLib->insertData('site_administrator_group', $data);

            //add privilege
            if ($administratorGroupType == 'administrator' && $this->request->getPost('item') != FALSE) {
                foreach ($administratorGroupPrevilliage as $id) {
                    $data = array();
                    $data['administrator_privilege_administrator_group_id'] = $administratorGroupId;
                    $data['administrator_privilege_administrator_menu_id'] = $id;
                    $data['administrator_privilege_action'] = '';
                    $addPrivillage = $this->functionLib->insertData('site_administrator_privilege', $data);
                }
            }

            if ($administratorGroupId && $addPrivillage != FALSE) {
                $result = array(
                    "message" => "Grup Administrator berhasil ditambahkan"
                );
            } else {
                $result = array(
                    "message" => "Grup Administrator gagal ditambahkan"
                );
            }
        }
        $this->createRespon(200, 'OK', $result);
    }

    function updateGroup()
    {
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'title' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul Grup tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );

            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorGroupId = $this->request->getPost('id');
            $administratorGroupType = $this->request->getPost('type');
            $administratorGroupTitle = $this->request->getPost('title');
            $administratorGroupPrevilliage = $this->request->getPost('item');

            $data = array();
            $data['administrator_group_title'] = $administratorGroupTitle;
            $data['administrator_group_type'] = $administratorGroupType;
            $addGroup = $this->functionLib->updateData('site_administrator_group', 'administrator_group_id', $administratorGroupId, $data);

            //delete privilege
            $deleteData = $this->functionLib->deleteData('site_administrator_privilege', 'administrator_privilege_administrator_group_id', $administratorGroupId);

            $addPrivillage = FALSE;
            //add privilege
            if ($administratorGroupType == 'administrator' && $this->request->getPost('item') != FALSE) {
                foreach ($administratorGroupPrevilliage as $id) {
                    $data = array();
                    $data['administrator_privilege_administrator_group_id'] = $administratorGroupId;
                    $data['administrator_privilege_administrator_menu_id'] = $id;
                    $data['administrator_privilege_action'] = '';
                    $addPrivillage = $this->functionLib->insertData('site_administrator_privilege', $data);
                }
            }

            if ($addGroup || ($deleteData && $addPrivillage != FALSE)) {
                $result = array(
                    "message" => "Grup Administrator berhasil diubah"
                );
            } else {
                $result = array(
                    "message" => "Grup Administrator gagal diubah"
                );
            }
        }
        $this->createRespon(200, 'OK', $result);
    }

    public function removeGroup()
    {
        $dataGroup = $this->request->getPost('data');

        if (is_array($dataGroup)) {
            $success = $failed = 0;
            foreach ($dataGroup as $id) {
                if ($this->administratorModel->removeGroup($id)) {
                    $this->administratorModel->removePrevillage($id);
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Group!';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Group!';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }

    function activeGroup()
    {
        $dataGroup = $this->request->getPost('data');

        if (is_array($dataGroup)) {
            $success = $failed = 0;
            foreach ($dataGroup as $id) {
                if ($this->administratorModel->activeGroup($id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Aktifkan Data Group!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Group!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }

    function NonActiveGroup()
    {
        $dataGroup = $this->request->getPost('data');

        if (is_array($dataGroup)) {
            $success = $failed = 0;
            foreach ($dataGroup as $id) {
                if ($this->administratorModel->nonActiveGroup($id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil NonAktifkan Data Group!';
            if ($success == 0) {
                $message = 'Gagal NonAktifkan Data Group!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan NonAktifkan!.');
        }
    }

    public function administrator_group_option()
    {
        $data = $this->administratorModel->administrator_group_option($this->session->administrator_group_type);
        echo json_encode($data);
    }
}
