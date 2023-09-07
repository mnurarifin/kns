<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;
use App\Libraries\Notification;
use stdClass;
use Config\Services;

class Transaction_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
        $this->mlm_service = service('Mlm');
        $this->serial_service = service('Serial');

        $this->datetime = date('Y-m-d H:i:s');

        $this->notification_lib = new Notification();
        $this->functionLib = Services::FunctionLib();
        $this->stock_service = service('Stock');
    }

    public function set_datetime($value)
    {
        $this->datetime = $value;
    }

    public function execute($params, $type)
    {
        //ambil data buyer
        $obj_buyer_member = $this->common_model->getDetail('sys_member', 'member_id', $params->buyer_member_id);
        if (is_null($obj_buyer_member)) {
            $obj_buyer_member = new stdClass();
            $obj_buyer_member->member_id = 0;
            $obj_buyer_member->member_name = $params->member_name;
            $obj_buyer_member->member_address = $params->member_address;
            $obj_buyer_member->member_mobilephone = $params->member_mobilephone;
        }

        //insert transaksi
        $transaction_code = $params->transaction_code;
        $arr_data = [];
        if ($type == "warehouse") {
            $arr_data['warehouse_transaction_warehouse_id'] = $params->warehouse_id;
            $arr_data['warehouse_transaction_code'] = $transaction_code;
            $arr_data['warehouse_transaction_buyer_type'] = $params->buyer_type;
            $arr_data['warehouse_transaction_buyer_member_id'] = $obj_buyer_member->member_id;
            $arr_data['warehouse_transaction_buyer_name'] = $obj_buyer_member->member_name;
            $arr_data['warehouse_transaction_buyer_address'] = $obj_buyer_member->member_address;
            $arr_data['warehouse_transaction_buyer_mobilephone'] = $obj_buyer_member->member_mobilephone;
            $arr_data['warehouse_transaction_total_price'] = $params->total_price;
            $arr_data['warehouse_transaction_extra_discount_type'] = $params->extra_discount_type;
            $arr_data['warehouse_transaction_extra_discount_percent'] = $params->extra_discount_percent;
            $arr_data['warehouse_transaction_extra_discount_value'] = $params->extra_discount_value;
            $arr_data['warehouse_transaction_delivery_method'] = $params->delivery_method;
            $arr_data['warehouse_transaction_delivery_courier_code'] = $params->delivery_courier_code;
            $arr_data['warehouse_transaction_delivery_courier_service'] = $params->delivery_courier_service;
            $arr_data['warehouse_transaction_delivery_cost'] = $params->delivery_cost;
            $arr_data['warehouse_transaction_delivery_receiver_name'] = $params->delivery_receiver_name ?: $obj_buyer_member->member_name;
            $arr_data['warehouse_transaction_delivery_receiver_mobilephone'] = $params->delivery_receiver_mobilephone ?: $obj_buyer_member->member_mobilephone;
            $arr_data['warehouse_transaction_delivery_receiver_address'] = $params->delivery_receiver_address;
            $arr_data['warehouse_transaction_delivery_receiver_province_id'] = $params->delivery_receiver_province_id;
            $arr_data['warehouse_transaction_delivery_receiver_city_id'] = $params->delivery_receiver_city_id;
            $arr_data['warehouse_transaction_delivery_receiver_subdistrict_id'] = $params->delivery_receiver_subdistrict_id;
            $arr_data['warehouse_transaction_total_nett_price'] = $params->total_nett_price;
            $arr_data['warehouse_transaction_payment_ewallet'] = isset($params->payment_ewallet) ? $params->payment_ewallet : 0;
            $arr_data['warehouse_transaction_status'] = $params->status;
            $arr_data['warehouse_transaction_notes'] = $params->note;
            $arr_data['warehouse_transaction_type'] = $params->type;
            $arr_data['warehouse_transaction_datetime'] = $this->datetime;
            $arr_data['warehouse_transaction_payment_image'] = property_exists($params, "payment_image") ? $params->payment_image : "";
            $this->db->table('inv_warehouse_transaction')->insert($arr_data);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menyimpan data transaksi.", 1);
            }
        } else if ($type == "stockist" || $type == "master") {
            $arr_data['stockist_transaction_stockist_member_id'] = $params->stockist_member_id;
            $arr_data['stockist_transaction_code'] = $transaction_code;
            $arr_data['stockist_transaction_buyer_type'] = $params->buyer_type;
            $arr_data['stockist_transaction_buyer_member_id'] = $obj_buyer_member->member_id;
            $arr_data['stockist_transaction_buyer_name'] = $obj_buyer_member->member_name;
            $arr_data['stockist_transaction_buyer_address'] = $obj_buyer_member->member_address;
            $arr_data['stockist_transaction_buyer_mobilephone'] = $obj_buyer_member->member_mobilephone;
            $arr_data['stockist_transaction_total_price'] = $params->total_price;
            $arr_data['stockist_transaction_extra_discount_type'] = $params->extra_discount_type;
            $arr_data['stockist_transaction_extra_discount_percent'] = $params->extra_discount_percent;
            $arr_data['stockist_transaction_extra_discount_value'] = $params->extra_discount_value;
            $arr_data['stockist_transaction_delivery_method'] = $params->delivery_method;
            $arr_data['stockist_transaction_delivery_courier_code'] = $params->delivery_courier_code;
            $arr_data['stockist_transaction_delivery_courier_service'] = $params->delivery_courier_service;
            $arr_data['stockist_transaction_delivery_cost'] = $params->delivery_cost;
            $arr_data['stockist_transaction_delivery_receiver_name'] = $params->delivery_receiver_name ?: $obj_buyer_member->member_name;
            $arr_data['stockist_transaction_delivery_receiver_mobilephone'] = $params->delivery_receiver_mobilephone ?: $obj_buyer_member->member_mobilephone;
            $arr_data['stockist_transaction_delivery_receiver_address'] = $params->delivery_receiver_address;
            $arr_data['stockist_transaction_delivery_receiver_province_id'] = $params->delivery_receiver_province_id;
            $arr_data['stockist_transaction_delivery_receiver_city_id'] = $params->delivery_receiver_city_id;
            $arr_data['stockist_transaction_delivery_receiver_subdistrict_id'] = $params->delivery_receiver_subdistrict_id;
            $arr_data['stockist_transaction_total_nett_price'] = $params->total_nett_price;
            $arr_data['stockist_transaction_status'] = $params->status;
            $arr_data['stockist_transaction_notes'] = $params->note;
            $arr_data['stockist_transaction_type'] = $params->type;
            $arr_data['stockist_transaction_datetime'] = $this->datetime;
            $arr_data['stockist_transaction_payment_image'] = property_exists($params, "payment_image") ? $params->payment_image : "";
            $this->db->table('inv_stockist_transaction')->insert($arr_data);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menyimpan data transaksi.", 1);
            }
        }
        $transaction_id = $this->db->insertID();

        $arr_product_wa = "";
        if (!empty($params->arr_product)) {
            foreach ($params->arr_product as $product) {
                $product = (object) $product;

                $obj_product = $this->common_model->getDetail('inv_product', 'product_id', $product->product_id);
                if (empty($obj_product)) {
                    throw new \Exception("Gagal mendapatkan data produk.", 1);
                }

                $arr_data = [];
                if ($type == "warehouse") {
                    $arr_data['warehouse_transaction_detail_warehouse_transaction_id'] = $transaction_id;
                    $arr_data['warehouse_transaction_detail_product_id'] = $obj_product->product_id;
                    $arr_data['warehouse_transaction_detail_product_code'] = $obj_product->product_code;
                    $arr_data['warehouse_transaction_detail_product_name'] = $obj_product->product_name;
                    $arr_data['warehouse_transaction_detail_unit_price'] = $product->unit_price;
                    $arr_data['warehouse_transaction_detail_discount_type'] = $product->discount_type;
                    $arr_data['warehouse_transaction_detail_discount_percent'] = $product->discount_percent;
                    $arr_data['warehouse_transaction_detail_discount_value'] = $product->discount_value;
                    $arr_data['warehouse_transaction_detail_unit_nett_price'] = $product->unit_nett_price;
                    $arr_data['warehouse_transaction_detail_quantity'] = $product->quantity;
                    $this->db->table('inv_warehouse_transaction_detail')->insert($arr_data);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal menyimpan data detail transaksi.", 1);
                    }

                    $this->stock_service->substractStock((object)[
                        "quantity" => $product->quantity,
                        "warehouse_id" => $params->warehouse_id,
                        "product_id" => $obj_product->product_id,
                        "unit_price" => $product->unit_price,
                        "note" => $params->note,
                        "datetime" => $this->datetime,
                    ]);
                } else if ($type == "stockist" || $type == "master") {
                    $arr_data['stockist_transaction_detail_stockist_transaction_id'] = $transaction_id;
                    $arr_data['stockist_transaction_detail_product_id'] = $obj_product->product_id;
                    $arr_data['stockist_transaction_detail_product_code'] = $obj_product->product_code;
                    $arr_data['stockist_transaction_detail_product_name'] = $obj_product->product_name;
                    $arr_data['stockist_transaction_detail_unit_price'] = $product->unit_price;
                    $arr_data['stockist_transaction_detail_discount_type'] = $product->discount_type;
                    $arr_data['stockist_transaction_detail_discount_percent'] = $product->discount_percent;
                    $arr_data['stockist_transaction_detail_discount_value'] = $product->discount_value;
                    $arr_data['stockist_transaction_detail_unit_nett_price'] = $product->unit_nett_price;
                    $arr_data['stockist_transaction_detail_quantity'] = $product->quantity;
                    $this->db->table('inv_stockist_transaction_detail')->insert($arr_data);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal menyimpan data detail transaksi.", 1);
                    }

                    $this->stock_service->substractStockStockist((object)[
                        "quantity" => $product->quantity,
                        "stockist_member_id" => $params->stockist_member_id,
                        "product_id" => $obj_product->product_id,
                        "unit_price" => $product->unit_price,
                        "note" => $params->note,
                        "datetime" => $this->datetime,
                    ]);
                }

                $arr_product_wa .= "
*{$product->quantity} x {$obj_product->product_name}*";
            }
        }

        if (WA_NOTIFICATION_IS_ACTIVE) {
            $client_name = COMPANY_NAME;
            $client_wa_cs_number = WA_CS_NUMBER;
            $buyer_name = $this->mlm_service->get_member_name_by_member_id($params->buyer_member_id);
            $buyer_mobilephone = $this->mlm_service->get_member_mobilephone_by_member_id($params->buyer_member_id);

            $content = "*Transaksi Pembelian Berhasil*
Hai {$buyer_name},
Transaksi pembelian anda dengan kode *{$transaction_code}* berhasil diproses.

Detail produk sebagai berikut:
{$arr_product_wa}
=====================================================
*Total Rp {$this->functionLib->format_nominal('',$params->total_price, 2)}*

Segera lakukan pembayaran dan lakukan konfirmasi untuk proses pengiriman produk.

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}
            ";

            $this->notification_lib->send_waone($content, phoneNumberFilter($buyer_mobilephone));
        }

        return [
            "transaction_id" => $transaction_id,
            "transaction_code" => $transaction_code,
            "transaction_type" => $type,
        ];
    }

    public function generate_transaction_code($seller, $date = "", $type = "activation")
    {
        if ($date == '') {
            $date = date("Y-m-d");
        }

        $type = $type == "activation" ? "AC" : "RO";

        //TRX/SM/20220901/0001
        $prefix = "TRX/";
        $new_sort = '0001';
        if ($seller == "warehouse") {
            $sql = "
                SELECT IFNULL(LPAD(MAX(CAST(RIGHT(warehouse_transaction_code, 4) AS SIGNED) + 1), 4, '0'), '0001') AS new_sort
                FROM inv_warehouse_transaction
                WHERE DATE(warehouse_transaction_datetime) = '" . $date . "'
            ";
            $prefix .= "WH";
        } else if ($seller == "stockist" || $seller == "master") {
            $sql = "
                SELECT IFNULL(LPAD(MAX(CAST(RIGHT(stockist_transaction_code, 4) AS SIGNED) + 1), 4, '0'), '0001') AS new_sort
                FROM inv_stockist_transaction
                WHERE DATE(stockist_transaction_datetime) = '" . $date . "'
            ";
            if ($seller == "stockist") {
                $prefix .= "ST";
            } else if ($seller == "master") {
                $prefix .= "MT";
            }
        } else {
            throw new \Exception("Gagal generate kode transaksi.", 1);
        }
        $row = $this->db->query($sql)->getRow();

        if (!is_null($row)) {
            $new_sort = $row->new_sort;
        }
        $new_code = $prefix . '/' . str_replace('-', '', $date) . '/' . $new_sort;

        return $new_code;
    }

    public function paid($type, $update, $where)
    {
        if ($type == "warehouse") {
            $table = "inv_warehouse_transaction";
            $select = "warehouse_transaction_detail_product_name as product_name, warehouse_transaction_detail_quantity as quantity";
            $where_detail = ["warehouse_transaction_detail_warehouse_transaction_id" => $where['warehouse_transaction_id']];
        } else if ($type == "stockist" || $type == "master") {
            $table = "inv_stockist_transaction";
            $select = "stockist_transaction_detail_product_name as product_name, stockist_transaction_detail_quantity as quantity";
            $where_detail = ["stockist_transaction_detail_stockist_transaction_id" => $where['stockist_transaction_id']];
        } else {
            throw new \Exception("Tipe tidak diketahui.", 1);
        }
        $this->db->table($table)->update($update, $where);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah status transaksi.", 1);
        }

        return $this->db->table($table . '_detail')->select($select)->getWhere($where_detail)->getResult();
    }

    public function expired($type, $update, $where, $transaction_id)
    {
        if ($type == "warehouse") {
            $table = "inv_warehouse_transaction";
            $transaction = $this->db->table("inv_warehouse_transaction")->getWhere(["warehouse_transaction_id" => $transaction_id])->getRow();

            if ($transaction->warehouse_transaction_buyer_type == "stockist" || $transaction->warehouse_transaction_buyer_type == "master") {
                $check_trx = $this->db->table("inv_warehouse_transaction")->select("warehouse_transaction_code, warehouse_transaction_buyer_member_id, warehouse_transaction_payment_ewallet")->getWhere(["warehouse_transaction_id" => $transaction_id])->getRow();

                if ($check_trx->warehouse_transaction_payment_ewallet > 0) {
                    $ewallet = $this->db->table("sys_ewallet")->getWhere(["ewallet_member_id" => $check_trx->warehouse_transaction_buyer_member_id])->getRow();

                    $arr_log = [
                        'ewallet_log_member_id' => $check_trx->warehouse_transaction_buyer_member_id,
                        'ewallet_log_value' => $check_trx->warehouse_transaction_payment_ewallet,
                        'ewallet_log_type' => 'in',
                        'ewallet_log_note' => 'Pembatalan transaksi dengan kode transaksi ' . $check_trx->warehouse_transaction_code,
                        'ewallet_log_datetime' => date("Y-m-d H:i:s")
                    ];

                    $this->db->table("sys_ewallet_log")->insert($arr_log);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal insert log ewallet.", 1);
                    }

                    $arr_update_ewallet = [
                        'ewallet_acc' => (int)$ewallet->ewallet_acc + (int)$check_trx->warehouse_transaction_payment_ewallet
                    ];

                    $this->db->table("sys_ewallet")->where('ewallet_member_id', $check_trx->warehouse_transaction_buyer_member_id)->update($arr_update_ewallet);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update ewallet.", 1);
                    }
                }
            } else {
            }
        } else if ($type == "stockist" || $type == "master") {
            $table = "inv_stockist_transaction";
        } else {
            throw new \Exception("Tipe tidak diketahui.", 1);
        }
        $this->db->table($table)->update($update, $where);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah status transaksi.", 1);
        }
    }

    public function addStatus($params)
    {
        $this->db->table("inv_warehouse_transaction_status")->insert($params);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menyimpan data log transaksi.", 1);
        }
    }

    public function addStatusStockist($params)
    {
        $this->db->table("inv_stockist_transaction_status")->insert($params);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menyimpan data log transaksi.", 1);
        }
    }

    public function getTransactionTotal($member_registration_id)
    {
        # code...
    }
}
