<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\MenuModel;
use Config\Services;

class Menu extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->menuModel = new MenuModel();
    }

    /*
    //MENU ADMINISTRATOR
    //================================================================================
    */
    public function getDataMenu($menuParId = 0)
    {
        $tableName = 'site_administrator_menu';
        $columns = array(
            'administrator_menu_id',
            'administrator_menu_par_id',
            'administrator_menu_order_by',
            'administrator_menu_title',
            'administrator_menu_link',
            'administrator_menu_icon',
            'administrator_menu_class',
            'administrator_menu_is_active',
        );
        $joinTable = '';
        $whereCondition = "administrator_menu_par_id = '" . $menuParId . "'";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Menu', $data);
    }

    public function addMenu()
    {
        $validation = Services::validation();
        $validation->setRules([
            'titleMenu' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => ['required' => 'Judul tidak boleh kosong'],
            ],
            'linkMenu' => [
                'label' => 'link',
                'rules' => 'required',
                'errors' => ['required' => 'Link tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorMenuParId = $this->request->getPost('parId');
            $administratorMenuTitle = $this->request->getPost('titleMenu');
            $administratorMenuLink = $this->request->getPost('linkMenu');
            $administratorMenuClass = $this->request->getPost('iconMenu');
            $administratMenuOrderBy = $this->functionLib->getMax('site_administrator_menu', 'administrator_menu_order_by', array('administrator_menu_par_id' => $administratorMenuParId)) + 1;

            $data = array();
            $data['administrator_menu_par_id'] = $administratorMenuParId;
            $data['administrator_menu_title'] = $administratorMenuTitle;
            $data['administrator_menu_link'] = $administratorMenuLink;
            $data['administrator_menu_icon'] = $administratorMenuClass;
            $data['administrator_menu_order_by'] = $administratMenuOrderBy;
            $res =  $this->functionLib->insertData('site_administrator_menu', $data);

            $title = ($administratorMenuParId != 0) ? 'Sub Menu' : 'Menu';

            if ($res != FALSE) {
                $this->updateSessionAdministratorMenu();
                $result = array(
                    "message" => "$title berhasil ditambahkan"
                );
            } else {
                $result = array(
                    "message" => "$title gagal ditambahkan"
                );
            }
        }
        $this->createRespon(200, 'OK', $result);
    }

    public function updateMenu()
    {
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'titleMenu' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'judul tidak boleh kosong',
                ],
            ],
            'linkMenu' => [
                'label' => 'label',
                'rules' => 'required',
                'errors' => [
                    'required' => 'link tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );

            $this->createRespon(400, 'validationError', $result);
        } else {

            $administratorMenuParId = $this->request->getPost('parId');

            $administratorMenuId = $this->request->getPost('id');
            $administratorMenuTitle = $this->request->getPost('titleMenu');
            $administratorMenuLink = $this->request->getPost('linkMenu');
            $administratorMenuClass = $this->request->getPost('iconMenu');

            $data = array();
            $data['administrator_menu_title'] = $administratorMenuTitle;
            $data['administrator_menu_link'] = $administratorMenuLink;
            $data['administrator_menu_icon'] = $administratorMenuClass;
            $res = $this->functionLib->updateData('site_administrator_menu', 'administrator_menu_id', $administratorMenuId, $data);

            $title = ($administratorMenuParId != 0) ? 'Sub Menu' : 'Menu';

            if ($res != FALSE) {
                $result = array(
                    "message" => "$title berhasil diubah"
                );
                $this->updateSessionAdministratorMenu();
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "$title gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    function updateSessionAdministratorMenu()
    {
        if ($this->session->administrator_group_type == 'superuser') {
            $query_menu = $this->functionLib->getSuperuserMenu();
        } else {
            $query_menu = $this->functionLib->getAdministratorMenu($this->session->administrator_group_id);
        }
        $array_items = array(
            'administrator_menu' => $query_menu
        );
        $this->session->set($array_items);
    }

    public function removeMenu()
    {
        $dataMenu = $this->request->getPost('data');

        if (is_array($dataMenu)) {
            $success = $failed = 0;
            foreach ($dataMenu as $menu_id) {
                if ($this->menuModel->removeMenu($menu_id)) {
                    $this->menuModel->removePrivilegeByMenu($menu_id);

                    $subMenu = $this->menuModel->getSubMenuId($menu_id);
                    //remove subnya & privilegenya
                    if (!empty($subMenu)) {
                        foreach ($subMenu as $rowSub) {
                            $this->menuModel->removeMenu($rowSub->administrator_menu_id);
                            $this->menuModel->removePrivilegeByMenu($rowSub->administrator_menu_id);
                        }
                    }

                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];

            // update session menu admin
            if ($this->session->administrator_group_type == 'superuser') {
                $query_menu = $this->functionLib->getSuperuserMenu();
            } else {
                $query_menu = $this->functionLib->getAdministratorMenu($this->session->administrator_group_id);
            }
            $this->session->set(array('administrator_menu' => $query_menu));

            $message = 'Berhasil Hapus Data Menu!';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Menu!';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }

    public function activeMenu()
    {
        $dataMenu = $this->request->getPost('data');

        if (is_array($dataMenu)) {
            $success = $failed = 0;
            foreach ($dataMenu as $key => $menu_id) {
                if ($this->menuModel->activeMenu($menu_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];

            // update session menu admin
            if ($this->session->administrator_group_type == 'superuser') {
                $query_menu = $this->functionLib->getSuperuserMenu();
            } else {
                $query_menu = $this->functionLib->getAdministratorMenu($this->session->administrator_group_id);
            }
            $this->session->set(array('administrator_menu' => $query_menu));

            $message = 'Berhasil Aktifkan Data Menu!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Menu!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }

    public function nonActiveMenu()
    {
        $dataMenu = $this->request->getPost('data');
        if (is_array($dataMenu)) {
            $success = $failed = 0;
            foreach ($dataMenu as $key => $menu_id) {
                if ($this->menuModel->nonActiveMenu($menu_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];

            // update session menu admin
            if ($this->session->administrator_group_type == 'superuser') {
                $query_menu = $this->functionLib->getSuperuserMenu();
            } else {
                $query_menu = $this->functionLib->getAdministratorMenu($this->session->administrator_group_id);
            }
            $this->session->set(array('administrator_menu' => $query_menu));

            $message = 'Berhasil Nonaktifkan Data Menu!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Menu!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan Nonaktifkan!.');
        }
    }

    public function sortOrder()
    {

        $id = $this->request->getPost('idMenu');
        $orderBy = $this->request->getPost('orderMenu');

        if (!empty($id) && !empty($orderBy)) {
            if ($orderBy == 'up') {
                $op =  '<';
                $sort = 'DESC';
            } else {
                $op =  '>';
                $sort = 'ASC';
            }

            $hasil = $this->menuModel->ChangeOrderMenu($id, $op, $sort);
        }

        if ($hasil) {
            $result = array(
                "message" => "Urutan menu berhasil diubah"
            );

            $this->updateSessionAdministratorMenu();

            $this->createRespon(200, 'OK', $result);
        } else {
            $result = array(
                "message" => "Urutan menu gagal diubah"
            );
            $this->createRespon(400, 'Bad Request', $result);
        }
    }

    public function sortOrderPublicMenu()
    {

        $id = $this->request->getPost('idMenu');
        $orderBy = $this->request->getPost('orderMenu');

        if (!empty($id) && !empty($orderBy)) {
            if ($orderBy == 'up') {
                $op =  '<';
                $sort = 'DESC';
            } else {
                $op =  '>';
                $sort = 'ASC';
            }

            $hasil = $this->menuModel->ChangeOrderPublicMenu($id, $op, $sort);
        }

        if ($hasil) {
            $result = array(
                "message" => "Urutan menu berhasil diubah"
            );

            $this->createRespon(200, 'OK', $result);
        } else {
            $result = array(
                "message" => "Urutan menu gagal diubah"
            );
            $this->createRespon(400, 'Bad Request', $result);
        }
    }

    /*
    //MENU MEMBER
    //================================================================================
    */

    public function getDataMenuPublic($menuParId = 0)
    {
        $tableName = 'site_menu';
        $columns = array(
            'menu_id',
            'menu_title',
            'menu_link',
            'menu_location',
            'menu_content_id',
            'menu_par_id',
            'menu_order_by',
            'content_title',
            'menu_is_active',
        );
        $joinTable = 'LEFT JOIN site_content ON menu_content_id = content_id';
        $whereCondition = "menu_par_id = '" . $menuParId . "'";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Menu', $data);
    }

    public function addMenuPublic()
    {
        $validation = Services::validation();
        $validation->setRules([
            'titleMenu' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => ['required' => 'Judul tidak boleh kosong'],
            ],
            'linkMenu' => [
                'label' => 'link',
                'rules' => 'required',
                'errors' => ['required' => 'Link tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $administratorMenuParId = $this->request->getPost('parId');
            $administratorMenuTitle = $this->request->getPost('titleMenu');
            $administratorMenuLink = $this->request->getPost('linkMenu');
            $administratMenuOrderBy = $this->functionLib->getMax('site_menu', 'menu_order_by', array('menu_par_id' => $administratorMenuParId)) + 1;

            $data = array();
            $data['menu_par_id'] = $administratorMenuParId;
            $data['menu_title'] = $administratorMenuTitle;
            $data['menu_link'] = $administratorMenuLink;
            $data['menu_order_by'] = $administratMenuOrderBy;
            $res =  $this->functionLib->insertData('site_menu', $data);

            $title = ($administratorMenuParId != 0) ? 'Sub Menu' : 'Menu';

            if ($res != FALSE) {
                $result = array(
                    "message" => "$title berhasil ditambahkan"
                );
            } else {
                $result = array(
                    "message" => "$title gagal ditambahkan"
                );
            }
        }
        $this->createRespon(200, 'OK', $result);
    }

    public function updateMenuPublic()
    {
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'titleMenu' => [
                'label' => 'title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'judul tidak boleh kosong',
                ],
            ],
            'linkMenu' => [
                'label' => 'label',
                'rules' => 'required',
                'errors' => [
                    'required' => 'link tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );

            $this->createRespon(400, 'validationError', $result);
        } else {

            $administratorMenuParId = $this->request->getPost('parId');

            $administratorMenuId = $this->request->getPost('id');
            $administratorMenuTitle = $this->request->getPost('titleMenu');
            $administratorMenuLink = $this->request->getPost('linkMenu');

            $data = array();
            $data['menu_title'] = $administratorMenuTitle;
            $data['menu_link'] = $administratorMenuLink;
            $res = $this->functionLib->updateData('site_menu', 'menu_id', $administratorMenuId, $data);

            $title = ($administratorMenuParId != 0) ? 'Sub Menu' : 'Menu';

            if ($res != FALSE) {
                $result = array(
                    "message" => "$title berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "$title gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function removeMenuPublic()
    {
        $dataMenu = $this->request->getPost('data');

        if (is_array($dataMenu)) {
            $success = $failed = 0;
            foreach ($dataMenu as $menu_id) {
                if ($this->menuModel->removeMenuPublic($menu_id)) {

                    $subMenu = $this->menuModel->getSubMenuIdPublic($menu_id)->getResult();
                    if (!empty($subMenu)) {
                        foreach ($subMenu as $rowSub) {
                            $this->menuModel->removeMenuPublic($rowSub->menu_id);
                        }
                    }

                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];

            $message = 'Berhasil Hapus Data Menu!';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Menu!';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }

    public function activeMenuPublic()
    {
        $dataMenu = $this->request->getPost('data');

        if (is_array($dataMenu)) {
            $success = $failed = 0;
            foreach ($dataMenu as $key => $menu_id) {
                if ($this->menuModel->activeMenuPublic($menu_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];

            $message = 'Berhasil Aktifkan Data Menu!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Menu!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }

    public function nonActiveMenuPublic()
    {
        $dataMenu = $this->request->getPost('data');
        if (is_array($dataMenu)) {
            $success = $failed = 0;
            foreach ($dataMenu as $key => $menu_id) {
                if ($this->menuModel->nonActiveMenuPublic($menu_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];

            $message = 'Berhasil Nonaktifkan Data Menu!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Menu!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan Nonaktifkan!.');
        }
    }
}
