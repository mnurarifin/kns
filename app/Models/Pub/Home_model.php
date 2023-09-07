<?php

namespace App\Models\Pub;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Home_model extends Model
{
    public $request;

    public function init($request, $datetime = NULL)
    {
        $this->request = $request;
        $this->datetime = $datetime ?? date("Y-m-d H:i:s");
    }

    public function getProductPackage()
    {
        $tableName = "inv_product_package";
        $columns = [
            "product_package_id",
            "product_package_name",
            "product_package_type",
            "product_package_point",
            "product_package_note",
            "product_package_image",
            "product_package_is_active",
        ];
        $joinTable = "";
        $whereCondition = " product_package_is_active = 1 AND product_package_type = 'activation'";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $detail = $this->db->table("inv_product_package_detail")
                ->join("inv_product", "product_id = product_package_detail_product_id")
                ->getWhere(["product_package_detail_product_package_id" => $value["product_package_id"]])->getResult();
            $arr_data["results"][$key]["detail"] = $detail;

            $price = 0;
            $weight = 0;
            foreach ($detail as $detail) {
                for ($i = 0; $i < $detail->product_package_detail_quantity; $i++) {
                    $price += $detail->product_package_detail_price;
                    $weight += $detail->product_weight;
                }
            }
            $arr_data["results"][$key]["product_package_price"] = $price;
            $arr_data["results"][$key]["product_package_weight"] = $weight;

            if ($arr_data["results"][$key]["product_package_image"]) {
                $arr_data["results"][$key]["product_package_image"] = UPLOAD_URL . URL_IMG_PRODUCT . $value["product_package_image"];
            } else {
                $arr_data["results"][$key]["product_package_image"] = UPLOAD_URL . URL_IMG_PRODUCT . "_default.png";
            }
        }

        return $arr_data;
    }

    public function getProductList()
    {
        $tableName = "inv_product";
        $columns = [
            "product_id",
            "product_name",
            "product_code",
            "product_price",
            "product_description",
            "product_image",
            "product_input_datetime",
            "product_is_active",
        ];
        $joinTable = "";
        $whereCondition = " product_is_active = 1";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($arr_data["results"][$key]["product_image"] && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $value['product_image'])) {
                $arr_data["results"][$key]["product_image"] = UPLOAD_URL . URL_IMG_PRODUCT . $value["product_image"];
            } else {
                $arr_data["results"][$key]["product_image"] = UPLOAD_URL . URL_IMG_PRODUCT . "_default.png";
            }
        }

        return $arr_data;
    }

    public function getContentList()
    {
        $tableName = "site_content";
        $columns = [
            "content_id",
            "content_content_category_id",
            "content_keyword",
            "content_slug",
            "content_title",
            "content_description",
            "content_body",
            "content_image",
            "content_input_datetime",
            "content_is_active",
        ];
        $joinTable = "";
        $whereCondition = " content_is_active = 1";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["content_input_datetime"] = convertDate($value['content_input_datetime']);
            if ($arr_data["results"][$key]["content_image"] && file_exists(UPLOAD_PATH . URL_IMG_CONTENT . $value['content_image'])) {
                $arr_data["results"][$key]["content_image"] = UPLOAD_URL . URL_IMG_CONTENT . $value["content_image"];
            } else {
                $arr_data["results"][$key]["content_image"] = UPLOAD_URL . URL_IMG_CONTENT . "_default.png";
            }
        }

        return $arr_data;
    }
}
