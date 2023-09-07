<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Home_model;

class Home extends BaseController
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
        $this->template->content("Pub/Home/homeView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function get_product_package()
    {
        $this->db->transBegin();
        try {
            $this->home_model->init($this->request);
            $data = $this->home_model->getProductPackage();

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data Paket Produk.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_product()
    {
        $data = $this->db->table("inv_product")->select("product_code, product_name, product_image, product_price")->getWhere(["product_is_active" => 1])->getResult();

        foreach ($data as $key => $value) {
            if ($value->product_image != '' && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $value->product_image)) {
                $data[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . $value->product_image;
            } else {
                $data[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . '_default.png';
            }
        }

        $this->restLib->responseSuccess("Data Produk.", ['results' => $data]);
    }

    public function get_content()
    {
        $data = $this->db->table("site_content")->select("content_title, content_slug, content_description, content_body, content_image, content_input_datetime")->getWhere(["content_is_active" => 1])->getResult();

        foreach ($data as $key => $value) {
            $data[$key]->content_input_datetime = convertDate($value->content_input_datetime);
            if ($value->content_image != '' && file_exists(UPLOAD_PATH . URL_IMG_CONTENT . $value->content_image)) {
                $data[$key]->content_image = UPLOAD_URL . URL_IMG_CONTENT . $value->content_image;
            } else {
                $data[$key]->content_image = UPLOAD_URL . URL_IMG_CONTENT . '_default.png';
            }
        }

        $this->restLib->responseSuccess("Data Content.", ['results' => $data]);
    }

    public function get_count()
    {
        $data = [
            'stockist' => $this->db->table("inv_stockist")->selectCount("stockist_member_id")->getWhere(["stockist_is_active" => 1])->getRow("stockist_member_id"),
            'member' => $this->db->table("sys_member")->selectCount("member_id")->getWhere(["member_status" => 1])->getRow("member_id"),
            'new_member' => $this->db->table("sys_member")->selectCount("member_id")->where("DATE(member_join_datetime) = DATE(current_date)")->where("member_status", 1)->get()->getRow("member_id"),
        ];

        $this->restLib->responseSuccess("Data Count.", ['results' => $data]);
    }

    public function get_banner()
    {
        $data = [];
        $data = $this->db->table("site_banner")->select("banner_image, banner_image_mobile, banner_title, banner_description, banner_order_by")->orderBy("banner_order_by", "ASC")->limit(3)->getWhere(["banner_is_active" => 1])->getResult();

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $data[$key]->banner_dot = "<button>{$value->banner_order_by}</button>";
                if ($value->banner_image != '' && file_exists(UPLOAD_PATH . URL_IMG_BANNER . $value->banner_image)) {
                    $data[$key]->banner_image = UPLOAD_URL . URL_IMG_BANNER . $value->banner_image;
                } else {
                    $data[$key]->banner_image = UPLOAD_URL . URL_IMG_BANNER . '_default.jpg';
                }

                if ($value->banner_image_mobile != '' && file_exists(UPLOAD_PATH . URL_IMG_BANNER . $value->banner_image_mobile)) {
                    $data[$key]->banner_image_mobile = UPLOAD_URL . URL_IMG_BANNER . $value->banner_image_mobile;
                } else {
                    $data[$key]->banner_image_mobile = UPLOAD_URL . URL_IMG_BANNER . '_default-mobile.jpg';
                }
            }
        } else {
            $data[0] = [
                'banner_image' => UPLOAD_URL . URL_IMG_BANNER . '_default.jpg',
                'banner_image_mobile' => UPLOAD_URL . URL_IMG_BANNER . '_default-mobile.jpg',
                'banner_title' => 'Kimstella Brightening Soap Plus DNA Salmon',
                'banner_description' => 'Manfaat: Membantu membersihkan, Mencerahkan, Melembabkan, Menghaluskan, Menjaga Kulit Tetap Sehat dan Menutrisi Kulit.',
                'banner_order_by' => 1,
                'banner_dot' => '<button>1</button>'
            ];
        }

        $this->restLib->responseSuccess("Data Banner.", ['results' => $data]);
    }
}
