<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\MessageModel;
use Config\Services;

class Message extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->messageModel = new MessageModel();
    }

    public function getDataMessage($type = 'inbox')
    {
        switch ($type) {
            case 'inbox':
                $whereCondition = "message_type = 'pesan' AND message_status <= 1 AND message_receiver_ref_type = 'admin'";
                break;
            case 'send':
                $whereCondition = "message_type = 'pesan' AND message_status <= 1 AND message_sender_ref_type = 'admin'";
                break;
            case 'broadcast':
                $whereCondition = "message_type = 'broadcast' AND message_status <= 1";
                break;
            case 'draft':
                $whereCondition = "message_type = 'draf' AND message_status <= 1 AND message_sender_ref_type = 'admin'";
                break;
            case 'archive':
                $whereCondition = "message_status = 2 AND (message_sender_ref_type = 'admin' OR message_receiver_ref_type = 'admin')";
                break;
            case 'member':
                $whereCondition = "message_status < 3 AND message_sender_ref_type = 'mitra' AND message_receiver_ref_type = 'mitra'";
                break;
            case 'serial':
                $whereCondition = "message_type = 'serial' AND message_status < 3";
                break;
            case 'notification':
                $whereCondition = "(message_type = 'pesan' OR message_type = 'serial') AND message_status < 1 AND message_receiver_ref_type = 'admin'";
                break;
            case 'delete':
                // type ini tidak akan ditampilkan di admin/member
                $whereCondition = "message_status = 3";
                break;
            default:
                $whereCondition = "message_type = 'pesan' AND message_status <= 1 AND message_receiver_ref_type = 'admin'";
                break;
        }

        $tableName = 'site_message';
        $columns = array(
            'message_id',
            'message_type',
            'message_sender_ref_type',
            'message_sender_ref_id',
            'message_sender_ref_code',
            'message_sender_ref_name',
            'message_receiver_ref_type',
            'message_receiver_ref_id',
            'message_receiver_ref_code',
            'message_receiver_ref_name',
            'message_content',
            'message_status',
            'message_datetime',
        );
        $joinTable = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {

                if ($row['message_content']) {
                    $message_content = strlen($row['message_content'])  > 30 ? $this->functionLib->cutText($row['message_content'], 30) : $row['message_content'];
                    $data['results'][$key]['message_content'] = Strip_tags($message_content);
                }

                if ($row['message_datetime']) {
                    $data['results'][$key]['message_datetime'] = $this->functionLib->convertDatetime($row['message_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Pesan ' . $type, $data);
    }

    public function getDataMessageCount()
    {
        $where_type = [
            "WHERE message_type = 'pesan' AND message_status = 0 AND message_receiver_ref_type = 'admin'",
            "WHERE message_type = 'serial' AND message_status = 0",
            "where message_type = 'draf' AND message_status <= 1 AND message_sender_ref_type = 'admin'",
            "WHERE message_status = 2 AND (message_sender_ref_type = 'admin' OR message_receiver_ref_type = 'admin')",
            "WHERE message_type = 'pesan' AND message_sender_ref_type = 'admin'",
            "WHERE message_status < 3 AND message_sender_ref_type = 'mitra' AND message_receiver_ref_type = 'mitra'",
            "WHERE (message_type = 'pesan' AND message_status = 0 AND message_receiver_ref_type = 'admin' OR message_type = 'serial' AND message_status = 0)"
        ];

        foreach ($where_type as $key => $value) {
            $sql = "
                SELECT 
                    COUNT(*) AS message_count 
                FROM site_message 
                $value
            ";
            $result[] = $this->db->query($sql)->getRow('message_count');
        }

        $message = "Berhasil mengambil data";

        $data['results'] = [
            'inbox' => $result[0],
            'serial' => $result[1],
            'draft' => $result[2],
            'arsip' => $result[3],
            'terkirim' => $result[4],
            'member' => $result[5],
            'notification' => $result[6]
        ];

        $this->createRespon(200, $message, $data);
    }

    public function getDataMember()
    {
        $sql = "
            SELECT 
                member_id,
                member_name,
                IFNULL(member_ref_network_code,'') AS member_network_code
            FROM sys_member 
            LEFT JOIN bin_member_ref on member_id = member_ref_member_id
        ";

        $message = "Berhasil mengambil data";
        $data['results'] = $this->db->query($sql)->getResult();

        // $this->createRespon(200, $message, $data);
        echo json_encode($data);
    }

    public function getPenerima()
    {
        $search = $_GET['search'] ? $_GET['search'] : '';
        $sql = "
            SELECT 
                member_id,
                network_code as member_ref_network_code
            FROM sys_member
            JOIN sys_network on network_member_id = member_id
            WHERE network_code LIKE '%$search%'
            LIMIT 30
        ";
        $result = $this->db->query($sql);
        $list = array();
        $key = 0;
        foreach ($result->getResult('array') as $row) {
            $list[$key]['id'] = $row['member_id'];
            $list[$key]['text'] = $row['member_ref_network_code'];
            $key++;
        }
        echo json_encode($list);
    }

    public function getDataById($message_id)
    {
        if ($message_id) {
            $message = "Berhasil mengambil Data";
            $data['results'] = $this->messageModel
                ->where('message_id', $message_id)
                ->first();
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Data tidak ditemukan!');
        }
    }

    public function sendDraft()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'message_receive_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penerima tidak boleh kosong',
                ],
            ],
            'message_content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pesan tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $data = array();
            $date = date('Y-m-d H:i:s');
            $message_receive_id = $this->request->getPost('message_receive_id');
            $message_content = $this->request->getPost('message_content');
            $message_type = $this->request->getPost('message_type');
            if (count($message_receive_id) > 0) {
                foreach ($message_receive_id as $key => $value) {
                    $sql = "
                        UPDATE site_message
                        SET message_status = 3
                        WHERE message_type = 'draf'
                        AND message_receive_id = '$value'
                    ";
                    $this->db->query($sql);
                    $member_info = $this->getInfoMember($value);

                    $data['message_type'] = $message_type;
                    $data['message_sender_type'] = 'admin';
                    $data['message_sender_id'] = session('admin')['admin_id'];
                    $data['message_sender_network_code'] = '';
                    $data['message_sender_name'] = session('admin')['admin_name'];
                    $data['message_receive_type'] = 'mitra';
                    $data['message_receive_id'] = $member_info[0]->member_id;
                    $data['message_receive_network_code'] = $member_info[0]->member_network_code;
                    $data['message_receive_name'] = $member_info[0]->member_name;
                    $data['message_content'] = $message_content;
                    $data['message_datetime'] = $date;

                    $this->db->table("site_message")->insert($data);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal kirim pesan", 1);
                    }
                }
            }
            $message = 'Berhasil Menambah Data';

            $this->createRespon(200, $message, $data);
        }
    }

    public function addMessage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'message_receive_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penerima tidak boleh kosong',
                ],
            ],
            'message_content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pesan tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $data = array();
            $date = date('Y-m-d H:i:s');
            $message_receive_id = $this->request->getPost('message_receive_id');
            $message_content = $this->request->getPost('message_content');
            $message_type = $this->request->getPost('message_type');

            if ($message_type == 'pesan' || $message_type == 'draf') {
                if (count($message_receive_id) > 0) {
                    foreach ($message_receive_id as $key => $value) {
                        $member_info = $this->getInfoMember($value);

                        $data['message_type'] = $message_type;
                        $data['message_sender_ref_type'] = 'admin';
                        $data['message_sender_ref_id'] = session('admin')['admin_id'];
                        $data['message_sender_ref_code'] = '';
                        $data['message_sender_ref_name'] = session('admin')['admin_name'];
                        $data['message_receiver_ref_type'] = 'mitra';
                        $data['message_receiver_ref_id'] = $member_info[0]->member_id;
                        $data['message_receiver_ref_code'] = $member_info[0]->member_network_code;
                        $data['message_receiver_ref_name'] = $member_info[0]->member_name;
                        $data['message_content'] = $message_content;
                        $data['message_datetime'] = $date;

                        $this->db->table("site_message")->insert($data);

                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal kirim pesan", 1);
                        }
                    }
                }
            }

            $message = 'Berhasil Menambah Data';

            $this->createRespon(200, $message, $data);
        }
    }

    public function archiveMessage()
    {
        $data = $this->request->getPost('data');
        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $message_id) {
                $archive = $this->messageModel->archiveMessage($message_id);
                if ($archive) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataArchive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengarsipkan data pesan!';
            if ($success == 0) {
                $message = 'Gagal mengarsipkan data pesan';
            }
            $this->createRespon(200, $message, $dataArchive);
        } else {
            $this->createRespon(400, 'Anda belum memilih pesan yang diarsipkan!');
        }
    }

    public function deleteMessage()
    {
        $data = $this->request->getPost('data');
        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $message_id) {
                $archive = $this->messageModel->deleteMessage($message_id);
                if ($archive) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataArchive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menghapus data pesan!';
            if ($success == 0) {
                $message = 'Gagal menghapus data pesan';
            }
            $this->createRespon(200, $message, $dataArchive);
        } else {
            $this->createRespon(400, 'Anda belum memilih pesan yang akan dihapus!');
        }
    }

    public function readMessage()
    {
        $data = $this->request->getPost('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $message_id) {
                $read = $this->messageModel->readMessage($message_id);
                if ($read) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $data = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil membaca data pesan!';
            if ($success == 0) {
                $message = 'Gagal membaca data pesan';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih pesan!');
        }
    }

    function getInfoMember($id = NULL)
    {
        if ($id) {
            $sql = "
                SELECT 
                    member_id,
                    member_name,
                    IFNULL(network_code,'') AS member_network_code
                FROM sys_member 
                LEFT JOIN sys_network on member_id = network_member_id
                WHERE member_id = $id
            ";
        } else {
            $sql = "
                SELECT 
                    member_id,
                    member_name,
                    IFNULL(network_code,'') AS member_network_code
                FROM sys_member 
                LEFT JOIN sys_network on member_id = network_member_id
            ";
        }


        return $this->db->query($sql)->getResult();
    }
}
