<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\BusinessPlanModel;
use Config\Services;

class BusinessPlan extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->businessPlanModel = new BusinessPlanModel();

        helper(['form', 'url']);
        $this->db = \Config\Database::connect();
    }

    public function getDataBusinessPlan()
    {
        $tableName = 'site_business_page';
        $columns = array(
            'page_id',
            'page_content',
            'page_image',
            'page_order_by',
            'page_is_active',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Data Bisnis Plan', $data);
    }

    public function actAddBusinessPlan()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'page_content' => [
                'label' => 'page_content',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'page_image' => [
                'label' => 'Gambar',
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
                $page_content = $this->request->getPost('page_content');
                $page_img = $this->request->getPost('page_image');

                $data = [
                    'page_content' => $page_content,
                    'page_image' => $page_img,
                    'page_datetime' => date('Y-m-d H:i:s'),
                    'page_order_by' => $this->functionLib->getMax('site_business_page', 'page_order_by', []) + 1,
                    'page_is_active' => 0,
                ];

                $this->db->table('site_business_page')->insert($data);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah data Bisnis Plan", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil menambah data Bisnis Plan', 'OK');
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function actUpdateBusinessPlan()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'page_content' => [
                'label' => 'page_content',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                ],
            ],
            'page_image' => [
                'label' => 'Gambar',
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
                $page_id = $this->request->getPost('page_id');

                if (empty($page_id)) {
                    throw new \Exception("Data Bisnis tidak bisa diubah", 1);
                }

                $update = [
                    'page_content' => $this->request->getPost('page_content'),
                    'page_image' => $this->request->getPost('page_image'),
                    'page_oerder_by' => $this->request->getPost('page_oerder_by'),
                ];

                $this->db->table('site_business_page')
                    ->where('page_id', $page_id)
                    ->update($update);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data Bisnis", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil update data Bisnis', 'OK',);
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function activeBusinessPlan()
    {
        $page_id = $this->request->getPost('data');

        $cek = $this->businessPlanModel->countActiveBusiness();

        if (is_array($page_id)) {
            $success = $failed = 0;
            foreach ($page_id as $value) {
                $is_active = $this->businessPlanModel->businessIsActive($value);

                if ($cek == 6 && $is_active == NULL) {
                    $failed++;
                } else {
                    $update = [
                        'page_is_active' => 1,
                    ];

                    $this->db->table('site_business_page')
                        ->where('page_id', $value)
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
            $message = 'Berhasil mengaktifkan Data Bisnis Plan!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Bisnis Plan';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function nonactiveBusinessPlan()
    {
        $page_id = $this->request->getPost('data');

        if (is_array($page_id)) {
            $success = $failed = 0;
            foreach ($page_id as $value) {
                $update = [
                    'page_is_active' => 0,
                ];

                $this->db->table('site_business_page')
                    ->where('page_id', $value)
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
            $message = 'Berhasil nonaktifkan Data Bisnis Plan!';
            if ($success == 0) {
                $message = 'Gagal nonaktifkan Data Bisnis Plan';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function deleteBusinessPlan()
    {
        $page_id = $this->request->getPost('data');

        if (is_array($page_id)) {
            $success = $failed = 0;
            foreach ($page_id as $value) {
                $this->db->table('site_business_page')
                    ->where('page_id', $value)
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
            $message = 'Berhasil menghapus Data Business Plan!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Business Plan';
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

        $operator = $this->request->getPost('page_operator');
        $orderBy = $this->request->getPost('page_order_by');

        $this->db->transBegin();
        try {
            if (empty($orderBy) && empty($operator)) {
                throw new \Exception("Gagal Proses", 1);
            }

            $this->businessPlanModel->changeOrderMenu($orderBy, $operator);
            $this->db->transCommit();

            $this->createRespon(200, 'Data Bisnis Plan', $result);
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

                $img->move(UPLOAD_PATH . URL_IMG_BUSINESS, $filename);

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
        $data['results'] = $this->db->table('site_business_page')
            ->getWhere(['page_id' => $this->request->getGet('id')])
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Data Partner', $data);
        } else {
            $this->createRespon(400, 'Partner tidak ditemukan', ['results' => []]);
        }
    }
}
