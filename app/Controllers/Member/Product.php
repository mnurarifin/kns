<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Product_model;
use App\Models\Common_model;

class Product extends BaseController
{
    public function __construct()
    {
        $this->product_model = new Product_model();
        $this->common_model = new Common_model();
    }

    public function index()
    {
        $this->stock();
    }

    public function stock()
    {
        $data = [];

        $this->template->title("Stok");
        $this->template->breadcrumbs(["Produk", "Stok"]);
        $this->template->content("Member/productStockView", $data);
        $this->template->show("Template/Member/main");
    }

    public function exchange()
    {
        $data = [];

        $this->template->title("Penukaran Serial");
        $this->template->breadcrumbs(["Produk", "Penukaran Serial"]);
        $this->template->content("Member/productExchangeView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_stock()
    {
        $this->db->transBegin();
        try {
            $this->product_model->init($this->request);
            $data = $this->product_model->getStock(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data stok member.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_stock_log()
    {
        $this->db->transBegin();
        try {
            $this->product_model->init($this->request);
            $data = $this->product_model->getStockLog(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat stok member.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_exchange_log()
    {
        $this->db->transBegin();
        try {
            $this->product_model->init($this->request);
            $data = $this->product_model->getExchangeLog(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat penukaran serial.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_type_serial()
    {
        $this->db->transBegin();
        try {
            $this->product_model->init($this->request);
            $data = $this->product_model->getTypeSerial();

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data tipe serial.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function exchange_serial()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "serial_type" => [
                "label" => "Jenis Serial",
                "rules" => "required|in_list[activation,repeatorder]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "in_list" => "{field} format tidak sesuai.",
                ],
            ],
            "serial_type_id" => [
                "label" => "Tipe Serial",
                "rules" => "required|check_serial_type_id[{serial_type}]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "check_serial_type_id" => "{field} tidak ditemukan.",
                ],
            ],
            "serial_type_quantity" => [
                "label" => "Tipe Serial",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "detail.*.product_id" => [
                "label" => "Produk",
                "rules" => "required|is_not_unique[inv_product.product_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "detail.*.quantity" => [
                "label" => "Jumlah",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $arr_item = [];
            $arr_stock_log = [];
            $total_product_bv = 0;
            $serial_type = $this->product_model->getSerialType($params->serial_type, $params->serial_type_id);

            foreach ($params->detail as $detail) {
                $product = $this->product_model->getProduct($detail["product_id"]);
                $quantity = $detail["quantity"] * $params->serial_type_quantity;

                $arr_item[] = [
                    "member_serial_exchange_detail_product_id" => $product->product_id,
                    "member_serial_exchange_detail_product_code" => $product->product_code,
                    "member_serial_exchange_detail_product_name" => $product->product_name,
                    "member_serial_exchange_detail_unit_product_bv" => $product->product_bv,
                    "member_serial_exchange_detail_unit_product_price" => $product->product_member_price,
                    "member_serial_exchange_detail_unit_quantity" => $detail["quantity"],
                    "member_serial_exchange_detail_total_quantity" => $quantity,
                ];

                $stock_balance = $this->common_model->getOne("inv_member_product_stock", "member_product_stock_balance", [
                    "member_product_stock_member_id" => session("member")["member_id"],
                    "member_product_stock_product_id" => $product->product_id,
                ]);
                if ($stock_balance < $quantity) {
                    throw new \Exception("Stok {$product->product_name} tidak mencukupi.", 1);
                }

                $arr_stock_log[] = [
                    "member_product_stock_log_member_id" => session("member")["member_id"],
                    "member_product_stock_log_product_id" => $product->product_id,
                    "member_product_stock_log_type" => "out",
                    "member_product_stock_log_quantity" => $quantity,
                    "member_product_stock_log_unit_price" => $product->product_member_price,
                    "member_product_stock_log_balance" => $stock_balance - $quantity,
                    "member_product_stock_log_note" => "Penukaran {$params->serial_type_quantity} serial {$serial_type->serial_type_name}.",
                    "member_product_stock_log_datetime" => $this->datetime,
                ];

                $total_product_bv += $product->product_bv * $detail["quantity"];
            }

            if ($serial_type->serial_type_bv != $total_product_bv) {
                throw new \Exception("Jumlah BV tidak sesuai.", 1);
            }

            $arr_data = [
                "member_serial_exchange_member_id" => session("member")["member_id"],
                "member_serial_exchange_serial_type_ref" => $params->serial_type,
                "member_serial_exchange_serial_type_ref_id" => $serial_type->serial_type_id,
                "member_serial_exchange_serial_type_ref_name" => $serial_type->serial_type_name,
                "member_serial_exchange_serial_type_ref_bv" => $serial_type->serial_type_bv,
                "member_serial_exchange_quantity" => $params->serial_type_quantity,
                "member_serial_exchange_datetime" => $this->datetime,
            ];

            if ($params->serial_type == "activation") {
                $sql = "
                SELECT
                serial_id,
                serial_pin,
                serial_serial_type_id,
                serial_type_name
                FROM sys_serial
                JOIN sys_serial_type ON serial_type_id = serial_serial_type_id
                WHERE serial_serial_type_id = {$params->serial_type_id}
                AND serial_last_owner_status IS NULL
                LIMIT 0, {$params->serial_type_quantity}
                ";
                $arr_serial = $this->db->query($sql)->getResult("array");
                if (empty($arr_serial)) {
                    throw new \Exception("Stok serial tidak tersedia.", 1);
                }
            } else if ($params->serial_type == "repeatorder") {
                $sql = "
                SELECT
                serial_ro_id AS serial_id,
                serial_ro_pin AS serial_pin,
                serial_ro_serial_ro_type_id AS serial_serial_type_id,
                serial_ro_type_name AS serial_type_name
                FROM sys_serial_ro
                JOIN sys_serial_ro_type ON serial_ro_type_id = serial_ro_serial_ro_type_id
                WHERE serial_ro_serial_ro_type_id = {$params->serial_type_id}
                AND serial_ro_last_owner_status IS NULL
                LIMIT 0, {$params->serial_type_quantity}
                ";
                $arr_serial = $this->db->query($sql)->getResult("array");
                if (empty($arr_serial)) {
                    throw new \Exception("Stok serial tidak tersedia.", 1);
                }
            } else {
                throw new \Exception("Tipe serial tidak ditemukan.", 1);
            }
            foreach ($arr_serial as $key => $value) {
                $arr_serial[$key]["serial_type"] = $params->serial_type;
                $arr_serial[$key]["expired_datetime"] = date("Y-m-d H:i:s", strtotime($this->datetime . " +1 year"));
            }

            $exchange = $this->product_model->exchangeSerial($arr_data, $arr_item, $arr_serial, $arr_stock_log);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Penukaran serial berhasil diproses.", ["results" => $exchange]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }
}
