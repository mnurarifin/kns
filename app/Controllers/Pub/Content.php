<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Home_model;

class Content extends BaseController
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

        $this->template->title("Artikel");
        $this->template->breadcrumbs(["Home", "Artikel"]);
        $this->template->content("Pub/Content/contentView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function detail()
    {
        $data = [];

        $this->template->title("Detail Artikel");
        $this->template->breadcrumbs(["Home", "Detail Artikel"]);
        $this->template->content("Pub/Content/contentDetailView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function get_list_content()
    {
        $this->home_model->init($this->request);
        $data = $this->home_model->getContentList();

        $this->restLib->responseSuccess("Data Artikel.", $data);
    }

    public function get_detail_content($content_slug)
    {
        $data = $this->db->table("site_content")->getWhere(["content_slug" => $content_slug])->getRow();
        if ($data) {
            $data->content_input_datetime = convertDate($data->content_input_datetime);
            if ($data->content_image && file_exists(UPLOAD_PATH . URL_IMG_CONTENT . $data->content_image)) {
                $data->content_image = UPLOAD_URL . URL_IMG_CONTENT . $data->content_image;
            } else {
                $data->content_image = UPLOAD_URL . URL_IMG_CONTENT . "_default.png";
            }
        } else {
            $data = [];
        }

        $this->restLib->responseSuccess('detail artikel', ["results" => $data]);
    }

    public function get_new_content()
    {
        $data = $this->db->table("site_content")->orderBy("content_input_datetime", "desc")->limit(3)->getWhere(["content_is_active" => 1])->getResult();

        foreach ($data as $key => $value) {
            $data[$key]->content_input_datetime = convertDate($value->content_input_datetime);

            if ($data[$key]->content_image && file_exists(UPLOAD_PATH . URL_IMG_CONTENT . $value->content_image)) {
                $value->content_image = UPLOAD_URL . URL_IMG_CONTENT . $value->content_image;
            } else {
                $value->content_image = UPLOAD_URL . URL_IMG_CONTENT . "_default.png";
            }
        }

        $this->restLib->responseSuccess('Data new artikel', ["results" => $data]);
    }

    public function get_list_category()
    {
        $data = $this->db->table("site_content_category")->getWhere(["content_category_is_active" => 1])->getResult();

        $this->restLib->responseSuccess('Data kategori', ["results" => $data]);
    }
}
