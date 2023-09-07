<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Reward_model;

class Reward extends BaseController
{
    public function __construct()
    {
        $this->reward_model = new Reward_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Daftar Poin Reward");
        $this->template->breadcrumbs(["Poin Reward", "Daftar Poin Reward"]);
        $this->template->content("Member/Reward/rewardView", $data);
        $this->template->show("Template/Member/main");
    }

    public function trip()
    {
        $data = [];

        $this->template->title("Daftar Poin Trip");
        $this->template->breadcrumbs(["Poin Trip", "Daftar Poin Trip"]);
        $this->template->content("Member/Reward/rewardTripView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log_trip()
    {
        $data = [];

        $this->template->title("Riwayat Poin Trip");
        $this->template->breadcrumbs(["Poin Trip", "Riwayat Poin Trip"]);
        $this->template->content("Member/Reward/rewardTripLogView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log_trip_qualified()
    {
        $data = [];

        $this->template->title("Riwayat Perolehan Poin Trip");
        $this->template->breadcrumbs(["Poin Trip", "Riwayat Perolehan Poin Trip"]);
        $this->template->content("Member/Reward/rewardTripLogQualifiedView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log()
    {
        $data = [];

        $this->template->title("Riwayat Poin");
        $this->template->breadcrumbs(["Poin Reward", "Riwayat Poin"]);
        $this->template->content("Member/Reward/rewardLogView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log_qualified()
    {
        $data = [];

        $this->template->title("Riwayat Perolehan Poin Reward");
        $this->template->breadcrumbs(["Poin Reward", "Riwayat Perolehan Poin Reward"]);
        $this->template->content("Member/Reward/rewardLogQualifiedView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_reward($member_id)
    {
        $this->db->transBegin();
        try {
            $this->reward_model->init($this->request);
            // $data["results"] = $this->reward_model->getReward(session("member")["member_id"]);
            // $summary = $this->reward_model->getSummaryPoint(session("member")["member_id"]);
            $data["results"] = $this->reward_model->getReward($member_id);
            $summary = $this->reward_model->getSummaryPoint($member_id);

            $data['point'] = (object)[
                "network_point" => (int) $summary->network_point,
                "potency_point" => (int) $summary->potency_point,
                "network_point_all" => (int) $summary->network_point_all,
                "potency_point_all" => (int) $summary->potency_point_all,
            ];

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data reward.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_reward_trip()
    {
        $this->db->transBegin();
        try {
            $this->reward_model->init($this->request);
            $data["results"] = $this->db->table("sys_reward_trip")->get()->getResult();

            foreach ($data['results'] as $key => $value) {
                if ($value->reward_image_filename && file_exists(UPLOAD_PATH . URL_IMG_REWARD . $value->reward_image_filename)) {
                    $data['results'][$key]->reward_image_filename = UPLOAD_URL . URL_IMG_REWARD . $value->reward_image_filename;
                } else {
                    $data['results'][$key]->reward_image_filename = BASEURL . '/app-assets/images/icon/cup.png';
                }

                $data['results'][$key]->reward_condition_point = (int) $value->reward_condition_point;
            }

            $data["point"] = $this->db->table("sys_reward_trip_point")->getWhere(["reward_point_member_id" => session("member")["member_id"]])->getRow();

            $data['point']->point_trip = (int) $data['point']->reward_point_acc - (int) $data['point']->reward_point_paid;

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data reward trip.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_reward_log($member_id)
    {
        $this->db->transBegin();
        try {
            $this->reward_model->init($this->request);
            // $data = $this->reward_model->getRewardLog(session("member")["member_id"]);
            $data = $this->reward_model->getRewardLog($member_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat perolehan reward.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_reward_log_qualified()
    {
        $this->db->transBegin();
        try {
            $this->reward_model->init($this->request);
            $data = $this->reward_model->getRewardLogQualified(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat perolehan reward.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_reward_trip_log($member_id)
    {
        $this->db->transBegin();
        try {
            $this->reward_model->init($this->request);
            // $data = $this->reward_model->getRewardTripLog(session("member")["member_id"]);
            $data = $this->reward_model->getRewardTripLog($member_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat perolehan reward trip.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_reward_trip_log_qualified()
    {
        $this->db->transBegin();
        try {
            $this->reward_model->init($this->request);
            $data = $this->reward_model->getRewardTripLogQualified(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat perolehan reward trip.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function claim()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "reward_qualified_id" => [
                "label" => "Reward",
                "rules" => "required|is_not_unique[sys_reward_qualified.reward_qualified_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $update = [
                "reward_qualified_claim" => "claimed",
                "reward_qualified_claim_datetime" => $this->datetime,
            ];
            $where = ["reward_qualified_id" => $params->reward_qualified_id];
            $this->reward_model->claimReward($update, $where);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Klaim reward berhasil diproses.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function claim_trip()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "reward_id" => [
                "label" => "Reward",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "name" => [
                "label" => "Nama Lengkap",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "mobilephone" => [
                "label" => "No Hp",
                "rules" => "required|numeric",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "numeric" => "{field} hanya boleh angka."
                ],
            ],
            "identity_no" => [
                "label" => "NIK",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ]
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $datetime = date("Y-m-d H:i:s");
            $detail_reward = $this->db->table("sys_reward_trip")->getWhere(["reward_id" => $params->reward_id])->getRow();
            $point_trip = $this->db->table("sys_reward_trip_point")->getWhere(["reward_point_member_id" => session("member")["member_id"]])->getRow();

            $arr_insert_qualified_reward_trip = [
                'reward_qualified_member_id' => session("member")["member_id"],
                'reward_qualified_reward_id' => $detail_reward->reward_id,
                'reward_qualified_reward_title' => $detail_reward->reward_title,
                'reward_qualified_reward_value' => $detail_reward->reward_value,
                'reward_qualified_condition_point' => $detail_reward->reward_condition_point,
                'reward_qualified_datetime' => $datetime,
                'reward_qualified_status_datetime' => $datetime,
                'reward_qualified_claim' => 'claimed',
                'reward_qualified_claim_datetime' => $datetime,
                'reward_qualified_name' => $params->name,
                'reward_qualified_mobilephone' => $params->mobilephone,
                'reward_qualified_identity_no' => $params->identity_no,
            ];

            $this->db->table("sys_reward_trip_qualified")->insert($arr_insert_qualified_reward_trip);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambahkan reward trip qualified", 1);
            }

            $this->db->table("sys_reward_trip_point")->where("reward_point_member_id", session('member')['member_id'])->update(["reward_point_paid" => $point_trip->reward_point_paid + $detail_reward->reward_condition_point]);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah poin reward trip", 1);
            }

            $arr_insert_log_point_reward_trip = [
                'reward_point_log_member_id' => session('member')['member_id'],
                'reward_point_log_type' => 'out',
                'reward_point_log_value' => $detail_reward->reward_condition_point,
                'reward_point_log_note' => "Claim Reward Trip " . $detail_reward->reward_title,
                'reward_point_log_datetime' => $datetime
            ];

            $this->db->table("sys_reward_trip_point_log")->insert($arr_insert_log_point_reward_trip);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambahkan log claim reward trip", 1);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Klaim reward trip berhasil diproses.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }
}
