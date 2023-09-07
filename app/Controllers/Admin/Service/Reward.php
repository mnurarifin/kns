<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;
use App\Models\Admin\RewardModel;

class Reward extends BaseServiceController
{
    private $rewardModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->rewardModel = new RewardModel();
        $this->dataTable = Services::DataTableLib();

        $this->pathUpload = UPLOAD_PATH . URL_IMG_REWARD;
        $this->urlReward = UPLOAD_URL . URL_IMG_REWARD;
        $this->urlMember = UPLOAD_URL . URL_IMG_MEMBER;
    }

    //REWARD BINARY
    //=========================================================
    public function getDataReward()
    {
        $tableName = 'sys_reward';
        $columns = array(
            'reward_id',
            'reward_title',
            'reward_description',
            'reward_value',
            'reward_trip_point',
            'reward_condition_point',
            'reward_is_active',
            'reward_image_filename',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                $data['results'][$key]['reward_value_formatted'] = $this->functionLib->format_nominal("Rp ", $value['reward_value'], 2);
                $data['results'][$key]['reward_trip_point'] = (int) $value['reward_trip_point'];
                $data['results'][$key]['reward_condition_point'] = (int) $value['reward_condition_point'];
                $data['results'][$key]['reward_image_file_url'] = $value['reward_image_filename'] != '' ? $this->urlReward . $value['reward_image_filename'] : BASEURL . '/app-assets/images/icon/cup.png';
            }
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function getDataRewardTrip()
    {
        $tableName = 'sys_reward_trip';
        $columns = array(
            'reward_id',
            'reward_title',
            'reward_description',
            'reward_duration',
            'reward_value',
            'reward_condition_point',
            'reward_is_active',
            'reward_image_filename',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                $data['results'][$key]['reward_value_formatted'] = $this->functionLib->format_nominal("Rp ", $value['reward_value'], 2);
                $data['results'][$key]['reward_condition_point'] = (int) $value['reward_condition_point'];
                $data['results'][$key]['reward_image_file_url'] = $value['reward_image_filename'] != '' ? $this->urlReward . $value['reward_image_filename'] : BASEURL . '/app-assets/images/icon/cup.png';
            }
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function add()
    {
        try {
            $this->db->transBegin();
            $validation = \Config\Services::validation();
            $validation->setRules([
                'reward_title' => [
                    'label' => 'Nama Reward',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ],
                ],
                'reward_value' => [
                    'label' => 'Nilai Reward',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_condition_point' => [
                    'label' => 'Syarat Poin',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_trip_point' => [
                    'label' => 'Poin Trip',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
            ]);

            if ($validation->run($this->request->getPost()) == false) {
                $result = array(
                    "validationMessage" => $validation->getErrors(),
                );
                $this->createRespon(400, 'validationError', $result);
            }

            $image_file = $this->request->getFile('reward_image_filename');
            $filename = '';
            if ($image_file) {
                if ($image_file->getSizeByUnit('mb') > 5) {
                    $result = array(
                        "validationMessage" => [
                            "reward_image_filename" => "Ukuran maksimal 5MB."
                        ],
                    );
                    $this->createRespon(400, 'validationError', $result);
                } else {
                    $filename = $image_file->getRandomName();

                    $image_file->move(UPLOAD_PATH . URL_IMG_REWARD, $filename);
                    $this->functionLib->resizeImage(UPLOAD_PATH . URL_IMG_REWARD, UPLOAD_PATH  . URL_IMG_REWARD . $filename, $filename, 1000, 1000);
                }
            }

            $data = [
                'reward_title' => $this->request->getPost('reward_title'),
                'reward_value' => $this->request->getPost('reward_value'),
                'reward_condition_point' => $this->request->getPost('reward_condition_point'),
                'reward_trip_point' => $this->request->getPost('reward_trip_point'),
                'reward_image_filename' => $filename
            ];

            $this->db->table("sys_reward")->insert($data);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal Tambah data reward", 1);
            }

            $this->db->transCommit();
            $this->createRespon(200, 'Data Reward berhasil diubah', array("message" => "Data Reward berhasil ditambahkan"));
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), array("message" => "Data Reward gagal ditambahkan"));
        }
    }

    public function addTrip()
    {
        try {
            $this->db->transBegin();
            $validation = \Config\Services::validation();
            $validation->setRules([
                'reward_title' => [
                    'label' => 'Nama Reward',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ],
                ],
                'reward_value' => [
                    'label' => 'Nilai Reward',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_condition_point' => [
                    'label' => 'Syarat Poin',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_duration' => [
                    'label' => 'Durasi Trip',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
            ]);

            if ($validation->run($this->request->getPost()) == false) {
                $result = array(
                    "validationMessage" => $validation->getErrors(),
                );
                $this->createRespon(400, 'validationError', $result);
            }

            $image_file = $this->request->getFile('reward_image_filename');
            $filename = '';
            if ($image_file) {
                if ($image_file->getSizeByUnit('mb') > 5) {
                    $result = array(
                        "validationMessage" => [
                            "reward_image_filename" => "Ukuran maksimal 5MB."
                        ],
                    );
                    $this->createRespon(400, 'validationError', $result);
                } else {
                    $filename = $image_file->getRandomName();

                    $image_file->move(UPLOAD_PATH . URL_IMG_REWARD, $filename);
                }
            }

            $data = [
                'reward_title' => $this->request->getPost('reward_title'),
                'reward_value' => $this->request->getPost('reward_value'),
                'reward_condition_point' => $this->request->getPost('reward_condition_point'),
                'reward_image_filename' => $filename,
                'reward_duration' => $this->request->getPost('reward_duration')
            ];

            $this->db->table("sys_reward_trip")->insert($data);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal Tambah data reward", 1);
            }

            $this->db->transCommit();
            $this->createRespon(200, 'Data Reward berhasil diubah', array("message" => "Data Reward berhasil ditambahkan"));
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), array("message" => "Data Reward gagal ditambahkan"));
        }
    }

    public function edit()
    {
        try {
            $this->db->transBegin();
            $validation = \Config\Services::validation();
            $validation->setRules([
                'reward_title' => [
                    'label' => 'Nama Reward',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ],
                ],
                'reward_value' => [
                    'label' => 'Nilai Reward',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_condition_point' => [
                    'label' => 'Syarat Poin',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_trip_point' => [
                    'label' => 'Poin Trip',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
            ]);

            if ($validation->run($this->request->getPost()) == false) {
                $result = array(
                    "validationMessage" => $validation->getErrors(),
                );
                $this->createRespon(400, 'validationError', $result);
            }

            $data = [
                'reward_title' => $this->request->getPost('reward_title'),
                'reward_value' => $this->request->getPost('reward_value'),
                'reward_condition_point' => $this->request->getPost('reward_condition_point'),
                'reward_trip_point' => $this->request->getPost('reward_trip_point'),
            ];

            $image_file = $this->request->getFile('reward_image_filename');

            if ($image_file) {
                if ($image_file->getSizeByUnit('mb') > 5) {
                    $result = array(
                        "validationMessage" => [
                            "reward_image_filename" => "Ukuran maksimal 5MB."
                        ],
                    );
                    $this->createRespon(400, 'validationError', $result);
                } else {
                    $filename = $image_file->getRandomName();

                    $image_file->move(UPLOAD_PATH . URL_IMG_REWARD, $filename);
                    $this->functionLib->resizeImage(UPLOAD_PATH . URL_IMG_REWARD, UPLOAD_PATH  . URL_IMG_REWARD . $filename, $filename, 1000, 1000);

                    $data['reward_image_filename'] = $filename;
                }
            }

            $this->db->table("sys_reward")->where('reward_id', $this->request->getPost('reward_id'))->update($data);
            if ($this->db->affectedRows() < 0) {
                throw new \Exception("Gagal Ubah data reward", 1);
            }

            $this->db->transCommit();
            $this->createRespon(200, 'Data Reward berhasil diubah', array("message" => "Data Reward berhasil diubah"));
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), array("message" => "Data Reward gagal diubah"));
        }
    }

    public function editTrip()
    {
        try {
            $this->db->transBegin();
            $validation = \Config\Services::validation();
            $validation->setRules([
                'reward_title' => [
                    'label' => 'Nama Reward',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ],
                ],
                'reward_value' => [
                    'label' => 'Nilai Reward',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_condition_point' => [
                    'label' => 'Syarat Poin',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
                'reward_duration' => [
                    'label' => 'Durasi Trip',
                    'rules' => 'required',
                    'errors' => ['required' => '{field} tidak boleh kosong'],
                ],
            ]);

            if ($validation->run($this->request->getPost()) == false) {
                $result = array(
                    "validationMessage" => $validation->getErrors(),
                );
                $this->createRespon(400, 'validationError', $result);
            }

            $data = [
                'reward_title' => $this->request->getPost('reward_title'),
                'reward_value' => $this->request->getPost('reward_value'),
                'reward_condition_point' => $this->request->getPost('reward_condition_point'),
                'reward_duration' => $this->request->getPost('reward_duration')
            ];

            $image_file = $this->request->getFile('reward_image_filename');

            if ($image_file) {
                if ($image_file->getSizeByUnit('mb') > 5) {
                    $result = array(
                        "validationMessage" => [
                            "reward_image_filename" => "Ukuran maksimal 5MB."
                        ],
                    );
                    $this->createRespon(400, 'validationError', $result);
                } else {
                    $filename = $image_file->getRandomName();

                    $image_file->move(UPLOAD_PATH . URL_IMG_REWARD, $filename);

                    $data['reward_image_filename'] = $filename;
                }
            }

            $this->db->table("sys_reward_trip")->where('reward_id', $this->request->getPost('reward_id'))->update($data);
            if ($this->db->affectedRows() < 0) {
                throw new \Exception("Gagal Ubah data reward", 1);
            }

            $this->db->transCommit();
            $this->createRespon(200, 'Data Reward berhasil diubah', array("message" => "Data Reward berhasil diubah"));
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), array("message" => "Data Reward gagal diubah"));
        }
    }

    public function activeReward($type = 'bin')
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                if ($this->rewardModel->activeRewardModel($type, $rewardId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil aktifkan data Reward!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function notActiveReward($type = 'bin')
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                if ($this->rewardModel->nonActiveRewardModel($type, $rewardId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataNotAktive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil nonaktifkan data Reward!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Reward';
            }

            $this->createRespon(200, $message, $dataNotAktive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function removeReward($type = 'bin')
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                $imgReward = $this->functionLib->getOne($type . '_reward', 'reward_image_filename', ['reward_id' => $rewardId]);
                if ($this->rewardModel->removeRewardModel($type, $rewardId)) {
                    @unlink($this->pathUpload . $imgReward);
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataDeleted = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil Hapus Data Reward';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Reward';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function activeRewardTrip()
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                $this->db->table('sys_reward_trip')->where('reward_id', $rewardId)->update(['reward_is_active' => 1]);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil aktifkan data Reward!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function notActiveRewardTrip()
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                $this->db->table('sys_reward_trip')->where('reward_id', $rewardId)->update(['reward_is_active' => 0]);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataNotAktive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil nonaktifkan data Reward!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Reward';
            }

            $this->createRespon(200, $message, $dataNotAktive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function removeRewardTrip()
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                $imgReward = $this->functionLib->getOne('sys_reward_trip', 'reward_image_filename', ['reward_id' => $rewardId]);
                $this->db->table('sys_reward_trip')->where('reward_id', $rewardId)->delete();
                if ($this->db->affectedRows() >= 0) {
                    @unlink($this->pathUpload . $imgReward);
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataDeleted = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil Hapus Data Reward';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Reward';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function getDataApprovalReward()
    {
        $tableName = 'sys_reward_qualified';
        $columns = array(
            'reward_qualified_id',
            'reward_qualified_member_id',
            'reward_qualified_reward_id',
            'reward_qualified_reward_title',
            'reward_qualified_condition_point',
            'reward_qualified_reward_value',
            'reward_qualified_claim',
            'reward_qualified_datetime',
            'reward_qualified_status',
            'administrator_id',
            'administrator_name',
            'network_code',
            'member_name',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_image',
        );
        $joinTable = '
            LEFT JOIN sys_member ON member_id = reward_qualified_member_id
            LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
            LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
        ';
        $whereCondition = 'reward_qualified_status = "pending" && reward_qualified_claim != "unclaimed"';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['reward_qualified_condition_point'] = (int) $value['reward_qualified_condition_point'];
            $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', $value['reward_qualified_reward_value'], 2);
            $data['results'][$key]['reward_qualified_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_datetime'], 'id', 'text');
            $data['results'][$key]['member_image'] = $this->urlMember . (($value['member_image'] != '') ? $value['member_image'] : '_default.jpg');
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function getDataApprovalRewardTrip()
    {
        $tableName = 'sys_reward_trip_qualified';
        $columns = array(
            'reward_qualified_id',
            'reward_qualified_member_id',
            'reward_qualified_name',
            'reward_qualified_mobilephone',
            'reward_qualified_identity_no',
            'reward_qualified_reward_id',
            'reward_qualified_reward_title',
            'reward_qualified_condition_point',
            'reward_qualified_reward_value',
            'reward_qualified_claim',
            'reward_qualified_datetime',
            'reward_qualified_status',
            'administrator_id',
            'administrator_name',
            'network_code',
            'member_name',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_image',
        );
        $joinTable = '
            LEFT JOIN sys_member ON member_id = reward_qualified_member_id
            LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
            LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
        ';
        $whereCondition = 'reward_qualified_status = "pending" && reward_qualified_claim != "unclaimed"';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['reward_qualified_condition_point'] = (int) $value['reward_qualified_condition_point'];
            $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', $value['reward_qualified_reward_value'], 2);
            $data['results'][$key]['reward_qualified_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_datetime'], 'id', 'text');
            $data['results'][$key]['member_image'] = $this->urlMember . (($value['member_image'] != '') ? $value['member_image'] : '_default.jpg');
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function actApproveReward($type = 'sys')
    {
        $dataRewardQualified = $this->request->getPost('data');
        $datetime = date('Y-m-d H:i:s');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                if ($this->rewardModel->approveRewardQualified($type, session('admin')['admin_id'], $rewardQualifiedId, $datetime)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil approve data reward!';
            if ($success == 0) {
                $message = 'Gagal approve data reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diapprove!');
        }
    }

    public function actRejectReward($type = 'sys')
    {
        $dataRewardQualified = $this->request->getPost('data');
        $datetime = date('Y-m-d H:i:s');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                if ($this->rewardModel->rejectRewardQualified($type, session('admin')['admin_id'], $rewardQualifiedId, $datetime)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil reject data reward!';
            if ($success == 0) {
                $message = 'Gagal reject data reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang direject!');
        }
    }

    public function actApproveRewardTrip()
    {
        $dataRewardQualified = $this->request->getPost('data');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                $this->db->table('sys_reward_trip_qualified')->where('reward_qualified_id', $rewardQualifiedId)->update(['reward_qualified_status' => 'approved', 'reward_qualified_status_administrator_id' => session('admin')['admin_id'], 'reward_qualified_status_datetime' => date("Y-m-d H:i:s")]);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil approve data reward!';
            if ($success == 0) {
                $message = 'Gagal approve data reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diapprove!');
        }
    }

    public function actRejectRewardTrip()
    {
        $data = $this->request->getPost('data');
        $success = $failed = 0;
        try {
            if (!is_array($data)) {
                throw new \Exception("Error Processing Request", 1);
            }

            $this->db->transBegin();

            foreach ($data as $id) {
                $trip_qualified = $this->db->table("sys_reward_trip_qualified")->select("reward_qualified_member_id, reward_qualified_condition_point")->getWhere(["reward_qualified_id" => $id])->getRow();
                $this->db->table('sys_reward_trip_qualified')->where('reward_qualified_id', $id)->update(['reward_qualified_status' => 'rejected', 'reward_qualified_status_administrator_id' => session('admin')['admin_id'], 'reward_qualified_status_datetime' => date("Y-m-d H:i:s")]);
                if ($this->db->affectedRows() >= 0) {
                    $success++;

                    $last_point_trip_paid = $this->db->table("sys_reward_trip_point")->select("reward_point_paid")->getWhere(["reward_point_member_id" => $trip_qualified->reward_qualified_member_id])->getRow('reward_point_paid');

                    $this->db->table("sys_reward_trip_point")->where("reward_point_member_id", $trip_qualified->reward_qualified_member_id)->update(["reward_point_paid" => $last_point_trip_paid - $trip_qualified->reward_qualified_condition_point]);
                    if ($this->db->affectedRows() < 0) {
                        throw new \Exception("Gagal update reward poin trip", 1);
                    }

                    $arr_log_reward_point_trip = [
                        'reward_point_log_member_id' => $trip_qualified->reward_qualified_member_id,
                        'reward_point_log_type' => 'in',
                        'reward_point_log_value' => (int) $trip_qualified->reward_qualified_condition_point,
                        'reward_point_log_note' => 'Claim reward trip di tolak',
                        'reward_point_log_datetime' => date("Y-m-d H:i:s")
                    ];

                    $this->db->table("sys_reward_trip_point_log")->insert($arr_log_reward_point_trip);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal menambahkan reward poin trip", 1);
                    }
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil reject data reward trip!';
            if ($success == 0) {
                $message = 'Gagal reject data reward trip';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, 'Anda belum memilih data yang direject!');
        }
    }

    public function getDataHistoryApprovalReward()
    {
        $tableName = 'sys_reward_qualified';
        $columns = array(
            'reward_qualified_id',
            'reward_qualified_member_id',
            'reward_qualified_reward_id',
            'reward_qualified_reward_title',
            'reward_qualified_condition_point',
            'reward_qualified_reward_value',
            'reward_qualified_datetime',
            'reward_qualified_status',
            'reward_qualified_status_datetime',
            'administrator_id',
            'administrator_name',
            'administrator_image',
            'network_code',
            'member_name',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_image',
            'member_job',
            'city_name',
            'province_name',
        );
        $joinTable = '
            LEFT JOIN sys_member ON member_id = reward_qualified_member_id
            LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
            LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
            JOIN ref_city ON member_city_id = city_id
            JOIN ref_province ON member_province_id = province_id
        ';
        $whereCondition = 'reward_qualified_status != "pending"';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['reward_qualified_condition_point'] = (int) $value['reward_qualified_condition_point'];
            $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', ($value['reward_qualified_reward_value']), 2);
            $data['results'][$key]['reward_qualified_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_datetime'], 'id', 'text');
            $data['results'][$key]['reward_qualified_status_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_status_datetime'], 'id', 'text');

            if ($value['member_image'] != '' && file_exists($this->pathUpload . $value['member_image'])) {
                $data['results'][$key]['member_image'] = $this->urlMember . $value['member_image'];
            } else {
                $data['results'][$key]['member_image'] = $this->urlMember . '_default.png';
            }
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    // public function getDataCalonPenerimaReward()
    // {
    //     $tableName = 'sys_reward_qualified';
    //     $columns = array(
    //         'reward_qualified_id',
    //         'reward_qualified_member_id',
    //         'reward_qualified_reward_id',
    //         'reward_qualified_reward_title',
    //         'reward_qualified_condition_point',
    //         'reward_qualified_reward_value',
    //         'reward_qualified_datetime',
    //         'reward_qualified_status',
    //         'reward_qualified_status_datetime',
    //         'administrator_id',
    //         'administrator_name',
    //         'administrator_image',
    //         'network_code',
    //         'member_name',
    //         'member_image',
    //         'reward_point_member_id',
    //         'reward_point_acc'
    //     );
    //     $joinTable = '
    //         LEFT JOIN sys_member ON member_id = reward_qualified_member_id
    //         LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
    //         LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
    //         LEFT JOIN sys_reward_point ON reward_point_member_id = reward_point_member_id 
    // ';
    // $whereCondition = 'reward_point_member_id';

    // $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
    // foreach ($data['results'] as $key => $value) {
    //     $data['results'][$key]['reward_qualified_condition_point'] = (int) $value['reward_qualified_condition_point'];
    //     $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', ($value['reward_qualified_reward_value']), 2);
    //     $data['results'][$key]['reward_qualified_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_datetime'], 'id', 'text');
    //     $data['results'][$key]['reward_qualified_status_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_status_datetime'], 'id', 'text');

    //     if ($value['member_image'] != '' && file_exists($this->pathUpload . $value['member_image'])) {
    //         $data['results'][$key]['member_image'] = $this->urlMember . $value['member_image'];
    //     } else {
    //         $data['results'][$key]['member_image'] = $this->urlMember . '_default.png';
    //     }
    // }
    // $this->createRespon(200, 'Data Reward', $data);
    // }

    // public function getDataCalonPenerimaReward()
    // {
    //     $tableName = 'sys_reward_point';
    //     $columns = array(
    //         'reward_point_member_id',
    //         'reward_point_acc',
    //         'reward_point_paid',
    //         'network_code',
    //         'network_point',
    //         'member_name',
    //         'member_image',
    //         'reward_title',
    //     );
    //     $joinTable = '
    //         LEFT JOIN sys_member ON member_id = reward_point_member_id
    //         LEFT JOIN sys_network ON network_member_id = reward_point_member_id 
    //         LEFT JOIN sys_reward ON reward_title = reward_point_member_id                 
    // ';
    //     $whereCondition = '(reward_point_acc BETWEEN 54 AND 59) OR (reward_point_acc BETWEEN 174 AND 179) OR (reward_point_acc BETWEEN 494 AND 499) OR (reward_point_acc BETWEEN 4994 AND 4999) OR (reward_point_acc BETWEEN 9994 AND 9999) OR (reward_point_acc BETWEEN 34995 AND 34999) OR (reward_point_acc BETWEEN 144996 AND 144999) OR (reward_point_acc BETWEEN 499996 AND 499999) OR (reward_point_acc BETWEEN 999996 AND 999999)';
    //     $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
    //     // add the logic to set the reward_qualified_reward_title based on the reward_point_acc value
    //     foreach ($data['results'] as $key => $value) {
    //         if ($value['reward_point_acc'] >= 54 && $value['reward_point_acc'] <= 59) {
    //             $data['results'][$key]['reward_title'] = 'START UP';
    //             if ($value['reward_point_acc'] == 54) {
    //                 $whereCondition = '(reward_point_acc BETWEEN 54 AND 59)';
    //             }
    //         } else if ($value['reward_point_acc'] >= 174 && $value['reward_point_acc'] <= 179) {
    //             $data['results'][$key]['reward_title'] = 'SAPPHIRE';
    //         } else if ($value['reward_point_acc'] >= 494 && $value['reward_point_acc'] <= 499) {
    //             $data['results'][$key]['reward_title'] = 'RUBY';
    //         } else if ($value['reward_point_acc'] >= 1694 && $value['reward_point_acc'] <= 1699) {
    //             $data['results'][$key]['reward_title'] = 'PEARL';
    //         } else if ($value['reward_point_acc'] >= 4994 && $value['reward_point_acc'] <= 4999) {
    //             $data['results'][$key]['reward_title'] = 'EMERALD';
    //         } else if ($value['reward_point_acc'] >= 9994 && $value['reward_point_acc'] <= 9999) {
    //             $data['results'][$key]['reward_title'] = 'DIAMOND';
    //         } else if ($value['reward_point_acc'] >= 34995 && $value['reward_point_acc'] <= 34999) {
    //             $data['results'][$key]['reward_title'] = 'BLACK DIAMOND';
    //         } else if ($value['reward_point_acc'] >= 144996 && $value['reward_point_acc'] <= 144999) {
    //             $data['results'][$key]['reward_title'] = 'CROWN';
    //         } else if ($value['reward_point_acc'] >= 499996 && $value['reward_point_acc'] <= 499999) {
    //             $data['results'][$key]['reward_title'] = 'CROWN AMBASSADOR';
    //         } else if ($value['reward_point_acc'] >= 999996 && $value['reward_point_acc'] <= 999999) {
    //             $data['results'][$key]['reward_title'] = 'ROYAL CROWN AMBASSADOR';
    //         }

    //         if ($value['member_image'] != '' && file_exists($this->pathUpload . $value['member_image'])) {
    //             $data['results'][$key]['member_image'] = $this->urlMember . $value['member_image'];
    //         } else {
    //             $data['results'][$key]['member_image'] = $this->urlMember . '_default.png';
    //         }
    //     }
    //     // print_r($data);
    //     // die;
    //     $this->createRespon(200, 'Data Reward', $data);
    // }

    public function getDataCalonPenerimaReward()
    {
        $tableName = 'sys_reward_point';
        $columns = array(
            'reward_point_member_id',
            'reward_point_acc',
            'reward_point_paid',
            'network_code',
            'network_point',
            'member_name',
            'member_image',
            '(
                SELECT 
                    reward_title
                FROM sys_reward
                WHERE (reward_condition_point - 6) <= reward_point_acc AND 
                (reward_condition_point - 1)  >= reward_point_acc
            )' => 'reward_title'
        );
        $joinTable = '
        LEFT JOIN sys_member ON member_id = reward_point_member_id
        LEFT JOIN sys_network ON network_member_id = reward_point_member_id 
    ';
        $whereCondition = "
        (
            SELECT 
            reward_title
            FROM sys_reward
            WHERE (reward_condition_point - 6) <= reward_point_acc AND 
            (reward_condition_point - 1)  >= reward_point_acc
        ) IS NOT NULL
        ";
        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Data Reward', $data);
    }


    public function getDataHistoryApprovalRewardTrip()
    {
        $tableName = 'sys_reward_trip_qualified';
        $columns = array(
            'reward_qualified_id',
            'reward_qualified_member_id',
            'reward_qualified_name',
            'reward_qualified_mobilephone',
            'reward_qualified_identity_no',
            'reward_qualified_reward_id',
            'reward_qualified_reward_title',
            'reward_qualified_condition_point',
            'reward_qualified_reward_value',
            'reward_qualified_datetime',
            'reward_qualified_status',
            'reward_qualified_status_datetime',
            'administrator_id',
            'administrator_name',
            'administrator_image',
            'network_code',
            'member_name',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_image',
            'member_job',
            'city_name',
            'province_name',
        );
        $joinTable = '
            LEFT JOIN sys_member ON member_id = reward_qualified_member_id
            LEFT JOIN sys_network ON network_member_id = reward_qualified_member_id
            LEFT JOIN site_administrator ON administrator_id = reward_qualified_status_administrator_id
            JOIN ref_city ON member_city_id = city_id
            JOIN ref_province ON member_province_id = province_id
        ';
        $whereCondition = 'reward_qualified_status != "pending"';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['reward_qualified_condition_point'] = (int) $value['reward_qualified_condition_point'];
            $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', ($value['reward_qualified_reward_value']), 2);
            $data['results'][$key]['reward_qualified_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_datetime'], 'id', 'text');
            $data['results'][$key]['reward_qualified_status_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_status_datetime'], 'id', 'text');

            if ($value['member_image'] != '' && file_exists($this->pathUpload . $value['member_image'])) {
                $data['results'][$key]['member_image'] = $this->urlMember . $value['member_image'];
            } else {
                $data['results'][$key]['member_image'] = $this->urlMember . '_default.png';
            }
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function getDataRewardRo($type = 'bin')
    {
        $tableName = $type . '_reward_ro';
        $columns = array(
            'reward_id',
            'reward_title',
            'reward_descriptions',
            'reward_value as reward_bonus_value',
            'reward_condition_node_left',
            'reward_condition_node_right',
            'reward_is_active',
            'reward_image_filename',
            'reward_adm_charge_type',
            'reward_adm_charge',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($data['results'][$key]['reward_adm_charge_type'] == 'percent') {
                    $data['results'][$key]['reward_adm_charge_formatted'] = $value['reward_adm_charge'] . ' %';
                } else {
                    $data['results'][$key]['reward_adm_charge_formatted'] = $this->functionLib->format_nominal("Rp ", $value['reward_adm_charge'], 2);
                }

                $data['results'][$key]['reward_bonus_value'] = $this->functionLib->format_nominal("Rp ", $value['reward_bonus_value'], 2);
                $data['results'][$key]['reward_image_file_url'] = $this->urlReward . (($value['reward_image_filename'] != '') ? $value['reward_image_filename'] : '_default.jpg');
            }
        }
        $this->createRespon(200, 'Data Reward', $data);
    }

    public function actAddRewardRo($type = 'bin')
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'rewardTitle' => [
                'label' => 'Nama Reward',
                'rules' => 'required|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_numeric_space' => '{field} tidak sesuai',
                ],
            ],
            'rewardBonusValue' => [
                'label' => 'Nilai Reward',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'rewardCondRight' => [
                'label' => 'Syarat Kanan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'rewardCondLeft' => [
                'label' => 'Syarat Kiri',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'admChargeType' => [
                'label' => 'Tipe Potongan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'admCharge' => [
                'label' => 'Jumlah Potongan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $rewardTitle = $this->request->getPost('rewardTitle');
            $rewardBonusValue = $this->request->getPost('rewardBonusValue');
            $rewardCondLeft = $this->request->getPost('rewardCondLeft');
            $rewardCondRight = $this->request->getPost('rewardCondRight');
            $admChargeType = $this->request->getPost('admChargeType');
            $admCharge = $this->request->getPost('admCharge');
            $rewardImage = $this->request->getFile('rewardImageFilename');

            $data = array();
            $data['reward_title'] = $rewardTitle;
            $data['reward_value'] = $rewardBonusValue;
            $data['reward_condition_node_left'] = $rewardCondLeft;
            $data['reward_condition_node_right'] = $rewardCondRight;
            $data['reward_adm_charge_type'] = $admChargeType;
            $data['reward_adm_charge'] = $admCharge;

            if ($rewardImage->isValid() && file_exists($rewardImage)) {
                if ($rewardImage->getName() != '') {
                    $fileName = 'reward' . date("YmdHis") . '.' . $rewardImage->getClientExtension();
                    $rewardImage->move($this->pathUpload, $fileName);
                    $data['reward_image_filename'] = $fileName;
                }
            }

            $res = $this->functionLib->insertData($type . '_reward_ro', $data);

            if ($res != false) {
                $this->createRespon(200, 'OK', array("message" => "Data Reward berhasil ditambahkan"));
            } else {
                $this->createRespon(400, 'Bad Request', array("message" => "Data Reward gagal ditambahkan"));
            }
        }
    }

    public function actUpdateRewardRo($type = 'bin')
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'rewardTitle' => [
                'label' => 'Nama Reward',
                'rules' => 'required|alpha_numeric_space',
                'errors' => ['required' => '{field} tidak boleh kosong'],
                'alpha_numeric_space' => '{field} tidak sesuai',
            ],
            'rewardBonusValue' => [
                'label' => 'Nilai Reward',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'rewardCondRight' => [
                'label' => 'Syarat Kanan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'rewardCondLeft' => [
                'label' => 'Syarat Kiri',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'admChargeType' => [
                'label' => 'Tipe Potongan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'admCharge' => [
                'label' => 'Jumlah Potongan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {

            $rewardId = $this->request->getPost('rewardId');
            $rewardTitle = $this->request->getPost('rewardTitle');
            $rewardBonusValue = $this->request->getPost('rewardBonusValue');
            $rewardCondLeft = $this->request->getPost('rewardCondLeft');
            $rewardCondRight = $this->request->getPost('rewardCondRight');
            $rewardImage = $this->request->getFile('rewardImageFilename');
            $admChargeType = $this->request->getPost('admChargeType');
            $admCharge = $this->request->getPost('admCharge');
            $rewardOldImage = $this->request->getPost('oldImage');

            $data = array();
            $data['reward_title'] = $rewardTitle;
            $data['reward_value'] = $rewardBonusValue;
            $data['reward_condition_node_left'] = $rewardCondLeft;
            $data['reward_condition_node_right'] = $rewardCondRight;
            $data['reward_adm_charge_type'] = $admChargeType;
            $data['reward_adm_charge'] = $admCharge;

            if ($rewardImage->isValid() && file_exists($rewardImage)) {
                if ($rewardImage->getName() != '') {
                    $fileName = 'reward' . date("YmdHis") . '.' . $rewardImage->getClientExtension();
                    $rewardImage->move($this->pathUpload, $fileName);
                    $data['reward_image_filename'] = $fileName;

                    if ($rewardOldImage) {
                        @unlink($this->pathUpload . $rewardOldImage);
                    }
                }
            }

            $res = $this->functionLib->updateData($type . '_reward_ro', 'reward_id', $rewardId, $data);

            if ($res !== false) {
                $this->createRespon(200, 'OK', array("message" => "Data Reward berhasil diubah"));
            } else {
                $this->createRespon(400, 'Bad Request', array("message" => "Data Reward gagal diubah"));
            }
        }
    }

    public function activeRewardRo($type = 'bin')
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                if ($this->db->table('bin_reward_ro')->where('reward_id', $rewardId)->update(['reward_is_active' => 1])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil aktifkan data Reward!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function notActiveRewardRo($type = 'bin')
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                if ($this->db->table('bin_reward_ro')->where('reward_id', $rewardId)->update(['reward_is_active' => 0])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataNotAktive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil nonaktifkan data Reward!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Reward';
            }

            $this->createRespon(200, $message, $dataNotAktive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function removeRewardRo($type = 'bin')
    {
        $dataReward = $this->request->getPost('data');

        if (is_array($dataReward)) {
            $success = $failed = 0;
            foreach ($dataReward as $rewardId) {
                $imgReward = $this->functionLib->getOne($type . '_reward_ro', 'reward_image_filename', ['reward_id' => $rewardId]);
                if ($this->db->table('bin_reward_ro')->delete(['reward_id' => $rewardId])) {
                    @unlink($this->pathUpload . $imgReward);
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataDeleted = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil Hapus Data Reward';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Reward';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function getDataApprovalRewardRo($type = 'bin')
    {
        $tableName = $type . '_reward_ro_qualified';
        $columns = array(
            'reward_qualified_id',
            'reward_qualified_network_id',
            'reward_qualified_reward_id',
            'reward_qualified_reward_title',
            'reward_qualified_condition_node_left',
            'reward_qualified_condition_node_right',
            'reward_qualified_condition_sponsor',
            'reward_qualified_reward_value',
            'reward_qualified_reward_adm_charge',
            'reward_qualified_claim',
            'reward_qualified_date',
            'reward_qualified_status',
            'administrator_id',
            'administrator_name',
            'network_code',
            'member_name',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_image',
        );
        $joinTable = '
            LEFT JOIN ' . $type . '_network ON network_id = reward_qualified_network_id
            LEFT JOIN sys_member ON member_id = network_member_id
            LEFT JOIN site_administrator ON administrator_id = reward_qualified_administrator_id
        ';
        $whereCondition = 'reward_qualified_status = "pending" && reward_qualified_claim != "unclaimed"';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', $value['reward_qualified_reward_value'] - $value['reward_qualified_reward_adm_charge'], 2);
            $data['results'][$key]['reward_qualified_reward_adm_charge'] = $this->functionLib->format_nominal('Rp. ',  $value['reward_qualified_reward_adm_charge'], 2);
            $data['results'][$key]['reward_qualified_date'] = $this->functionLib->convertDatetime($value['reward_qualified_date'], 'id', 'text');
            $data['results'][$key]['member_image'] = $this->urlMember . (($value['member_image'] != '') ? $value['member_image'] : '_default.jpg');
        }

        $this->createRespon(200, 'Data Reward Ro', $data);
    }

    public function actApproveRewardRo($type = 'bin')
    {
        $dataRewardQualified = $this->request->getPost('data');
        $datetime = date('Y-m-d H:i:s');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                if ($this->db->table("{$type}_reward_ro_qualified")->where('reward_qualified_id', $rewardQualifiedId)->update([
                    'reward_qualified_status' => 'approved',
                    'reward_qualified_administrator_id' => session('admin')['admin_id'],
                    'reward_qualified_status_datetime' => $datetime
                ])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil approve data reward!';
            if ($success == 0) {
                $message = 'Gagal approve data reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diapprove!');
        }
    }

    public function actRejectRewardRo($type = 'bin')
    {
        $dataRewardQualified = $this->request->getPost('data');
        $datetime = date('Y-m-d H:i:s');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                if ($this->db->table("{$type}_reward_ro_qualified")->where('reward_qualified_id', $rewardQualifiedId)->update([
                    'reward_qualified_status' => 'rejected',
                    'reward_qualified_administrator_id' => session('admin')['admin_id'],
                    'reward_qualified_status_datetime' => $datetime
                ])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil reject data reward!';
            if ($success == 0) {
                $message = 'Gagal reject data reward';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang direject!');
        }
    }

    public function getDataHistoryApprovalRewardRo($type = 'bin')
    {
        $tableName = $type . '_reward_ro_qualified';
        $columns = array(
            'reward_qualified_id',
            'reward_qualified_network_id',
            'reward_qualified_reward_id',
            'reward_qualified_reward_title',
            'reward_qualified_condition_node_left',
            'reward_qualified_condition_node_right',
            'reward_qualified_condition_sponsor',
            'reward_qualified_reward_value',
            'reward_qualified_reward_adm_charge',
            'reward_qualified_date',
            'reward_qualified_status',
            'reward_qualified_status_datetime',
            'administrator_id',
            'administrator_name',
            'administrator_image',
            'network_code',
            'member_name',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_image',
        );
        $joinTable = '
            LEFT JOIN ' . $type . '_network ON network_id = reward_qualified_network_id
            LEFT JOIN sys_member ON member_id = network_member_id
            LEFT JOIN site_administrator ON administrator_id = reward_qualified_administrator_id
        ';
        $whereCondition = 'reward_qualified_status != "pending"';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['reward_qualified_reward_adm_charge'] = $this->functionLib->format_nominal('Rp. ', $value['reward_qualified_reward_adm_charge'], 2);
            $data['results'][$key]['reward_qualified_reward_value'] = $this->functionLib->format_nominal('Rp. ', ($value['reward_qualified_reward_value'] - $value['reward_qualified_reward_adm_charge']), 2);
            $data['results'][$key]['reward_qualified_date'] = $this->functionLib->convertDatetime($value['reward_qualified_date'], 'id', 'text');
            $data['results'][$key]['reward_qualified_status_datetime'] = $this->functionLib->convertDatetime($value['reward_qualified_status_datetime'], 'id', 'text');
            $data['results'][$key]['member_image'] = $this->urlMember . (($value['member_image'] != '') ? $value['member_image'] : '_default.jpg');
        }

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function getDataRank()
    {
        $data = $this->db->table('sys_rank')
            ->select('rank_id, rank_name')
            ->get()
            ->getResult();

        if ($data) {
            $this->createRespon(200, 'Data List Rank', $data);
        } else {
            $this->createRespon(400, 'Kesalahan sistem');
        }
    }
}
