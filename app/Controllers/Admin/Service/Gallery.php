<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\GalleryModel;
use Config\Services;

class Gallery extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->galleryModel = new GalleryModel();
    }

    public function getDataGallery()
    {
        $tableName = 'site_gallery';
        $columns = array(
            'gallery_id',
            'gallery_title',
            'gallery_slug',
            'gallery_category_id',
            'gallery_category_title',
            'gallery_description',
            'gallery_type',
            'gallery_file',
            'gallery_is_active',
            'gallery_order_by',
            'gallery_input_datetime'
        );
        $joinTable = 'JOIN site_gallery_category on gallery_category_id = gallery_gallery_category_id';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['gallery_input_datetime']) {
                    $data['results'][$key]['gallery_input_datetime'] = $this->functionLib->convertDatetime($row['gallery_input_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Gallery', $data);
    }

    public function actAddGallery()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gallery_title' => [
                'label' => 'gallery_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'gallery_category_id' => [
                'label' => 'gallery_category_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori tidak boleh kosong',
                ],
            ],
            'gallery_type' => [
                'label' => 'gallery_type',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tipe tidak boleh kosong',
                ],
            ],
            'gallery_file' => [
                'label' => 'gallery_file',
                'rules' => 'max_size[gallery_file,3072]|mime_in[gallery_file,image/png,image/jpg,image/jpeg]',
                'errors' => [
                    'max_size' => 'Gambar harus tidak lebih dari 3 mb',
                    'mime_in' => 'format gambar tidak valid'
                ],
            ]
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array("validationMessage" => $validation->getErrors());

            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $gallery_type = $this->request->getPost('gallery_type');
                $content = '';
                if ($gallery_type == 'video') {
                    $url = $this->request->getPost('gallery_file');

                    if (strpos($url, 'http') !== false) {
                        $url_watch = explode('v=', $url);

                        if (count($url_watch) > 1) {
                            $content = $url_watch[1];
                        } else {
                            $url_share = explode('/', $url);
                            $content = $url_share[3];
                        }
                    } else {
                        $this->createRespon(400, 'validationError', array(
                            "validationMessage" => array(
                                'gallery_file' => 'Link tidak valid'
                            ),
                        ));
                    }
                }

                if ($gallery_type == 'image') {
                    $gallery_file = $this->request->getFile('gallery_file');
                    if ($gallery_file->isValid()) {
                        $filename = $gallery_file->getRandomName();

                        $gallery_file->move(APPPATH . '../public/upload/' . URL_IMG_GALLERY, $filename);
                        $this->functionLib->resizeImage(APPPATH . '../public/upload/' . URL_IMG_GALLERY, APPPATH . '../public/upload/'  . URL_IMG_GALLERY . $filename, $filename, 1000, 1000);
                        $content = $filename;
                    }
                }

                $arr_insert_gallery = [
                    'gallery_title' => $this->request->getPost('gallery_title'),
                    'gallery_slug' => slugify($this->request->getPost('gallery_title')),
                    'gallery_gallery_category_id' => $this->request->getPost('gallery_category_id'),
                    'gallery_type' => $gallery_type,
                    'gallery_description' => $this->request->getPost('gallery_description'),
                    'gallery_file' => $content,
                    'gallery_input_administrator_id' => session("admin")['admin_id'],
                    'gallery_input_datetime' => date('Y-m-d H:i:s'),
                ];

                $this->db->table('site_gallery')->insert($arr_insert_gallery);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update data galeri", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', 'Berhasil menambah data Galeri');
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function actUpdateGallery()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gallery_title' => [
                'label' => 'gallery_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'gallery_category_id' => [
                'label' => 'gallery_category_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori tidak boleh kosong',
                ],
            ],
            'gallery_type' => [
                'label' => 'gallery_type',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tipe tidak boleh kosong',
                ],
            ],
            'gallery_file' => [
                'label' => 'gallery_file',
                'rules' => 'max_size[gallery_file,3072]|mime_in[gallery_file,image/png,image/jpg,image/jpeg]',
                'errors' => [
                    'max_size' => 'Gambar harus tidak lebih dari 3 mb',
                    'mime_in' => 'format gambar tidak valid'
                ],
            ]
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array("validationMessage" => $validation->getErrors());

            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $gallery_type = $this->request->getPost('gallery_type');
                $old_file = $this->request->getPost('old_file');

                $content = '';
                if ($gallery_type == 'video') {
                    $url = $this->request->getPost('gallery_file');

                    $url_watch = explode('v=', $url);

                    if (count($url_watch) > 1) {
                        $content = $url_watch[1];
                    } else {
                        $url_share = explode('/', $url);
                        $content = $url_share[3];
                    }
                }

                if ($gallery_type == 'image') {
                    $path = APPPATH . '../public/upload/' . URL_IMG_GALLERY;
                    $gallery_file = $this->request->getFile('gallery_file');
                    $content = $old_file;
                    if ($gallery_file->isValid() && file_exists($gallery_file)) {
                        if ($gallery_file->getName() != '') {
                            $extension = pathinfo($gallery_file->getName(), PATHINFO_EXTENSION);
                            $fileName = date("YmdHis") . '.' . $extension;

                            $image = \Config\Services::image()
                                ->withFile($gallery_file)
                                ->resize(720, 480, true, 'height')
                                ->save($path  . $fileName);
                            $content = $fileName;
                            if ($old_file) {
                                @unlink($path . $old_file);
                            }
                        }
                    }
                }

                $arr_update_gallery = [
                    'gallery_title' => $this->request->getPost('gallery_title'),
                    'gallery_slug' => slugify($this->request->getPost('gallery_title')),
                    'gallery_type' => $gallery_type,
                    'gallery_gallery_category_id' => $this->request->getPost('gallery_category_id'),
                    'gallery_description' => $this->request->getPost('gallery_description'),
                    'gallery_file' => $content,
                ];

                $this->db->table('site_gallery')
                    ->where('gallery_id', $this->request->getPost('id'))
                    ->update($arr_update_gallery);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal mengedit data Galeri", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', 'Berhasil update data Galeri');
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function deleteGallery()
    {
        $this->db->transBegin();
        try {
            $gallery_id = $this->request->getPost('data');
            $success = $failed = 0;

            if (!is_array($gallery_id)) {
                throw new \Exception("Anda belum memilih data yang dihapus!", 1);
            }

            foreach ($gallery_id as $value) {
                $this->db->table('site_gallery')
                    ->where('gallery_id', $value)
                    ->delete();

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
            $message = 'Berhasil menghapus Data Galeri!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Galeri';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function activeGallery()
    {
        $this->db->transBegin();
        try {
            $gallery_id = $this->request->getPost('data');
            $success = $failed = 0;

            foreach ($gallery_id as $value) {
                $update = [
                    'gallery_is_active' => 1,
                ];

                $this->db->table('site_gallery')
                    ->where('gallery_id', $value)
                    ->update($update);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan Data Galeri!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Galeri';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function nonactiveGallery()
    {
        $this->db->transBegin();
        try {
            $gallery_id = $this->request->getPost('data');
            $success = $failed = 0;

            foreach ($gallery_id as $value) {
                $update = [
                    'gallery_is_active' => 0,
                ];

                $this->db->table('site_gallery')
                    ->where('gallery_id', $value)
                    ->update($update);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menonaktifkan Data Galeri!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan Data Galeri';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function getGalleryCategory()
    {
        $tableName = 'site_gallery_category';
        $columns = array(
            'gallery_category_id ',
            'gallery_category_title',
            'gallery_category_description',
            'gallery_category_image',
            'gallery_category_is_active',
            'gallery_category_input_administrator_id',
            'gallery_category_input_datetime',
            'administrator_name',
        );
        $joinTable = 'LEFT JOIN site_administrator on administrator_id = gallery_category_input_administrator_id';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['gallery_category_input_datetime']) {
                    $data['results'][$key]['gallery_category_input_datetime'] = $this->functionLib->convertDatetime($row['gallery_category_input_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Kategori Gallery', $data);
    }

    public function actAddGalleryCategory()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gallery_category_title' => [
                'label' => 'gallery_category_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
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
                $path = APPPATH . '../public/upload/' . URL_IMG_GALLERY_CATEGORY;
                $gallery_category_image = $this->request->getFile('gallery_category_image');
                $content = '';
                if ($gallery_category_image->isValid() && file_exists($gallery_category_image)) {
                    if ($gallery_category_image->getName() != '') {
                        $extension = pathinfo($gallery_category_image->getName(), PATHINFO_EXTENSION);
                        $fileName = date("YmdHis") . '.' . $extension;
                        $gallery_category_image->move($path, $fileName);
                        $content = $fileName;
                    }
                }

                $arr_insert_category_gallery = [
                    'gallery_category_title' => $this->request->getPost('gallery_category_title'),
                    'gallery_category_description' => $this->request->getPost('gallery_category_description'),
                    'gallery_category_image' => $content,
                    'gallery_category_input_administrator_id' => session('admin')['admin_id'],
                    'gallery_category_input_datetime' => date('Y-m-d H:i:s'),
                ];

                $this->db->table('site_gallery_category')->insert($arr_insert_category_gallery);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update data kategori galeri", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', 'Berhasil menambah data kategori galeri');
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function actUpdateGalleryCategory()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gallery_category_title' => [
                'label' => 'gallery_category_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
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
                $old_file = $this->request->getPost('old_file');
                $gallery_category_id = $this->request->getPost('id');
                $path = APPPATH . '../public/upload/' . URL_IMG_GALLERY_CATEGORY;
                $gallery_category_image = $this->request->getFile('gallery_category_image');
                $content = $old_file;
                if ($gallery_category_image->isValid() && file_exists($gallery_category_image)) {
                    if ($gallery_category_image->getName() != '') {
                        $extension = pathinfo($gallery_category_image->getName(), PATHINFO_EXTENSION);
                        $fileName = $gallery_category_id . '-' . date("YmdHis") . '.' . $extension;
                        $gallery_category_image->move($path, $fileName);
                        $content = $fileName;
                        if ($old_file) {
                            @unlink($path . $old_file);
                        }
                    }
                }

                $arr_update_category_gallery = [
                    'gallery_category_title' => $this->request->getPost('gallery_category_title'),
                    'gallery_category_description' => $this->request->getPost('gallery_category_description'),
                    'gallery_category_image' => $content,
                ];

                $this->db->table('site_gallery_category')
                    ->where('gallery_category_id', $gallery_category_id)
                    ->update($arr_update_category_gallery);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal mengedit data kategori galeri", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', 'Berhasil update data kategori galeri');
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function deleteGalleryCategory()
    {
        $this->db->transBegin();
        try {
            $gallery_category_id = $this->request->getPost('data');

            if (!is_array($gallery_category_id)) {
                throw new \Exception("Anda belum memilih data yang diaktifkan!", 1);
            }
            $success = $failed = 0;
            foreach ($gallery_category_id as $value) {
                $this->db->table('site_gallery_category')
                    ->where('gallery_category_id', $value)
                    ->delete();

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
            $message = 'Berhasil menghapus Data Kategori Galeri!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Kategori Galeri';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function activeGalleryCategory()
    {
        $this->db->transBegin();
        try {
            $gallery_category_id = $this->request->getPost('data');
            $success = $failed = 0;

            if (!is_array($gallery_category_id)) {
                throw new \Exception("Anda belum memilih data yang diaktifkan!", 1);
            }

            foreach ($gallery_category_id as $value) {
                $update = [
                    'gallery_category_is_active' => 1,
                ];

                $this->db->table('site_gallery_category')
                    ->where('gallery_category_id', $value)
                    ->update($update);

                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan Data Kategori Galeri!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Kategori Galeri';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function nonactiveGalleryCategory()
    {
        $this->db->transBegin();
        try {
            $gallery_category_id = $this->request->getPost('data');
            $success = $failed = 0;

            if (!is_array($gallery_category_id)) {
                throw new \Exception("Anda belum memilih data yang dinonaktifkan!", 1);
            }

            foreach ($gallery_category_id as $value) {
                $update = [
                    'gallery_category_is_active' => 0,
                ];

                $this->db->table('site_gallery_category')
                    ->where('gallery_category_id', $value)
                    ->update($update);

                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menonaktifkan Data Kategori Galeri!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan Data Kategori Galeri';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function gallery_category_option()
    {
        $data = $this->galleryModel->gallery_category_option();
        echo json_encode($data);
    }
}
