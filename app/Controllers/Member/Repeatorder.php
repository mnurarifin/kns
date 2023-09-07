<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Repeatorder_model;
use App\Models\Common_model;
use App\Libraries\Notification;

class Repeatorder extends BaseController
{
    public function __construct()
    {
        $this->repeatorder_model = new Repeatorder_model();
        $this->common_model = new Common_model();
        $this->notification_lib = new Notification();
        $this->repeatorder_service = service("Repeatorder");
        $this->serial_service = service("Serial");
        $this->mlm_service = service("Mlm");
        $this->ewallet_service = service('Ewallet');
        $this->reward_service = service('Reward');
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Repeat Order");
        $this->template->breadcrumbs(["Repeat Order", "Repeat Order"]);
        $this->template->content("Member/Repeatorder/repeatorderView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log()
    {
        $data = [];

        $this->template->title("Riwayat Repeat Order");
        $this->template->breadcrumbs(["Repeat Order", "Riwayat Repeat Order"]);
        $this->template->content("Member/Repeatorder/repeatorderLogView", $data);
        $this->template->show("Template/Member/main");
    }

    public function group()
    {
        $data = [];

        $this->template->title("Omzet");
        $this->template->breadcrumbs(["Repeat Order", "Omzet"]);
        $this->template->content("Member/Repeatorder/repeatorderGroupView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_repeat_order()
    {
        $this->db->transBegin();
        try {
            $this->repeatorder_model->init($this->request);
            $data = $this->repeatorder_model->getRepeatOrder(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_repeat_order_log()
    {
        $this->db->transBegin();
        try {
            $this->repeatorder_model->init($this->request);
            $data = $this->repeatorder_model->getRepeatOrderLog(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_repeat_order_group()
    {
        $this->db->transBegin();
        try {
            $this->repeatorder_model->init($this->request);
            $data = $this->repeatorder_model->getRepeatOrderGroup(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data omzet group.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_repeat_order_personal()
    {
        $this->db->transBegin();
        try {
            $this->repeatorder_model->init($this->request);
            $data = $this->repeatorder_model->getRepeatOrderPersonal(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data omzet personal.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function add_repeat_order()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "bv" => [
                "label" => "BV",
                "rules" => "required|numeric",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "numeric" => "Format {field} tidak sesuai.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $this->repeatorder_service->insert_ro(session("member")["member_id"], "Repeat Order {$params->bv} BV", $this->datetime, $params->bv);

            $this->db->transCommit();

            if (WA_NOTIFICATION_IS_ACTIVE) {
                $member = $this->db->table("sys_member")->select("member_name, member_mobilephone")->getWhere(["member_id" => session("member")["member_id"]])->getRow();
                $client_name = COMPANY_NAME;
                $project_name = PROJECT_NAME;
                $client_wa_cs_number = WA_CS_NUMBER;
                $content = "*REPEAT ORDER BERHASIL*

Hai {$member->member_name},
Input Repeat Order berhasil diproses.

Jumlah Poin BV yang Anda input adalah {$params->bv} BV

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}

*-- {$project_name} --*";

                $this->notification_lib->send_waone($content, $member->member_mobilephone);
            }
            $this->restLib->responseSuccess("Input repeat order berhasil diproses.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function get_balance()
    {
        $this->db->transBegin();
        try {
            $this->repeatorder_model->init($this->request);
            $data["results"] = $this->repeatorder_model->getSummaryPoint(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data paket RO.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
