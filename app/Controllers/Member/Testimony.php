<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Testimony_model;

class Testimony extends BaseController
{
    public function __construct()
    {
        $this->testimony_model = new Testimony_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Testimoni");
        $this->template->breadcrumbs(["Testimoni"]);
        $this->template->content("Member/Testimony/testimonyView", $data);
        $this->template->show("Template/Member/main");
    }

    public function add()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "testimony_content" => [
                "label" => "Konten",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $arr_insert_testy = [
                'testimony_member_id' => session("member")["member_id"],
                'testimony_network_code' => session("member")["network_code"],
                'testimony_content' => $params->testimony_content,
                'testimony_date' => date("Y-m-d")
            ];

            if (!$this->testimony_model->add($arr_insert_testy)) {
                throw new \Exception("Gagal Tambah testimoni", 1);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Testimoni Berhasil ditambahkan.", []);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
