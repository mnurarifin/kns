<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Dashboard_model;
use App\Models\Member\Profile_model;

class Dashboard extends BaseController
{
    protected $dashboard_model;
    protected $profile_model;

    public function __construct()
    {
        $this->dashboard_model = new Dashboard_model();
        $this->profile_model = new Profile_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Dashboard");
        $this->template->breadcrumbs(["Dashboard", "Dashboard"]);
        $this->template->content("Member/Dashboard/dashboardView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_profile()
    {
        $this->db->transBegin();
        try {
            $data = $this->profile_model->get(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data profile.", ["results" => $data]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get()
    {
        $data = [];
        $data['stock_serial'] = $this->db->table('sys_serial_member_stock')->selectCount('serial_member_stock_serial_id')->getWhere(['serial_member_stock_is_expired' => 0, 'serial_member_stock_member_id' => $this->session->get('member')['member_id']])->getRow('serial_member_stock_serial_id');
        $data['ten_downline'] = $this->dashboard_model->get_ten_downline($this->session->get('member')['member_id']);
        if (count($data['ten_downline']) > 0) {
            foreach ($data['ten_downline'] as $key => $value) {
                $data['ten_downline'][$key]['member_image'] = $data['ten_downline'][$key]['member_image'] != '' ? UPLOAD_URL . URL_IMG_MEMBER . $data['ten_downline'][$key]['member_image'] : UPLOAD_URL . URL_IMG_MEMBER . '_default.png';
            }
        }

        $data['growing_per_week'] = $this->dashboard_model->get_member_growing_per_week($this->session->get('member')['member_id']);

        $this->restLib->responseSuccess("Data Dashboard.", ["results" => $data]);
    }

    public function commission()
    {
        $data = [];
        $data = $this->dashboard_model->get_commission($this->session->get('member')['member_id']);
        if ($data) {
            $data->total_commission = hlp_cor_formatNominal('', $data->sponsor + $data->welcome + $data->unilevel + $data->annually, 2);
            $data->sponsor = hlp_cor_formatNominal('', $data->sponsor, 2);
            $data->pasangan = hlp_cor_formatNominal('', $data->welcome, 2);
            $data->matching = hlp_cor_formatNominal('', $data->unilevel, 2);
            $data->sharing = hlp_cor_formatNominal('', $data->annually, 2);
        }

        $this->restLib->responseSuccess("Data Komisi.", ["results" => $data]);
    }

    public function get_rank()
    {
        $data = [];
        $network = $this->db->table("sys_network")->select("network_rank_id, network_rank_name, network_point_bv")->getWhere(["network_member_id" => session('member')['member_id']])->getRow();

        if ($network) {
            $rank = $this->db->table("sys_rank")->select("rank_name, rank_bv")->where("rank_bv > {$network->network_point_bv}")->limit(1)->get()->getRow();

            $color = "";
            switch ($network->network_rank_name) {
                case 'Bronze':
                    $color = "bronze";
                    break;

                case 'Silver':
                    $color = "secondary";
                    break;

                case 'Gold':
                    $color = "warning";
                    break;

                default:
                    $color = "primary";
                    break;
            }

            $data = [
                'cur_rank' => $network->network_rank_name,
                'cur_bv' => $network->network_point_bv,
                'next_rank' => $rank->rank_name,
                'next_bv' => $rank->rank_bv,
                'rank_color' => $color
            ];
        }

        $this->restLib->responseSuccess("Data rank.", ["results" => $data]);
    }

    public function get_data_certificate()
    {
        $total_bonus = $this->dashboard_model->get_total_bonus(session('member')['member_parent_member_id']);
        $top_reward = $this->dashboard_model->get_top_reward(session('member')['member_parent_member_id']);

        $data = [
            'total_bonus' => 'Rp ' . (setNumberFormat($total_bonus)),
            'rank' => (!empty($top_reward) ? $top_reward : 'Mitra'),
            'member_name' => session('member')['member_name'],
            'network_code' => session('member')['network_code'],
            'member_image' => session('member')['member_image']
        ];

        $this->restLib->responseSuccess("Data Sertifikat", ["results" => $data]);
    }
}
