<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;

class Announcement extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function data()
    {
        $tableName = 'site_running_text';
        $columns = array(
            'running_text_id',
            'running_text_description',
            'running_text_is_active'
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Data Running Text', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'running_text_description' => [
                'label' => 'Deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ]
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $insert = [
                    'running_text_description' => $this->request->getPost('running_text_description'),
                    'running_text_is_active' => 0
                ];

                $this->db->table('site_running_text')->insert($insert);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah data", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil tambah data', 'OK',);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function update()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'running_text_description' => [
                'label' => 'Deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ]
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $id = $this->request->getPost('running_text_id');

                if (empty($id)) {
                    throw new \Exception("Data Banner tidak bisa diubah", 1);
                }

                $update = [
                    'running_text_description' => $this->request->getPost('running_text_description'),
                ];

                $this->db->table('site_running_text')
                    ->where('running_text_id', $id)
                    ->update($update);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil update data', 'OK',);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function detail()
    {
        $data = array();
        $data['results'] = $this->db->table('site_running_text')
            ->getWhere(['running_text_id' => $this->request->getGet('id')])
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Detail Data', $data);
        } else {
            $this->createRespon(400, 'Data tidak ditemukan', ['results' => []]);
        }
    }

    public function remove()
    {
        $data = $this->request->getPost('data');

        if (is_array($data)) {
            $success = $failed = 0;
            foreach ($data as $id) {
                $this->db->table('site_running_text')
                    ->where('running_text_id', $id)
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
            $message = 'Berhasil menghapus Data Pengumuman!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data Pengumuman';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang dihapus!');
        }
    }

    public function active()
    {
        $data = $this->request->getPost('data');
        $cek = $this->db->table("site_running_text")->selectCount("running_text_id")->getWhere(["running_text_is_active" => 1])->getRow('running_text_id');

        $message = '';
        if (is_array($data)) {
            $success = $failed = 0;
            if (count($data) > 4 || (count($data) + $cek) > 4) {
                $failed++;
                $message = 'Maksimal 4 Pengumuman yang Aktif';
            } else {
                foreach ($data as $id) {
                    $is_active = $this->db->table('site_running_text')->getWhere(['running_text_is_active' => 1, 'running_text_id' => $id])->getRow('banner_id');

                    if ($cek == 4 && $is_active == NULL) {
                        $failed++;
                        $message = 'Maksimal 4 Pengumuman yang Aktif';
                    } else {
                        $update = [
                            'running_text_is_active' => 1,
                        ];

                        $this->db->table('site_running_text')
                            ->where('running_text_id', $id)
                            ->update($update);

                        if ($this->db->affectedRows() > 0) {
                            $success++;
                            $message = 'Berhasil mengaktifkan Data Pengumuman!';
                        } else {
                            $failed++;
                            $message = 'Gagal mengaktifkan Data Pengumuman';
                        }
                    }
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function nonactive()
    {
        $data = $this->request->getPost('data');

        if (is_array($data)) {
            $success = $failed = 0;
            foreach ($data as $id) {
                $update = [
                    'running_text_is_active' => 0,
                ];

                $this->db->table('site_running_text')
                    ->where('running_text_id', $id)
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
            $message = 'Berhasil nonaktifkan Data Pengumuman!';
            if ($success == 0) {
                $message = 'Gagal nonaktifkan Data Pengumuman';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }
}
