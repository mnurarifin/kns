<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Transaction_model;
use App\Models\Common_model;
use App\Libraries\Notification;

class Transaction extends BaseController
{
    public function __construct()
    {
        $this->transaction_model = new Transaction_model();
        $this->common_model = new Common_model();
        $this->notification_lib = new Notification();
        $this->transaction_service = service("Transaction");
        $this->serial_service = service("Serial");
        $this->mlm_service = service("Mlm");
        $this->reward_service = service('Reward');
        $this->stock_service = service('Stock');
        $this->payment_service = service('Payment');
        $this->ewallet_service = service('Ewallet');
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Belanja Ulang");
        $this->template->breadcrumbs(["Belanja", "Belanja Ulang"]);
        $this->template->content("Member/Transaction/transactionView", $data);
        $this->template->show("Template/Member/main");
    }

    public function transaction_success()
    {
        $data = [];

        $this->template->title("Transaksi Berhasil");
        $this->template->breadcrumbs(["Transaksi", "Transaksi Berhasil"]);
        $this->template->content("Member/Transaction/transactionSuccessView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log()
    {
        $data = [];

        $this->template->title("Riwayat Belanja");
        $this->template->breadcrumbs(["Belanja", "Riwayat Belanja"]);
        $this->template->content("Member/Transaction/transactionLogView", $data);
        $this->template->show("Template/Member/main");
    }

    public function payment()
    {
        $data = [];

        $this->template->title("Konfirmasi Pembayaran");
        $this->template->breadcrumbs(["Transaksi", "Konfirmasi Pembayaran"]);
        $this->template->content("Member/Transaction/transactionPaymentView", $data);
        $this->template->show("Template/Member/main");
    }

    public function receivement_warehouse()
    {
        $data = [];

        $this->template->title("Perusahaan");
        $this->template->breadcrumbs(["Penerimaan Paket", "Perusahaan"]);
        $this->template->content("Member/Transaction/transactionReceivementWarehouseView", $data);
        $this->template->show("Template/Member/main");
    }

    public function receivement_stockist()
    {
        $data = [];

        $this->template->title("Master/Stokis");
        $this->template->breadcrumbs(["Penerimaan Paket", "Master/Stokis"]);
        $this->template->content("Member/Transaction/transactionReceivementStockistView", $data);
        $this->template->show("Template/Member/main");
    }

    public function point()
    {
        $data = [];

        $this->template->title("Saldo Belanja RO");
        $this->template->breadcrumbs(["Transaksi", "Saldo Belanja RO"]);
        $this->template->content("Member/Transaction/transactionPointView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_transaction()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getTransaction(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_transaction_history()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getTransactionHistory(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_transaction_seller_history()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getTransactionSellerHistory(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_transaction_payment()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getTransactionPayment(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_transaction_warehouse_delivered()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getTransactionWarehouseDelivered(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data penerimaan transaksi.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_transaction_stockist_delivered()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getTransactionStockistDelivered(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data penerimaan transaksi.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_point_log()
    {
        $this->db->transBegin();
        try {
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getPointLog(session("member")["member_id"]);
            $data["point"] = $this->transaction_model->getSummaryPoint(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat saldo belanja.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_plan_activity()
    {
        $this->db->transBegin();
        try {
            $this->transaction_model->init($this->request);
            $data = $this->transaction_model->getPlanActivity(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat saldo belanja.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function add_transaction()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "transaction_delivery_method" => [
                "label" => "Metode Distribusi",
                "rules" => "required|in_list[pickup,courier]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "in_list" => "Format {field} tidak sesuai.",
                ],
            ],
            "detail.*.product_id" => [
                "label" => "Produk",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        if ($params->transaction_delivery_method == "courier") {
            $this->validation->setRules([
                "transaction_courier_code" => [
                    "label" => "Kurir",
                    "rules" => "required",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                    ],
                ],
                "transaction_courier_service" => [
                    "label" => "Layanan",
                    "rules" => "required",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                    ],
                ],
            ])->run((array) $params);
            if ($this->validation->getErrors()) {
                $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
            }
        }

        if ($params->type == "stockist" || $params->type == "master") {
            $this->validation->setRules([
                "stockist_member_id" => [
                    "label" => "Master / Stokis",
                    "rules" => "required|is_not_unique[inv_stockist.stockist_member_id]",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                        "is_not_unique" => "{field} tidak ditemukan.",
                    ],
                ],
            ])->run((array) $params);
            if ($this->validation->getErrors()) {
                $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
            }
        }

        $this->db->transBegin();
        try {
            if ($this->transaction_model->check_clone(session("member")["member_id"])) {
                throw new \Exception("Silakan kloning dari akun utama.", 1);
            }

            $total_price = 0;
            $arr_product = [];
            foreach ($params->detail as $key => $detail) {
                $detail = (object) $detail;
                $product = $this->db->table("inv_product")->getWhere(["product_id" => $detail->product_id])->getRow();

                $warehouse_id = 1;
                if ($params->type == "warehouse") {
                    $this->stock_service->checkStock($warehouse_id, $product->product_id, $detail->product_qty);
                } else {
                    $this->stock_service->checkStockStockist($params->stockist_member_id, $product->product_id, $detail->product_qty);
                }

                $price_nett = TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE == "percent" ?
                    $product->product_price - $product->product_price * TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT / 100 :
                    $product->product_price - TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE;
                $arr_product[] = [
                    "product_id" => $product->product_id,
                    "unit_price" => $product->product_price,
                    "discount_type" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE,
                    "discount_percent" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT,
                    "discount_value" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE,
                    "quantity" => $detail->product_qty,
                    "unit_nett_price" => $price_nett,
                    "total_nett_price" => $price_nett * $detail->product_qty,
                ];
                $total_price += $price_nett * $detail->product_qty;
                // $total_price += $product->product_price * $detail->product_qty;
            }

            // if (!$this->transaction_model->validateMinPrice($total_price)) {
            //     $this->restLib->responseFailed("Total transaksi harus di atas minimum transaksi.", "process", []);
            // }

            if ($params->address_type == 'self') {
                $address = $this->db->table("sys_member")->select("member_name, member_mobilephone, member_address, member_province_id, member_city_id, member_subdistrict_id")->getWhere(["member_id" => session('member')['member_id']])->getRow();

                $params->name = $address->member_name;
                $params->mobilephone = $address->member_mobilephone;
                $params->address = $address->member_address;
                $params->province_id = $address->member_province_id;
                $params->city_id = $address->member_city_id;
                $params->subdistrict_id = $address->member_subdistrict_id;
            }

            $total_nett = $total_price + ($params->transaction_delivery_method == "courier" ? (int)$params->transaction_delivery_cost : 0);
            $transaction_code = $this->transaction_service->generate_transaction_code($params->type);

            $notes_ = "";
            if ($params->transaction_delivery_method == "courier") {
                $notes_ = "Ekspedisi " . $params->transaction_courier_code . " - " . $params->transaction_courier_service;
            }

            $transaction_data = (object) [
                "transaction_code" => $transaction_code,
                "buyer_member_id" => session("member")["member_id"],
                "buyer_type" => "member",
                "total_price" => $total_price,
                "extra_discount_type" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE,
                "extra_discount_percent" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT,
                "extra_discount_value" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE,
                "delivery_method" => $params->transaction_delivery_method,
                "delivery_courier_code" => $params->transaction_delivery_method == "courier" ? $params->transaction_courier_code : "",
                "delivery_courier_service" => $params->transaction_delivery_method == "courier" ? $params->transaction_courier_service : "",
                "delivery_cost" => $params->transaction_delivery_method == "courier" ? $params->transaction_delivery_cost : 0,
                "delivery_receiver_name" => $params->transaction_delivery_method == "courier" ? $params->name : NULL,
                "delivery_receiver_mobilephone" => $params->transaction_delivery_method == "courier" ? $params->mobilephone  : NULL,
                "delivery_receiver_address" => $params->transaction_delivery_method == "courier" ? $params->address  : NULL,
                "delivery_receiver_province_id" => $params->transaction_delivery_method == "courier" ? $params->province_id  : NULL,
                "delivery_receiver_city_id" => $params->transaction_delivery_method == "courier" ? $params->city_id  : NULL,
                "delivery_receiver_subdistrict_id" => $params->transaction_delivery_method == "courier" ? $params->subdistrict_id  : NULL,
                "total_nett_price" => $total_nett,
                "status" => "pending",
                "note" => "Transaksi Kode : " . $transaction_code . " " . $notes_,
                "warehouse_id" => $warehouse_id,
                "arr_product" => $arr_product,
                "type" => "repeatorder",
                "stockist_member_id" => $params->stockist_member_id,
            ];
            $data = $this->transaction_service->execute($transaction_data, $params->type);

            if ($params->type == "warehouse") {
                $transaction_status_data = [
                    "warehouse_transaction_status_warehouse_transaction_id" => $data["transaction_id"],
                    "warehouse_transaction_status_value" => $transaction_data->status,
                    "warehouse_transaction_status_datetime" => $this->datetime,
                    "warehouse_transaction_status_ref_type" => "member",
                    "warehouse_transaction_status_ref_id" => session("member")["member_id"],
                ];
                $this->transaction_service->addStatus($transaction_status_data);
            } else {
                $transaction_status_data = [
                    "stockist_transaction_status_stockist_transaction_id" => $data["transaction_id"],
                    "stockist_transaction_status_value" => $transaction_data->status,
                    "stockist_transaction_status_datetime" => $this->datetime,
                    "stockist_transaction_status_ref_type" => "member",
                    "stockist_transaction_status_ref_id" => session("member")["member_id"],
                ];
                $this->transaction_service->addStatusStockist($transaction_status_data);
            }

            $member_email = $this->common_model->getOne("sys_member", "member_email", ["member_id" => session("member")["member_id"]]);
            $payment = $this->payment_service->execute(
                $params->type == "warehouse" ? "warehouse" : "stockist",
                $data["transaction_id"],
                $member_email,
                "Pembayaran transaksi kode : {$transaction_code}",
                $total_nett
            );

            $this->db->transCommit();

            if (WA_NOTIFICATION_IS_ACTIVE) {
                $arr_product_wa = "";
                foreach ($params->detail as $key => $value) {
                    $value = (object) $value;
                    $product = $this->db->table("inv_product")->getWhere(["product_id" => $value->product_id])->getRow();
                    $arr_product_wa .= "
*{$product->product_name} - {$product->product_price} x {$value->product_qty}*";
                }
                $fmt = numfmt_create('id_ID', \NumberFormatter::CURRENCY);
                $price =  numfmt_format_currency($fmt, $total_nett, "IDR");
                $method = $params->transaction_delivery_method == "courier" ? 'Kirim' : 'Ambil';
                $member = $this->db->table("sys_member")->select("member_name, member_mobilephone")->getWhere(["member_id" => session("member")["member_id"]])->getRow();
                $client_name = COMPANY_NAME;
                $client_url = URL_PRODUCTION;
                $content = "Belanja Ulang Berhasil!
Bapak/Ibu {$member->member_name} Terima kasih telah melakukan pembelanjaan ulang produk di {$client_name}.

Rincian belanja Anda:
{$arr_product_wa}

Total yang harus dibayarkan senilai *{$price}*
Metode pengiriman {$method}

Segera lakukan pembayaran sesuai dengan rincian di atas.

Terima kasih atas kepercayaan anda bersama kami.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";

                $this->notification_lib->send_waone($content, $member->member_mobilephone);
            }

            $this->restLib->responseSuccess("Tambah transaksi berhasil diproses.", ["results" => array_merge((array)$data, (array)$payment)]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function receive_warehouse()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "warehouse_transaction_id" => [
                "label" => "ID",
                "rules" => "required|is_not_unique[inv_warehouse_transaction.warehouse_transaction_id]|check_status_receive",
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
                "warehouse_transaction_status" => "complete",
            ];
            $where = ["warehouse_transaction_id" => $params->warehouse_transaction_id];
            $transaction = $this->transaction_model->editWarehouse($update, $where);

            $transaction_status_data = [
                "warehouse_transaction_status_warehouse_transaction_id" => $params->warehouse_transaction_id,
                "warehouse_transaction_status_value" => $update["warehouse_transaction_status"],
                "warehouse_transaction_status_datetime" => $this->datetime,
                "warehouse_transaction_status_ref_type" => "member",
                "warehouse_transaction_status_ref_id" => session("member")["member_id"],
            ];
            $this->transaction_service->addStatus($transaction_status_data);

            $update = [
                'warehouse_transaction_status_datetime' => $this->datetime,
            ];
            $where = ["warehouse_transaction_id" => $params->warehouse_transaction_id];
            $transaction = $this->transaction_model->editWarehouse($update, $where);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Stok berhasil diterima.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function receive_stockist()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "stockist_transaction_id" => [
                "label" => "ID",
                "rules" => "required|is_not_unique[inv_stockist_transaction.stockist_transaction_id]|check_status_receive",
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
                "stockist_transaction_status" => "complete",
            ];
            $where = ["stockist_transaction_id" => $params->stockist_transaction_id];
            $transaction = $this->transaction_model->editStockist($update, $where);
            $update = [
                "stockist_transaction_status_datetime" => $this->datetime,
            ];
            $where = ["stockist_transaction_id" => $params->stockist_transaction_id];
            $transaction = $this->transaction_model->editStockist($update, $where);

            $transaction_status_data = [
                "stockist_transaction_status_stockist_transaction_id" => $params->stockist_transaction_id,
                "stockist_transaction_status_value" => $update["stockist_transaction_status"],
                "stockist_transaction_status_datetime" => $this->datetime,
                "stockist_transaction_status_ref_type" => "member",
                "stockist_transaction_status_ref_id" => session("member")["member_id"],
            ];
            $this->transaction_service->addStatus($transaction_status_data);

            $this->ewallet_service->add_ewallet($transaction->stockist_transaction_stockist_member_id, $transaction->stockist_transaction_total_nett_price);
            $this->ewallet_service->add_ewallet_log(
                $transaction->stockist_transaction_stockist_member_id,
                $transaction->stockist_transaction_total_nett_price,
                "in",
                "Penerimaan transaksi kode : " . $transaction->stockist_transaction_code,
                $this->datetime
            );

            $this->db->transCommit();
            $this->restLib->responseSuccess("Stok berhasil diterima.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function upload_transaction_payment()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "warehouse_transaction_id" => [
                "label" => "ID",
                "rules" => "required|is_not_unique[inv_warehouse_transaction.warehouse_transaction_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $paramsPaymentImage = getRequestParamsFile($this->request, "warehouse_transaction_payment_image");

        $this->validation->setRules([
            "warehouse_transaction_payment_image" => [
                "label" => "Bukti Transaksi",
                "rules" => "uploaded[warehouse_transaction_payment_image]|max_size[warehouse_transaction_payment_image,2048]|ext_in[warehouse_transaction_payment_image,png,jpg,jpeg]|is_image[warehouse_transaction_payment_image]",
                "errors" => [
                    "uploaded" => "Tidak boleh kosong.",
                    "max_size" => "Ukuran maksimal 5MB.",
                    "ext_in" => "Format tidak sesuai (ext_in).",
                    "is_image" => "Format tidak sesuai (image).",
                ],
            ],
        ])->run((array) $paramsPaymentImage);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $filename = "payment_{$paramsPaymentImage->getRandomName()}";
            $paramsPaymentImage->move(UPLOAD_PATH . URL_IMG_PAYMENT, $filename);
            if (!$paramsPaymentImage->hasMoved()) {
                throw new \Exception("File tidak bisa disimpan.", 1);
            }
            if (!resizeImage(UPLOAD_PATH . URL_IMG_PAYMENT, UPLOAD_PATH . URL_IMG_PAYMENT . $filename, $filename, 500, 500)) {
                throw new \Exception("File tidak bisa diresize.", 1);
            }

            $update = [
                "warehouse_transaction_payment_image" => $filename,
                "warehouse_transaction_status" => "paid",
            ];
            $where = ["warehouse_transaction_id" => $params->warehouse_transaction_id];
            $this->transaction_model->edit($update, $where);

            $transaction_status_data = [
                "warehouse_transaction_status_warehouse_transaction_id" => $params->warehouse_transaction_id,
                "warehouse_transaction_status_value" => $update["warehouse_transaction_status"],
                "warehouse_transaction_status_datetime" => $this->datetime,
                "warehouse_transaction_status_ref_type" => "member",
                "warehouse_transaction_status_ref_id" => session("member")["member_id"],
            ];
            $this->transaction_service->addStatus($transaction_status_data);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Konfirmasi Pembayaran diproses.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function get_member_address()
    {
        try {
            $data["results"] = $this->transaction_model->getMemberAddress(session("member")["member_id"]);

            $this->restLib->responseSuccess("Data alamat mitra.", $data);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_free_product()
    {
        try {
            $data["results"] = $this->transaction_model->getFreeProduct(session("member")["member_id"]);

            $this->restLib->responseSuccess("Data free produk.", $data);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function categoryTransaction()
    {
        foreach (TRANSACTION_STATUS as $key => $value) {
            $data[] = [
                'title' => ucfirst($value),
                'value' => $key
            ];
        }

        $this->restLib->responseSuccess("Data status transaksi.", ['results' => $data]);
    }

    public function getDetailTransaction()
    {
        $params = getRequestParamsData($this->request, "GET");

        $data = $this->transaction_model->getDetailTransaction($params->id);

        $this->restLib->responseSuccess("Data status transaksi.", ['results' => $data]);
    }
}
