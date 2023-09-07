<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Home_model;

class Product extends BaseController
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

        $this->template->title("Produk");
        $this->template->breadcrumbs(["Home", "Produk"]);
        $this->template->content("Pub/Product/productView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function detail()
    {
        $data = [];

        $this->template->title("Detail Produk");
        $this->template->breadcrumbs(["Home", "Detail Produk"]);
        $this->template->content("Pub/Product/productDetailView", $data);
        $this->template->show("Template/Pub/main");
    }

    public function get_list_product()
    {
        $this->home_model->init($this->request);
        $data = $this->home_model->getProductList();

        $this->restLib->responseSuccess("Data Produk.", $data);
    }

    public function get_detail_product($product_code)
    {
        $data = $this->db->table("inv_product")->getWhere(["product_code" => $product_code])->getRow();
        if ($data) {
            if ($data->product_image && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $data->product_image)) {
                $data->product_image = UPLOAD_URL . URL_IMG_PRODUCT . $data->product_image;
            } else {
                $data->product_image = UPLOAD_URL . URL_IMG_PRODUCT . "_default.png";
            }
        } else {
            $data = [];
        }

        $this->restLib->responseSuccess('detail produk', ["results" => $data]);
    }
}
