<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Home_model;

class Testimony extends BaseController
{
    public function __construct()
    {
        $this->home_model = new Home_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Beranda");
        $this->template->breadcrumbs(["Beranda", "Beranda"]);
        $this->template->content("Pub/Testimony/testyView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function get_list_testimony()
    {
        $this->db->transBegin();
        try {
            $data = $this->db->table("sys_member")
                ->select("member_name, city_name, subdistrict_name, member_image, testimony_content")
                ->join('site_testimony', 'testimony_member_id = member_id')
                ->join('ref_city', 'member_city_id = city_id')
                ->join('ref_subdistrict', 'member_subdistrict_id = subdistrict_id')
                ->where('testimony_is_publish = 1')
                ->orderBy('rand()')
                ->limit(4)
                ->get()
                ->getResult();

            foreach ($data as $key => $value) {
                if ($value->member_image != '' && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $value->member_image)) {
                    $data[$key]->member_image = UPLOAD_URL . URL_IMG_MEMBER . $value->member_image;
                } else {
                    $data[$key]->member_image = UPLOAD_URL . URL_IMG_MEMBER . '_default.png';
                }
            }
            $this->db->transCommit();
            $this->restLib->responseSuccess("Data Testimoni.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
