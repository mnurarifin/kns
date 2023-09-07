<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;
use App\Libraries\Notification;

class Configuration extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function getDataConfig()
    {
        $tableName = 'sys_config';
        $columns = array(
            'config_id',
            'config_code',
            'config_name',
            'config_value'
        );
        $whereCondition = "";
        $joinTable = '';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Data Config', $data);
    }

    public function detailData()
    {
        $data = array();
        $data['results'] = $this->db->table('sys_config')
            ->getWhere(['config_id' => $this->request->getGet('id')])
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Data', $data);
        } else {
            $this->createRespon(400, 'tidak ditemukan', ['results' => []]);
        }
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'config_code' => [
                'label' => 'Kode Konfigurasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'config_name' => [
                'label' => 'Nama Konfigurasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'config_value' => [
                'label' => 'Isi',
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
                    'config_code' => $this->request->getPost('config_code'),
                    'config_name' => $this->request->getPost('config_name'),
                    'config_value' => $this->request->getPost('config_value'),
                ];

                $this->db->table('sys_config')->insert($data);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah data", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil menambah data', 'OK');
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
            'config_code' => [
                'label' => 'Kode Konfigurasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'config_name' => [
                'label' => 'Nama Konfigurasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'config_value' => [
                'label' => 'Isi',
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
                    'config_code' => $this->request->getPost('config_code'),
                    'config_name' => $this->request->getPost('config_name'),
                    'config_value' => $this->request->getPost('config_value'),
                ];

                $this->db->table('sys_config')->where('config_id', $this->request->getPost('config_id'))->update($data);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal mengubah data", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil mengubah data', 'OK');
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }

    public function remove()
    {
        $data = $this->request->getPost('data');

        if (is_array($data)) {
            $success = $failed = 0;
            foreach ($data as $id) {
                $this->db->table('sys_config')
                    ->where('config_id', $id)
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
            $message = 'Berhasil menghapus Data!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function blast()
    {
        $this->notification_lib = new Notification();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'message' => [
                'label' => 'Isi Pesan',
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
                $content = $this->request->getPost('message');

                $list_number = $this->db->table("sys_member")->select("member_mobilephone")->groupBy('member_mobilephone')->getWhere(["member_is_network" => 1])->getResult();

                foreach ($list_number as $key => $value) {
                    $this->notification_lib->send_waone($content, $value->member_mobilephone);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil Mengirim pesan', 'OK');
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, $th->getMessage(), 'Bad Request');
            }
        }
    }
}
