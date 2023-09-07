<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Libraries\DataTableLib;

class Ron extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->dataTable = new DataTableLib;
        $this->db = \Config\Database::connect();

        $this->urlRon = getenv('UPLOAD_URL') . URL_IMG_RON;
    }

    public function getData()
    {
        $tableName = 'bin_ron';
        $columns = array(
            'ron_id',
            'ron_title',
            'ron_descriptions',
            'ron_value',
            'ron_condition_node_left',
            'ron_condition_node_right',
            'ron_condition_sponsor',
            'ron_is_active',
            'ron_adm_charge',
            'ron_adm_charge_type',
            'ron_image_filename'
        );
        $joinTable = '';
        $whereCondition = '';

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['ron_image_filename'] = $this->urlRon . (($value['ron_image_filename'] != '') ? $value['ron_image_filename'] : '_default.jpg');
        }
        $this->createRespon(200, 'Data Reward', $data);
    }

    public function actAdd()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'ron_title' => [
                'label' => 'Nama RON',
                'rules' => 'required|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_numeric_space' => '{field} tidak sesuai',
                ],
            ],
            'ron_value' => [
                'label' => 'Nilai Reward',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'ron_condition_node_right' => [
                'label' => 'Syarat Kanan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'ron_condition_node_right' => [
                'label' => 'Syarat Kiri',
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
            $ron_title = $this->request->getPost('ron_title');
            $ron_value = $this->request->getPost('ron_value');
            $ron_condition_node_left = $this->request->getPost('ron_condition_node_left');
            $ron_condition_node_right = $this->request->getPost('ron_condition_node_right');

            $data = array();
            $data['ron_title'] = $ron_title;
            $data['ron_value'] = $ron_value;
            $data['ron_condition_node_left'] = $ron_condition_node_left;
            $data['ron_condition_node_right'] = $ron_condition_node_right;

            // if ($ron_image_filename->isValid() && file_exists($ron_image_filename)) {
            //     if ($ron_image_filename->getName() != '') {
            //         $fileName = 'reward' . date("YmdHis") . '.' . $ron_image_filename->getClientExtension();
            //         $ron_image_filename->move($this->pathUpload, $fileName);
            //         $data['ron_image_filename'] = $fileName;
            //     }
            // }

            $res = $this->functionLib->insertData('bin_ron', $data);

            if ($res != false) {
                $this->createRespon(200, 'OK', array("message" => "Data Reward berhasil ditambahkan"));
            } else {
                $this->createRespon(400, 'Bad Request', array("message" => "Data Reward gagal ditambahkan"));
            }
        }
    }

    public function actUpdate($type = 'bin')
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'ron_id' => [
                'label' => 'RON',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'ron_title' => [
                'label' => 'Nama RON',
                'rules' => 'required|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_numeric_space' => '{field} tidak sesuai',
                ],
            ],
            'ron_value' => [
                'label' => 'Nilai Reward',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'ron_condition_node_right' => [
                'label' => 'Syarat Kanan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'ron_condition_node_right' => [
                'label' => 'Syarat Kiri',
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
            $ron_id = $this->request->getPost('ron_id');
            $ron_title = $this->request->getPost('ron_title');
            $ron_value = $this->request->getPost('ron_value');
            $ron_condition_node_left = $this->request->getPost('ron_condition_node_left');
            $ron_condition_node_right = $this->request->getPost('ron_condition_node_right');
            $ron_image_filename = $this->request->getFile('ron_image_filename');
            $ron_old_image_filename = $this->request->getFile('ron_old_image_filename');

            $data = array();
            $data['ron_title'] = $ron_title;
            $data['ron_value'] = $ron_value;
            $data['ron_condition_node_left'] = $ron_condition_node_left;
            $data['ron_condition_node_right'] = $ron_condition_node_right;

            // if ($ron_image_filename->isValid() && file_exists($ron_image_filename)) {
            //     if ($ron_image_filename->getName() != '') {
            //         $fileName = 'reward' . date("YmdHis") . '.' . $ron_image_filename->getClientExtension();
            //         $ron_image_filename->move($this->pathUpload, $fileName);
            //         $data['ron_image_filename'] = $fileName;

            //         if ($ron_old_image_filename) {
            //             @unlink($this->pathUpload . $ron_old_image_filename);
            //         }
            //     }
            // }

            $res = $this->functionLib->updateData('bin_ron', 'ron_id', $ron_id, $data);

            if ($res !== false) {
                $this->createRespon(200, 'OK', array("message" => "Data Reward berhasil diubah"));
            } else {
                $this->createRespon(400, 'Bad Request', array("message" => "Data Reward gagal diubah"));
            }
        }
    }

    public function activeRon()
    {
        $data = $this->request->getPost('data');

        if (is_array($data)) {
            $success = $failed = 0;
            foreach ($data as $id) {
                $this->db->table('bin_ron')->where(['ron_id' => $id])->update(['ron_is_active' => '1']);

                if ($this->db->affectedRows() > 0) {
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

    public function notActiveRon()
    {
        $data = $this->request->getPost('data');

        if (is_array($data)) {
            $success = $failed = 0;
            foreach ($data as $id) {
                $this->db->table('bin_ron')->where(['ron_id' => $id])->update(['ron_is_active' => '0']);

                if ($this->db->affectedRows() > 0) {
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

    public function removeRon()
    {
        $data = $this->request->getPost('data');

        if (is_array($data)) {
            $success = $failed = 0;
            foreach ($data as $id) {
                $this->db->table('bin_ron')->where(['ron_id' => $id])->delete();

                if ($this->db->affectedRows() > 0) {
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

    public function getDataApprovalReward($type = 'bin')
    {
        $tableName = $type . '_reward_qualified';
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

        $this->createRespon(200, 'Data Reward', $data);
    }

    public function actApproveReward($type = 'bin')
    {
        $dataRewardQualified = $this->request->getPost('data');
        $datetime = date('Y-m-d H:i:s');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                if ($this->rewardModel->approveRewardQualified($type, $this->session->administrator_id, $rewardQualifiedId, $datetime)) {
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

    public function actRejectReward($type = 'bin')
    {
        $dataRewardQualified = $this->request->getPost('data');
        $datetime = date('Y-m-d H:i:s');
        if (is_array($dataRewardQualified)) {
            $success = $failed = 0;
            foreach ($dataRewardQualified as $rewardQualifiedId) {
                if ($this->rewardModel->rejectRewardQualified($type, $this->session->administrator_id, $rewardQualifiedId, $datetime)) {
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

    public function getDataHistoryApprovalReward($type = 'bin')
    {
        $tableName = $type . '_reward_qualified';
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

    public function getDataCalonPenerimaReward($type = 'bin')
    {
        $tableName = $type . '_reward_qualified';
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
}
