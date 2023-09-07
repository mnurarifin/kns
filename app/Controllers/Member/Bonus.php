<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Bonus_model;

class Bonus extends BaseController
{
    public function __construct()
    {
        $this->bonus_model = new Bonus_model();
    }

    public function index()
    {
        $this->summary();
    }

    public function summary()
    {
        $data = [];

        $this->template->title("Komisi");
        $this->template->breadcrumbs(["Komisi", "Summary Komisi"]);
        $this->template->content("Member/Bonus/bonusSummaryView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log()
    {
        $data = [];

        $this->template->title("Riwayat Komisi");
        $this->template->breadcrumbs(["Komisi", "Riwayat Komisi"]);
        $this->template->content("Member/Bonus/bonusLogView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log_transfer()
    {
        $data = [];

        $this->template->title("Riwayat Transfer Komisi");
        $this->template->breadcrumbs(["Komisi", "Riwayat Transfer Komisi"]);
        $this->template->content("Member/Bonus/bonusTransferView", $data);
        $this->template->show("Template/Member/main");
    }

    public function statement()
    {
        $data = [];

        $this->template->title("Statement Komisi");
        $this->template->breadcrumbs(["Komisi", "Statement Komisi"]);
        $this->template->content("Member/Bonus/bonusStatementView", $data);
        $this->template->show("Template/Member/main");
    }

    public function statement_print()
    {
        $data = [];

        $this->template->title("Print Statement Komisi");
        $this->template->breadcrumbs(["Komisi", "Print Statement Komisi"]);
        $this->template->content("Member/Bonus/bonusStatementPrint", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_member_detail()
    {
        $data = $this->bonus_model->getMemberDetail($this->session->get("member")["member_id"]);
        $data->member_join_datetime_formatted = convertDatetime($data->member_join_datetime, "id");
        if ($data->sponsor_member_account_username == "") {
            $data->sponsor_member_account_username = "-";
        }
        if ($data->upline_member_account_username == "") {
            $data->upline_member_account_username = "-";
        }
        $this->restLib->responseSuccess("Data detail mitra.", ["results" => $data]);
    }

    public function get_member_children()
    {
        $data = $this->bonus_model->getMemberChildren($this->session->get("member")["member_id"]);
        $this->restLib->responseSuccess("Data detail mitra.", ["results" => $data]);
    }

    public function get_detail_bonus()
    {
        $this->bonus_model->init($this->request);
        $data = $this->bonus_model->getDetailBonus(session("member")["member_id"]);

        $this->restLib->responseSuccess("Data bonus mitra.", $data);
    }

    public function get_bonus()
    {
        $this->db->transBegin();
        try {
            $this->bonus_model->init($this->request);
            $data = $this->bonus_model->getBonus($this->session->get("member")["member_id"]);
            $return = [
                "summary" => [
                    "bonus" => $data->summary->bonus_sponsor_acc + $data->summary->bonus_gen_node_acc + $data->summary->bonus_power_leg_acc + $data->summary->bonus_matching_leg_acc,
                    "reward" => $data->summary->bonus_cash_reward_acc,
                    "acc" => $data->summary->bonus_sponsor_acc + $data->summary->bonus_gen_node_acc + $data->summary->bonus_power_leg_acc + $data->summary->bonus_matching_leg_acc + $data->summary->bonus_cash_reward_acc,
                    "paid" => $data->summary->bonus_sponsor_paid + $data->summary->bonus_gen_node_paid + $data->summary->bonus_power_leg_paid + $data->summary->bonus_matching_leg_paid + $data->summary->bonus_cash_reward_paid,
                    "balance" => $data->summary->bonus_sponsor_balance + $data->summary->bonus_gen_node_balance + $data->summary->bonus_power_leg_balance + $data->summary->bonus_matching_leg_balance + $data->summary->bonus_cash_reward_balance,
                    "limit" => $data->summary->bonus_limit,
                ],
                "detail" => [
                    "Sponsor" => $data->summary->bonus_sponsor_acc,
                    "Generasi" => $data->summary->bonus_gen_node_acc,
                    "Power Leg" => $data->summary->bonus_power_leg_acc,
                    "Matching Leg" => $data->summary->bonus_matching_leg_acc,
                    "Cash Reward" => $data->summary->bonus_cash_reward_acc,
                ],
                "potency" => [
                    "Sponsor" => [
                        "value" => $data->summary->bonus_sponsor_today,
                        "detail" => "sponsor",
                    ],
                    "Generasi" => [
                        "value" => $data->summary->bonus_gen_node_today,
                        "detail" => "gen-node",
                    ],
                    "Power Leg" => [
                        "value" => $data->summary->bonus_power_leg_today,
                        "detail" => "power-leg",
                    ],
                    "Matching Leg" => [
                        "value" => $data->summary->bonus_matching_leg_today,
                        "detail" => "matching-leg",
                    ],
                    // "Cash Reward" => [
                    //     "value" => $data->summary->bonus_cash_reward_today,
                    //     "detail" => "cash-reward",
                    // ],
                ],
                "children" => $data->children,
            ];

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data bonus mitra.", ["results" => $return]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_bonus_log($member_id, $month, $year)
    {
        $this->db->transBegin();
        try {
            $this->bonus_model->init($this->request);
            // $data = $this->bonus_model->getBonusLog(session("member")["member_id"]);
            $data = $this->bonus_model->getBonusLog($member_id, $month, $year);
            $data['bonus_filter'] = $this->db->query("SELECT sum(bonus_log_sponsor) + sum(bonus_log_gen_node) + sum(bonus_log_power_leg) + sum(bonus_log_matching_leg) + sum(bonus_log_cash_reward) AS total
            from sys_bonus_log where bonus_log_member_id = {$member_id} AND month(bonus_log_date) ='{$month}' AND year(bonus_log_date) ='{$year}' group by bonus_log_member_id")->getRow('total') ?? 0;
            $data['bonus_total'] = $this->db->query("SELECT sum(bonus_log_sponsor) + sum(bonus_log_gen_node) + sum(bonus_log_power_leg) + sum(bonus_log_matching_leg) + sum(bonus_log_cash_reward) AS total
            from sys_bonus_log where bonus_log_member_id = {$member_id} group by bonus_log_member_id")->getRow('total') ?? 0;
            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat bonus mitra.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_bonus_transfer($id = FALSE)
    {
        $this->db->transBegin();
        try {
            $this->bonus_model->init($this->request);
            $data = $this->bonus_model->getBonusTransfer(session("member")["member_id"], $id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data transfer bonus mitra.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_member_bonus_detail_sponsor()
    {
        $this->db->transBegin();
        try {
            $data = $this->bonus_model->getBonusSponsor(session("member")["member_id"], $this->date);
            $data["title"] = "Detail Komisi Sponsor Hari Ini";

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data detail bonus mitra.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_member_bonus_detail_gen_node()
    {
        $this->db->transBegin();
        try {
            $data = $this->bonus_model->getBonusGenNode(session("member")["member_id"], $this->date);
            $data["title"] = "Detail Komisi Generasi Hari Ini";

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data detail bonus mitra.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_member_bonus_detail_power_leg()
    {
        $this->db->transBegin();
        try {
            $data = $this->bonus_model->getBonusPowerLeg(session("member")["member_id"], $this->date);
            $data["title"] = "Detail Komisi Power Leg Hari Ini";

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data detail bonus mitra.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_member_bonus_detail_matching_leg()
    {
        $this->db->transBegin();
        try {
            $data = $this->bonus_model->getBonusMatchingLeg(session("member")["member_id"], $this->date);
            $data["title"] = "Detail Komisi Matching Leg Hari Ini";

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data detail bonus mitra.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
