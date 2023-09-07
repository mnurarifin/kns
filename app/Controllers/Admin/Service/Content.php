<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\ContentModel;
use Config\Services;

class Content extends BaseServiceController
{

    private $refContentModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->contentModel = new ContentModel();
    }

    public function getDataContent($category = '')
    {
        $tableName = 'site_content';
        $columns = array(
            'content_id',
            'content_title',
            'content_category_id',
            'content_category_name',
            'content_is_active',
            'content_input_datetime'
        );
        $joinTable = '
        JOIN site_content_category on content_category_id = content_content_category_id
        LEFT JOIN site_menu on content_id = menu_content_id
        ';

        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['content_input_datetime']) {
                    $data['results'][$key]['content_input_datetime'] = $this->functionLib->convertDatetime($row['content_input_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Content', $data);
    }

    public function getContentCategory()
    {
        $result = $this->contentModel->getContentCategory();
        if (!$result) {
            $this->createRespon(400, 'Gagal mendapatkan data kategori Artikel');
        }

        $this->createRespon(200, 'OK', $result);
    }

    public function uploadContentImage()
    {
        $image = $this->request->getFile('image');

        $maxUnitSize = preg_replace("/[0-9, ]/", "", ini_get('upload_max_filesize'));
        if ($maxUnitSize == 'K')
            $multiple = 1024;
        else if ($maxUnitSize == 'M')
            $multiple = 1048576;
        else if ($maxUnitSize == 'G')
            $multiple = 1073741824;
        else
            $multiple = 1;
        $maxSize = preg_replace("/[A-Z, ]/", "", ini_get('upload_max_filesize')) * $multiple;

        if (!$_FILES['image']['size'] > $maxSize)
            $this->createRespon(400, 'validationError', ['validationMessage' => ['image' => 'Ukuran file gambar terlalu besar. Max : ' . ini_get('upload_max_filesize')]]);

        if (!$image->isValid())
            $this->createRespon(400, 'validationError', ['validationMessage' => ['image' => 'Gambar tidak valid. ' . $image->getErrorString() . '(' . $image->getError() . ')']]);

        $newName = $image->getRandomName();
        $image->move(UPLOAD_PATH . URL_IMG_CONTENT, $newName);

        $data = [
            'results' => ['content_image' => $newName]
        ];

        if ($image->hasMoved())
            $this->createRespon(200, 'OK', $data);
        else
            $this->createRespon(400, 'Upload file gagal dilakukan');
    }

    public function actAddBusinessPlan()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'content_title' => [
                'label' => 'content_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'content_category_id' => [
                'label' => 'content_category_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori tidak boleh kosong',
                ],
            ],
            'content_body' => [
                'label' => 'content_body',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Isi tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $content_title = $this->request->getPost('content_title');
            $content_category_id = $this->request->getPost('content_category_id');
            $content_body = $this->request->getPost('content_body');

            $datetime = date('Y-m-d H:i:s');
            $data = [
                'content_title' => $content_title,
                'content_slug' => slugify($content_title),
                'content_content_category_id' => $content_category_id,
                'content_body' => $content_body,
                'content_image' => '',
                'content_input_administrator_id' => session('admin')['admin_id'],
                'content_input_datetime' => $datetime,
            ];

            $data_menu = [
                'is_menu' => false
            ];

            $result = $this->contentModel->addContent($data, $data_menu);
            if (!$result['status']) {
                $this->createRespon(400, 'Bad Request', $result['message']);
            }

            $this->createRespon(200, 'OK', 'Berhasil menambah data Business Plan');
        }
    }

    public function actUpdateBusinessPlan()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'content_title' => [
                'label' => 'content_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'content_category_id' => [
                'label' => 'content_category_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori tidak boleh kosong',
                ],
            ],
            'content_body' => [
                'label' => 'content_body',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Isi tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $content_title = $this->request->getPost('content_title');
            $content_category_id = $this->request->getPost('content_category_id');
            $content_body = $this->request->getPost('content_body');
            $content_id = $this->request->getPost('id');

            if (empty($content_id)) {
                $this->createRespon(400, 'Data Business Plan tidak bisa diubah');
            }

            $update = [
                'content_title' => $content_title,
                'content_slug' => slugify($content_title),
                'content_content_category_id' => $content_category_id,
                'content_body' => $content_body,
                'content_image' => '',
            ];
            $where = [
                'content_id' => $content_id
            ];
            $data_menu = [
                'is_menu' => false
            ];
            $result = $this->contentModel->updateContent($update, $where, $data_menu);
            if (!$result['status']) {
                $this->createRespon(400, 'Bad Request', $result['message']);
            }

            $this->createRespon(200, 'OK', $result['message']);
        }
    }

    public function actAddContent()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'content_title' => [
                'label' => 'content_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'content_category_id' => [
                'label' => 'content_category_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori tidak boleh kosong',
                ],
            ],
            'content_body' => [
                'label' => 'content_body',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Isi Artikel tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $content_title = $this->request->getPost('content_title');
            $content_category_id = $this->request->getPost('content_category_id');
            $content_body = $this->request->getPost('content_body');

            $path = UPLOAD_PATH . URL_IMG_CONTENT;
            $content_image = $this->request->getFile('content_image');
            $contentImage = '';
            if ($content_image->isValid() && file_exists($content_image)) {
                if ($content_image->getName() != '') {
                    $extension = pathinfo($content_image->getName(), PATHINFO_EXTENSION);
                    $fileName = date("YmdHis") . '.' . $extension;
                    $content_image->move($path, $fileName);
                    $contentImage = $fileName;
                }
            }

            $is_menu = $this->request->getPost('is_menu');
            $content_menu_type = $this->request->getPost('content_menu_type');
            $content_menu_parent = $this->request->getPost('content_menu_parent');
            $content_menu_link = $this->request->getPost('content_menu_link');

            if ($is_menu === 'true') {
                if (empty($content_menu_type)) {
                    $this->createRespon(400, 'Tipe Menu tidak boleh kosong');
                }

                if (!in_array($content_menu_type, ['parent', 'sub-parent'])) {
                    $this->createRespon(400, 'Tipe Menu tidak sesuai');
                }

                if ($content_menu_type == 'sub-parent' && empty($content_menu_parent)) {
                    $this->createRespon(400, 'Menu Utama tidak boleh kosong');
                }

                if (empty($content_menu_link)) {
                    $this->createRespon(400, 'Link Artikel tidak boleh kosong');
                }
            }

            $datetime = date('Y-m-d H:i:s');
            $data = [
                'content_title' => $content_title,
                'content_slug' => slugify($content_title),
                'content_content_category_id' => $content_category_id,
                'content_body' => $content_body,
                'content_image' => $contentImage,
                'content_input_administrator_id' => session('admin')['admin_id'],
                'content_input_datetime' => $datetime,
            ];

            $data_menu = [
                'is_menu' => $is_menu,
                'content_menu_type' => $content_menu_type,
                'content_menu_parent' => $content_menu_parent,
                'content_menu_link' => $content_menu_link
            ];

            $result = $this->contentModel->addContent($data, $data_menu);
            if (!$result['status']) {
                $this->createRespon(400, 'Bad Request', $result['message']);
            }

            $this->createRespon(200, 'OK', 'Berhasil menambah data Artikel');
        }
    }

    public function actUpdateContent()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'content_title' => [
                'label' => 'content_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'content_category_id' => [
                'label' => 'content_category_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori tidak boleh kosong',
                ],
            ],
            'content_body' => [
                'label' => 'content_body',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Isi Artikel tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $content_title = $this->request->getPost('content_title');
            $content_category_id = $this->request->getPost('content_category_id');
            $old_image = $this->request->getPost('old_image');
            $content_body = $this->request->getPost('content_body');
            $content_id = $this->request->getPost('id');

            $is_menu = $this->request->getPost('is_menu');
            $content_menu_type = $this->request->getPost('content_menu_type');
            $content_menu_parent = $this->request->getPost('content_menu_parent');
            $content_menu_link = $this->request->getPost('content_menu_link');

            if ($is_menu === 'true') {
                if (empty($content_menu_type)) {
                    $this->createRespon(400, 'Tipe Menu tidak boleh kosong');
                }

                if (!in_array($content_menu_type, ['parent', 'sub-parent'])) {
                    $this->createRespon(400, 'Tipe Menu tidak sesuai');
                }

                if ($content_menu_type == 'sub-parent' && empty($content_menu_parent)) {
                    $this->createRespon(400, 'Menu Utama tidak boleh kosong');
                }

                if (empty($content_menu_link)) {
                    $this->createRespon(400, 'Link Artikel tidak boleh kosong');
                }
            }

            if (empty($content_id)) {
                $this->createRespon(400, 'Data Artikel tidak bisa diubah');
            }

            $path = UPLOAD_PATH . URL_IMG_CONTENT;
            $content_image = $this->request->getFile('content_image');
            $contentImage = $old_image;
            if ($content_image->isValid() && file_exists($content_image)) {
                if ($content_image->getName() != '') {
                    $extension = pathinfo($content_image->getName(), PATHINFO_EXTENSION);
                    $fileName = $content_id . '-' . date("YmdHis") . '.' . $extension;
                    $content_image->move($path, $fileName);
                    $contentImage = $fileName;
                    if ($old_image) {
                        @unlink($path . $old_image);
                    }
                }
            }

            $update = [
                'content_title' => $content_title,
                'content_slug' => slugify($content_title),
                'content_content_category_id' => $content_category_id,
                'content_body' => $content_body,
                'content_image' => $contentImage,
            ];
            $where = [
                'content_id' => $content_id
            ];
            $data_menu = [
                'is_menu' => $is_menu,
                'content_menu_type' => $content_menu_type,
                'content_menu_parent' => $content_menu_parent,
                'content_menu_link' => $content_menu_link
            ];
            $result = $this->contentModel->updateContent($update, $where, $data_menu);
            if (!$result['status']) {
                $this->createRespon(400, 'Bad Request', $result['message']);
            }

            $this->createRespon(200, 'OK', $result['message']);
        }
    }

    public function deleteContent()
    {
        $content_id = $this->request->getPost('data');

        if (is_array($content_id)) {
            $success = $failed = 0;
            foreach ($content_id as $value) {
                $where = [
                    'content_id' => $value
                ];
                if ($this->contentModel->deleteContent($where)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menghapus Data Artikel!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Artikel';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data!');
        }
    }

    public function activeContent()
    {
        $content_id = $this->request->getPost('data');

        if (is_array($content_id)) {
            $success = $failed = 0;
            foreach ($content_id as $value) {
                $update = [
                    'content_is_active' => 1,
                ];
                $where = [
                    'content_id' => $value
                ];
                if ($this->contentModel->activeNonactiveContent($update, $where)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan Data Artikel!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Artikel';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function nonactiveContent()
    {
        $content_id = $this->request->getPost('data');

        if (is_array($content_id)) {
            $success = $failed = 0;
            foreach ($content_id as $value) {
                $update = [
                    'content_is_active' => 0,
                ];
                $where = [
                    'content_id' => $value
                ];
                if ($this->contentModel->activeNonactiveContent($update, $where)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menonaktifkan Data Artikel!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan Data Artikel';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data!');
        }
    }

    public function getMenuPublic()
    {
        $where = [
            'menu_par_id' => 0,
            'menu_location' => 'public'
        ];
        $result = $this->contentModel->getSiteMenu($where);
        if (!$result) {
            $this->createRespon(400, 'Gagal mendapatkan data menu');
        }

        $this->createRespon(200, 'OK', $result);
    }

    public function categoryList()
    {

        $tableName = 'site_content_category';
        $columns = array(
            'content_category_id',
            'content_category_name',
            'content_category_slug',
            'content_category_is_active',
            'content_category_is_one'
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Kategori Artikel', $data);
    }

    public function addCategory()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'category_name' => [
                'label' => 'Judul Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $data = [
                    'content_category_name' => $this->request->getPost('category_name'),
                    'content_category_slug' => slugify($this->request->getPost('category_name')),
                    'content_category_is_active' => 1,
                    'content_category_is_one' => $this->request->getPost('is_one')
                ];

                $this->db->table('site_content_category')->insert($data);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah data kategori", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', 'Berhasil menambah data Kategori');
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function editCategory()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'category_name' => [
                'label' => 'Judul Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $data = [
                    'content_category_name' => $this->request->getPost('category_name'),
                    'content_category_slug' => slugify($this->request->getPost('category_name')),
                    'content_category_is_active' => 1,
                    'content_category_is_one' => $this->request->getPost('is_one')
                ];

                $this->db->table('site_content_category')
                    ->where('content_category_id', $this->request->getPost('id'))
                    ->update($data);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal mengedit data kategori", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', 'Berhasil mengedit data kategori');
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function deleteCategory()
    {
        $category_id = $this->request->getPost('data');

        if (is_array($category_id)) {
            $success = $failed = 0;
            foreach ($category_id as $value) {
                $this->db->table('site_content_category')
                    ->where('content_category_id', $value)
                    ->delete();

                if ($this->db->affectedRows() > 0) {
                    $this->db->table('site_content')
                        ->where('content_content_category_id', $value)
                        ->delete();

                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menghapus Data Kategori Artikel';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Kategori Artikel';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data!');
        }
    }

    public function activeCategory()
    {
        $content_id = $this->request->getPost('data');

        if (is_array($content_id)) {
            $success = $failed = 0;
            foreach ($content_id as $value) {
                $update = [
                    'content_category_is_active' => 1,
                ];

                $this->db->table('site_content_category')
                    ->where('content_category_id', $value)
                    ->update($update);

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan Data Kategori Artikel!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Kategori Artikel';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function nonActiveCategory()
    {
        $content_id = $this->request->getPost('data');

        if (is_array($content_id)) {
            $success = $failed = 0;
            foreach ($content_id as $value) {
                $update = [
                    'content_category_is_active' => 0,
                ];

                $this->db->table('site_content_category')
                    ->where('content_category_id', $value)
                    ->update($update);

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menonaktifkan Data Kategori Artikel!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan Data Kategori Artikel';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data!');
        }
    }

    public function content_category_option()
    {
        $data = $this->contentModel->content_category_option();
        echo json_encode($data);
    }

    public function detailContent($content_id = 0)
    {
        $result = $this->contentModel->getDetailContent($content_id);
        if (!$result) {
            $this->createRespon(400, 'Gagal mendapatkan data content');
        }

        $this->createRespon(200, 'OK', $result);
    }
}
