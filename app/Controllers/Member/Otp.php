<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Otp_model;

class Otp extends BaseController
{
    public function __construct()
    {
        $this->otp_model = new Otp_model();
    }

    public function generate()
    {
        $this->db->transBegin();
        try {
            $data = $this->otp_model->generateOTP(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Kode OTP berhasil dikirim.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function verify()
    {

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "otp" => [
                "label" => "Kode OTP",
                "rules" => "required|check_otp[" . session("member")["member_id"] . "]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "check_otp" => "{field} tidak sesuai.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $data = $this->otp_model->verify(session("member")["member_id"], $params->otp);

            session()->set('otp', TRUE);;

            $this->db->transCommit();
            $this->restLib->responseSuccess("Kode OTP berhasil diverifikasi.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
