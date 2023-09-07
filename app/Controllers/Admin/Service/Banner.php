<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\BannerModel;
use Config\Services;

class Banner extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->bannerModel = new BannerModel();
    }

    public function getDataBanner()
    {
        $tableName = 'site_banner';
        $columns = array(
            'banner_id',
            'banner_title',
            'banner_label',
            'banner_image',
            'banner_image_mobile',
            'banner_order_by',
            'banner_is_active',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Data Banner', $data);
    }

    public function actAddBanner()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'banner_title' => [
                'label' => 'banner_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'banner_image' => [
                'label' => 'Gambar',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'banner_image_mobile' => [
                'label' => 'Gambar Mobile',
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
                $banner_title = $this->request->getPost('banner_title');
                $banner_label = $this->request->getPost('banner_title');
                $banner_description = $this->request->getPost('banner_description');
                $banner_img = $this->request->getPost('banner_image');

                $data = [
                    'banner_title' => $banner_title,
                    'banner_label' => $banner_label,
                    'banner_description' => $banner_description,
                    'banner_image' => $banner_img,
                    'banner_image_mobile' => $this->request->getPost('banner_image_mobile'),
                    'banner_order_by' => $this->functionLib->getMax('site_banner', 'banner_order_by', []) + 1,
                    'banner_is_active' => 0,
                ];

                $this->db->table('site_banner')->insert($data);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah data banner", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil menambah data banner', 'OK');
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function actUpdateBanner()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'banner_title' => [
                'label' => 'banner_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'banner_image' => [
                'label' => 'Gambar',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'banner_image_mobile' => [
                'label' => 'Gambar Mobile',
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
                $banner_id = $this->request->getPost('banner_id');

                if (empty($banner_id)) {
                    throw new \Exception("Data Banner tidak bisa diubah", 1);
                }

                $update = [
                    'banner_title' => $this->request->getPost('banner_title'),
                    'banner_label' => $this->request->getPost('banner_title'),
                    'banner_description' => $this->request->getPost('banner_description'),
                    'banner_image' => $this->request->getPost('banner_image'),
                    'banner_image_mobile' => $this->request->getPost('banner_image_mobile'),
                ];

                $this->db->table('site_banner')
                    ->where('banner_id', $banner_id)
                    ->update($update);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data banner", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil update data banner', 'OK',);
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function activeBanner()
    {
        $banner_id = $this->request->getPost('data');

        $cek = $this->bannerModel->countActiveBanner();

        if (is_array($banner_id)) {
            $success = $failed = 0;
            foreach ($banner_id as $value) {
                $is_active = $this->bannerModel->bannerIsActive($value);

                if ($cek == 6 && $is_active == NULL) {
                    $failed++;
                } else {
                    $update = [
                        'banner_is_active' => 1,
                    ];

                    $this->db->table('site_banner')
                        ->where('banner_id', $value)
                        ->update($update);

                    if ($this->db->affectedRows() > 0) {
                        $success++;
                    } else {
                        $failed++;
                    }
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan Data Banner!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Banner';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function nonactiveBanner()
    {
        $banner_id = $this->request->getPost('data');

        if (is_array($banner_id)) {
            $success = $failed = 0;
            foreach ($banner_id as $value) {
                $update = [
                    'banner_is_active' => 0,
                ];

                $this->db->table('site_banner')
                    ->where('banner_id', $value)
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
            $message = 'Berhasil nonaktifkan Data Banner!';
            if ($success == 0) {
                $message = 'Gagal nonaktifkan Data Banner';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function deleteBanner()
    {
        $banner_id = $this->request->getPost('data');

        if (is_array($banner_id)) {
            $success = $failed = 0;
            foreach ($banner_id as $value) {
                $this->db->table('site_banner')
                    ->where('banner_id', $value)
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
            $message = 'Berhasil menghapus Data Banner!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Banner';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function setOrder()
    {
        $result = array();
        $result['data'] = [];

        $operator = $this->request->getPost('banner_operator');
        $orderBy = $this->request->getPost('banner_order_by');

        $this->db->transBegin();
        try {
            if (empty($orderBy)) {
                throw new \Exception("Gagal Proses", 1);
            }

            $this->bannerModel->changeOrderMenu($orderBy, $operator);
            $this->db->transCommit();

            $this->createRespon(200, 'Data Banner', $result);
        } catch (\Throwable $th) {
            $this->db->transRollback();

            $this->createRespon(400, $th->getMessage(), $result);
        }
    }

    public function uploadImage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'file' => [
                'label' => 'File',
                'rules' => 'max_size[file,3072]|mime_in[file,image/png,image/jpg,image/jpeg]',
                'errors' => [
                    'max_size' => 'File harus tidak lebih dari 3 mb',
                    'mime_in' => 'File format tidak valid'
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

                $img->move(UPLOAD_PATH . URL_IMG_BANNER, $filename);

                $data = [
                    'name' =>  $filename,
                    'type'  => $img->getClientMimeType(),
                    'temp' => $img->getTempName()
                ];
                $message = "Berhasil Mengupload File";

                $this->createRespon(200, $message, $data);
            } catch (\Throwable $th) {
                //throw $th;
                $this->createRespon(400, 'CODE_ERROR', $th->getTrace());
            }
        }
    }

    public function detailData()
    {
        $data = array();
        $data['results'] = $this->db->table('site_banner')
            ->getWhere(['banner_id' => $this->request->getGet('id')])
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Data Partner', $data);
        } else {
            $this->createRespon(400, 'Partner tidak ditemukan', ['results' => []]);
        }
    }
}
