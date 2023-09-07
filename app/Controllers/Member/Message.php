<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Message_model;
use DateTime;
use stdClass;

class Message extends BaseController
{
    public function __construct()
    {
        $this->message_model = new Message_model();
    }

    public function index()
    {
        $this->inbox();
    }

    public function inbox()
    {
        $data = [];

        $this->template->title("Pesan Masuk");
        $this->template->breadcrumbs(["Pesan", "Pesan Masuk"]);
        $this->template->content("Member/Message/messageView", $data);
        $this->template->show("Template/Member/main");
    }

    public function send()
    {
        $data = [];

        $this->template->title("Buat Pesan");
        $this->template->breadcrumbs(["Pesan", "Buat Pesan"]);
        $this->template->content("Member/Message/messageSendView", $data);
        $this->template->show("Template/Member/main");
    }

    public function archive()
    {
        $data = [];

        $this->template->title("Arsip");
        $this->template->breadcrumbs(["Pesan", "Arsip"]);
        $this->template->content("Member/Message/messageArchiveView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log()
    {
        $data = [];

        $this->template->title("Pesan Terkirim");
        $this->template->breadcrumbs(["Pesan", "Pesan Terkirim"]);
        $this->template->content("Member/Message/messageLogView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_message($status = "", $type = "receiver")
    {
        try {
            $this->message_model->init($this->request);
            $data = $this->message_model->getMessage(session("member")["network_code"], $status, $type);

            $this->restLib->responseSuccess("Data pesan masuk.", $data);
        } catch (\Exception $e) {
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_detail()
    {
        $params = getRequestParamsData($this->request, "GET");

        $data = $this->message_model->getDetail($params->message_id);
        $data->message_datetime = convertDatetime($data->message_datetime);

        $this->restLib->responseSuccess("Data pesan.", ["results" => $data]);
    }

    public function archiveMessage()
    {
        $params = getRequestParamsData($this->request, "POST");

        try {
            $this->db->transBegin();

            $success = $failed = 0;
            foreach ($params->data as $message_id) {
                if ($this->message_model->archive($message_id)) {
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

            $this->db->transCommit();
            $this->restLib->responseSuccess($message, $dataArchive);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function unarchiveMessage()
    {
        $params = getRequestParamsData($this->request, "POST");

        try {
            $this->db->transBegin();

            $success = $failed = 0;
            foreach ($params->data as $message_id) {
                if ($this->message_model->unarchive($message_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataArchive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil buka arsip pesan!';
            if ($success == 0) {
                $message = 'Gagal buka arsip pesan';
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess($message, $dataArchive);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function remove()
    {
        $params = getRequestParamsData($this->request, "POST");

        try {
            $this->db->transBegin();

            $success = $failed = 0;
            foreach ($params->data as $message_id) {
                if ($this->message_model->remove($message_id)) {
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

            $this->db->transCommit();
            $this->restLib->responseSuccess($message, $dataArchive);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function send_msg()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "message_receiver_ref_code" => [
                "label" => "Penerima",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "message_content" => [
                "label" => "Pesan",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "message_receiver_ref_type" => [
                "label" => "Tipe Penerima",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);

        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        try {
            $this->db->transBegin();

            $arr_message_insert = [
                'message_type' => $params->message_type,
                'message_sender_ref_type' => 'member',
                'message_sender_ref_id' => session('member')['member_id'],
                'message_sender_ref_code' => session('member')['network_code'],
                'message_sender_ref_name' => session('member')['member_name'],
                'message_content' => $params->message_content,
                'message_datetime' => date("Y-m-d H:i:s")
            ];

            $check = new stdClass;
            if ($params->message_receiver_ref_type == 'member') {
                $check = $this->db->table('sys_network')->select('network_member_id, network_code, member_name')->join('sys_member', 'member_id = network_member_id')->getWhere(['network_code' => $params->message_receiver_ref_code])->getRow();

                if (!$check) {
                    $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", [
                        'message_receiver_ref_code' => 'Kode Mitra tidak terdaftar'
                    ]);
                }

                $arr_message_insert['message_receiver_ref_type'] = 'member';
                $arr_message_insert['message_receiver_ref_id'] = $check->network_member_id;
                $arr_message_insert['message_receiver_ref_code'] = $check->network_code;
                $arr_message_insert['message_receiver_ref_name'] = $check->member_name;
            } else {
                $arr_message_insert['message_receiver_ref_type'] = 'admin';
                $arr_message_insert['message_receiver_ref_id'] = 0;
                $arr_message_insert['message_receiver_ref_code'] = '';
                $arr_message_insert['message_receiver_ref_name'] = 'Sistem';
            }

            if (!$this->message_model->add($arr_message_insert)) {
                throw new \Exception("Gagal kirim pesan", 1);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Berhasil kirim pesan", $arr_message_insert);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function reply()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "message_receiver_ref_code" => [
                "label" => "Penerima",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "message_content" => [
                "label" => "Pesan",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);

        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        try {
            $this->db->transBegin();

            $arr_message_insert = [
                'message_type' => $params->message_type,
                'message_sender_ref_type' => $params->message_sender_ref_type,
                'message_sender_ref_id' => $params->message_sender_ref_id,
                'message_sender_ref_code' => $params->message_sender_ref_code,
                'message_sender_ref_name' => $params->message_sender_ref_name,
                'message_receiver_ref_type' => $params->message_receiver_ref_type,
                'message_receiver_ref_id' => $params->message_receiver_ref_id,
                'message_receiver_ref_name' => $params->message_receiver_ref_name,
                'message_content' => $params->message_content,
                'message_datetime' => date("Y-m-d H:i:s")
            ];

            if ($params->message_receiver_ref_type == 'admin') {
                $arr_message_insert['message_receiver_ref_code'] = '';
            } else {
                $arr_message_insert['message_receiver_ref_code'] = $params->message_receiver_ref_code;
            }

            if (!$this->message_model->add($arr_message_insert)) {
                throw new \Exception("Gagal kirim pesan", 1);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Berhasil kirim pesan", $arr_message_insert);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function getReceiver()
    {
        $search = $_GET['search'] ? $_GET['search'] : '';
        $sql = "
            SELECT 
                member_id,
                network_code
            FROM sys_member
            JOIN sys_network ON network_member_id = member_id
            WHERE network_code LIKE '%$search%'
            LIMIT 30
        ";
        $result = $this->db->query($sql);
        $list = array();
        $key = 0;
        foreach ($result->getResult('array') as $row) {
            $list[$key]['id'] = $row['member_id'];
            $list[$key]['text'] = $row['network_code'];
            $key++;
        }
        echo json_encode($list);
    }
}
