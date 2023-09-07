<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\TransactionModel;
use App\Models\Admin\ProductModel;
use App\Models\Admin\StockModel;
use App\Models\Member\Stockist_model;
use Config\Services;

class Transaction extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->transactionModel = new TransactionModel();
        $this->db = \Config\Database::connect();
        $this->productModel = new ProductModel();
        $this->stockModel = new StockModel();
        $this->stockist_model = new stockist_model();
        $this->repeatorder_service = service("Repeatorder");
        $this->stock_service = service("Stock");
        $this->transaction_service = service("Transaction");
    }

    public function getDataTransaction($type = 'stockist', $transaction_status = '')
    {
        switch ($transaction_status) {
            case 'delivery':
                $whereCondition = "warehouse_transaction_status = 'delivered'";
                break;
            case 'approval':
                $whereCondition = "warehouse_transaction_status = 'pending'";
                break;
            case 'approved':
                $whereCondition = "warehouse_transaction_status = 'paid'";
                break;
            default:
                $whereCondition = "1";
                break;
        }

        $whereCondition .= " AND warehouse_transaction_buyer_type = '{$type}'";

        $tableName = 'inv_warehouse_transaction';
        $columns = array(
            "warehouse_transaction_id",
            "warehouse_transaction_warehouse_id",
            "warehouse_transaction_code",
            "warehouse_transaction_buyer_type",
            "warehouse_transaction_buyer_member_id",
            "warehouse_transaction_buyer_name",
            "warehouse_transaction_buyer_address",
            "warehouse_transaction_buyer_mobilephone",
            "warehouse_transaction_total_price",
            "warehouse_transaction_extra_discount_type",
            "warehouse_transaction_extra_discount_percent",
            "warehouse_transaction_extra_discount_value",
            "warehouse_transaction_delivery_method",
            "warehouse_transaction_delivery_courier_code",
            "warehouse_transaction_delivery_courier_service",
            "warehouse_transaction_delivery_cost",
            "warehouse_transaction_delivery_receiver_name",
            "warehouse_transaction_delivery_receiver_mobilephone",
            "warehouse_transaction_delivery_receiver_province_id",
            "warehouse_transaction_delivery_receiver_city_id",
            "warehouse_transaction_delivery_receiver_subdistrict_id",
            "warehouse_transaction_delivery_receiver_address",
            "warehouse_transaction_delivery_awb",
            "warehouse_transaction_delivery_status",
            "warehouse_transaction_payment_image",
            "warehouse_transaction_total_nett_price",
            "warehouse_transaction_payment_cash",
            "warehouse_transaction_payment_ewallet",
            "warehouse_transaction_notes",
            "warehouse_transaction_status",
            "warehouse_transaction_status_datetime",
            "warehouse_transaction_datetime",
            "warehouse_transaction_type",
            "warehouse_transaction_awb",
        );
        $joinTable = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                foreach (TRANSACTION_STATUS as $status_key => $status) {
                    if ($status_key == $row['warehouse_transaction_status']) {
                        $data['results'][$key]['warehouse_transaction_status_formatted'] = ucfirst($status);
                    }
                }

                if ($row['warehouse_transaction_datetime']) {
                    $data['results'][$key]['warehouse_transaction_datetime_formatted'] = $this->functionLib->convertDatetime($row['warehouse_transaction_datetime'], 'id');
                }
                $data['results'][$key]['warehouse_transaction_total_nett_price_formatted'] = $this->functionLib->format_nominal("Rp ", $row['warehouse_transaction_total_nett_price'], 2);
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data', $data);
    }

    public function getDetailTransaction()
    {
        try {
            $transaction_id = $this->request->getGet('id');

            $data = array();
            $data['results'] = $this->transactionModel->getDetailTransaction($transaction_id);

            foreach (TRANSACTION_STATUS as $status_key => $val) {
                if ($status_key == $data['results']->warehouse_transaction_status) {
                    $data['results']->warehouse_transaction_status_formatted = ucfirst($val);
                }
            }

            $total_weight = 0;
            foreach ($data['results']->warehouse_transaction_detail as $key => $product) {
                $data['results']->warehouse_transaction_detail[$key]->warehouse_transaction_detail_unit_nett_price_formatted = $this->functionLib->format_nominal('', $product->warehouse_transaction_detail_unit_nett_price * $product->warehouse_transaction_detail_quantity, 2);
                $data['results']->warehouse_transaction_detail[$key]->warehouse_transaction_detail_unit_price_formatted = $this->functionLib->format_nominal('', $product->warehouse_transaction_detail_unit_price, 2);
                $total_weight += $product->product_weight * $product->warehouse_transaction_detail_quantity;
            }
            $data['results']->transaction_total_weight = $total_weight;

            $data['results']->warehouse_transaction_delivery_cost_formatted =  $this->functionLib->format_nominal('', $data['results']->warehouse_transaction_delivery_cost, 2);
            $data['results']->warehouse_transaction_payment_ewallet_formatted =  $this->functionLib->format_nominal('', $data['results']->warehouse_transaction_payment_ewallet, 2);
            $data['results']->warehouse_transaction_datetime_formatted =  $this->functionLib->convertDatetime($data['results']->warehouse_transaction_datetime, 'id');
            $data['results']->warehouse_transaction_total_nett_price_formatted =  $this->functionLib->format_nominal('', $data['results']->warehouse_transaction_total_nett_price, 2);


            $this->createRespon(200, 'Berhasil mengambil data', $data);
        } catch (\Throwable $th) {

            $this->createRespon(400, $th->getMessage(), []);
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

        $this->createRespon(200, 'Berhasil mengambil data', ['results' => $data]);
    }

    public function addTransaction()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'warehouse_transaction_warehouse_id' => [
                'label' => 'Gudang',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gudang tidak boleh kosong',
                ],
            ],
            'warehouse_transaction_buyer_type' => [
                'label' => 'Tipe',
                'rules' => 'required|in_list[member,stockist]',
                'errors' => [
                    'required' => 'Tipe tidak boleh kosong',
                    'in_list' => 'Tipe tidak sesuai',
                ],
            ],
            'warehouse_transaction_buyer_member_id' => [
                'label' => 'Mitra',
                'rules' => 'required|is_not_unique[sys_member.member_id]',
                'errors' => [
                    'required' => 'Mitra tidak boleh kosong',
                    'is_not_unique' => 'Mitra tidak ditemukan',
                ],
            ],
            'warehouse_transaction_detail.*.product_id' => [
                'label' => 'Produk',
                'rules' => 'required|is_not_unique[inv_product.product_id]',
                'errors' => [
                    'required' => 'Produk tidak boleh kosong',
                    'is_not_unique' => 'Product tidak ditemukan',
                ],
            ],
        ]);

        if ($validation->run((array) $this->request->getVar()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $data = array();
            $this->db->transBegin();
            try {
                $datetime = date('Y-m-d H:i:s');
                $expired_date = date("Y-m-d", strtotime($datetime . " +1 year"));

                $warehouse_id = $this->request->getVar("warehouse_transaction_warehouse_id");
                $member_id = $this->request->getVar("warehouse_transaction_buyer_member_id");
                $member = $this->db->query("SELECT * FROM sys_member WHERE member_id = {$member_id}")->getRow();
                $member_username = $this->db->table('sys_member_account')->select('member_account_username')->getWhere(['member_account_member_id' => $member_id])->getRow('member_account_username');
                $stockist_name = $this->db->table('inv_stockist')->select('stockist_name')->getWhere(['stockist_member_id' => $member_id])->getRow('stockist_name');
                $type = $this->request->getVar("warehouse_transaction_buyer_type");
                $details = $this->request->getVar("warehouse_transaction_detail");
                $transaction_code = $this->transactionModel->getTransactionCode($type);

                $stockist = false;
                if ($type == "stockist") {
                    $stockist = $this->db->query("SELECT * FROM inv_stockist WHERE stockist_member_id = {$member_id}")->getRow();
                }

                $total_price = 0;
                $data_detail = [];
                foreach ($details as $detail) {
                    $product = $this->db->query("SELECT * FROM inv_product WHERE product_id = {$detail['product_id']}")->getRow();
                    $price = $product->product_member_price;
                    if ($type == "stockist" && $stockist->stockist_type == "master") {
                        if (TRANSACTION_STOCKIST_MASTER_DISCOUNT_TYPE == "percent") {
                            $price = $product->product_member_price - $product->product_member_price * TRANSACTION_STOCKIST_MASTER_DISCOUNT_PERCENT / 100;
                        } else {
                            $price = $product->product_member_price - TRANSACTION_STOCKIST_MASTER_DISCOUNT_VALUE;
                        }
                    } else if ($type == "stockist" && $stockist->stockist_type == "mobile") {
                        if (TRANSACTION_STOCKIST_MOBILE_DISCOUNT_TYPE == "percent") {
                            $price = $product->product_member_price - $product->product_member_price * TRANSACTION_STOCKIST_MOBILE_DISCOUNT_PERCENT / 100;
                        } else {
                            $price = $product->product_member_price - TRANSACTION_STOCKIST_MOBILE_DISCOUNT_VALUE;
                        }
                    } else if ($type == "member") {
                        if (TRANSACTION_MEMBER_DISCOUNT_TYPE == "percent") {
                            $price = $product->product_member_price - $product->product_member_price * TRANSACTION_MEMBER_DISCOUNT_PERCENT / 100;
                        } else {
                            $price = $product->product_member_price - TRANSACTION_MEMBER_DISCOUNT_VALUE;
                        }
                    }
                    $data_detail[] = [
                        "warehouse_transaction_detail_product_id" => $detail["product_id"],
                        "warehouse_transaction_detail_product_code" => $product->product_code,
                        "warehouse_transaction_detail_product_name" => $product->product_name,
                        "warehouse_transaction_detail_unit_price" => $price,
                        // "warehouse_transaction_detail_discount_type" => "value",
                        // "warehouse_transaction_detail_discount_percent" => 0,
                        // "warehouse_transaction_detail_discount_value" => 0,
                        "warehouse_transaction_detail_unit_nett_price" => $price,
                        "warehouse_transaction_detail_quantity" => $detail["product_qty"],
                    ];
                    $total_price += $price;

                    $stock = $this->db->table("inv_warehouse_product_stock")->getWhere(["warehouse_product_stock_warehouse_id" => $warehouse_id, "warehouse_product_stock_product_id" => $detail['product_id']])->getRow();
                    if ($detail['product_qty'] > $stock->warehouse_product_stock_balance) {
                        throw new \Exception("Stok tidak mencukupi.", 1);
                    }
                    $this->stockModel->log($warehouse_id, $detail['product_id'], "out", $detail['product_qty'], $stock->warehouse_product_stock_balance, "Transaksi Kode " . $transaction_code, $datetime);

                    $this->db->table('inv_warehouse_product_stock')->set("warehouse_product_stock_balance", "warehouse_product_stock_balance-" . $detail['product_qty'], FALSE)
                        ->where(["warehouse_product_stock_warehouse_id" => $warehouse_id, "warehouse_product_stock_product_id" => $detail['product_id']])->update();
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update data stock.", 1);
                    }

                    if ($type == "stockist") {
                        $stockStockist = $this->db->table("inv_stockist_product_stock")->getWhere(["stockist_product_stock_stockist_member_id" => $member_id, "stockist_product_stock_product_id" => $detail['product_id']])->getRow();
                        $this->stockModel->logStockist($member_id, $detail['product_id'], "in", $detail['product_qty'], $stockStockist->stockist_product_stock_balance, "Transaksi Kode " . $transaction_code, $datetime);

                        $this->db->table('inv_stockist_product_stock')->set("stockist_product_stock_balance", "stockist_product_stock_balance+" . $detail['product_qty'], FALSE)
                            ->where(["stockist_product_stock_stockist_member_id" => $member_id, "stockist_product_stock_product_id" => $detail['product_id']])->update();
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update data stock.", 1);
                        }
                    } else if ($type == "member") {
                        //dipakai karena menggunakan konversi
                        $stockMember = $this->db->table("inv_member_product_stock")->getWhere(["member_product_stock_member_id" => $member_id, "member_product_stock_product_id" => $detail['product_id']])->getRow();
                        $this->stockModel->logMember($member_id, $detail['product_id'], "in", $detail['product_qty'], $stockMember->member_product_stock_balance, "Transaksi Kode " . $transaction_code, $datetime);

                        $this->db->table('inv_member_product_stock')->set("member_product_stock_balance", "member_product_stock_balance+" . $detail['product_qty'], FALSE)
                            ->where(["member_product_stock_member_id" => $member_id, "member_product_stock_product_id" => $detail['product_id']])->update();
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update data stock.", 1);
                        }
                        //tidak dipakai karena menggunakan konversi
                        // $serials = $this->db->query(
                        //     "SELECT * FROM sys_serial
                        //     WHERE serial_serial_type_id = {$product->product_serial_type_id}
                        //     AND serial_is_active = 0
                        //     ORDER BY serial_id ASC
                        //     LIMIT 0,{$detail['product_qty']}")->getResult();
                        // foreach ($serials as $serial) {
                        //     $this->db->table("log_serial_distribution")->insert([
                        //         "serial_distribution_serial_id" => $serial->serial_id,
                        //         "serial_distribution_serial_type_id" => $serial->serial_serial_type_id,
                        //         "serial_distribution_owner_status" => $type,
                        //         "serial_distribution_owner_ref_id" => $member_id,
                        //         "serial_distribution_owner_datetime" => $datetime,
                        //         "serial_distribution_owner_datetime" => $datetime,
                        //         "serial_distribution_note" => "Transaksi dengan kode: ".$transaction_code,
                        //     ]);
                        //     if ($this->db->affectedRows() <= 0) {
                        //         throw new \Exception("Gagal menyimpan log serial", 1);
                        //     }

                        //     $this->db->table("sys_serial_member_stock")->insert([
                        //         "serial_member_stock_serial_id" => $serial->serial_id,
                        //         "serial_member_stock_serial_pin" => $serial->serial_pin,
                        //         "serial_member_stock_member_id" => $member_id,
                        //         "serial_member_stock_serial_type_id" => $serial->serial_serial_type_id,
                        //         "serial_member_stock_datetime" => $datetime,
                        //         "serial_member_stock_is_expired" => 0,
                        //         "serial_member_stock_expired_date" => $expired_date,
                        //     ]);
                        //     if ($this->db->affectedRows() <= 0) {
                        //         throw new \Exception("Gagal menyimpan serial member stok", 1);
                        //     }

                        //     $this->db->table("sys_serial")->update([
                        //         "serial_expired_date" => $expired_date,
                        //         "serial_is_active" => 1,
                        //         "serial_active_datetime" => $datetime,
                        //         "serial_active_ref_type" => "company",
                        //         "serial_active_ref_id" => $this->session->administrator_id,
                        //         "serial_last_owner_status" => $type,
                        //         "serial_last_owner_ref_id" => $member_id,
                        //         "serial_last_owner_datetime" => $datetime,
                        //     ], ["serial_id" => $serial->serial_id]);
                        //     if ($this->db->affectedRows() <= 0) {
                        //         throw new \Exception("Gagal mengubah serial", 1);
                        //     }
                        // }
                    } else {
                        throw new \Exception("Tipe tidak diketahui", 1);
                    }
                }

                $this->db->table('inv_warehouse_transaction')->insert([
                    "warehouse_transaction_warehouse_id" => $warehouse_id,
                    "warehouse_transaction_code" => $transaction_code,
                    "warehouse_transaction_buyer_type" => $type,
                    "warehouse_transaction_buyer_member_id" => $member_id,
                    "warehouse_transaction_buyer_name" => $member->member_name,
                    "warehouse_transaction_buyer_address" => $member->member_address,
                    "warehouse_transaction_buyer_mobilephone" => $member->member_mobilephone,
                    "warehouse_transaction_total_price" => $total_price,
                    // "warehouse_transaction_extra_discount_type" => "value",
                    // "warehouse_transaction_extra_discount_percent" => 0,
                    // "warehouse_transaction_extra_discount_value" => 0,
                    "warehouse_transaction_total_nett_price" => $total_price,
                    "warehouse_transaction_payment_cash" => $total_price,
                    // "warehouse_transaction_payment_ewallet" => 0,
                    "warehouse_transaction_status" => "complete",
                    // "warehouse_transaction_notes" => "",
                    "warehouse_transaction_datetime" => $datetime,
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $transaction_id = $this->db->insertID();

                $arr_product_wa = "";
                foreach ($data_detail as $detail) {
                    $this->db->table('inv_warehouse_transaction_detail')->insert(array_merge(
                        $detail,
                        [
                            "warehouse_transaction_detail_warehouse_transaction_id" => $transaction_id,
                        ]
                    ));
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Proses gagal.", 1);
                    }

                    $arr_product_wa .= "
*{$detail['warehouse_transaction_detail_quantity']} x {$detail['warehouse_transaction_detail_product_name']}*";
                }

                if (WA_NOTIFICATION_IS_ACTIVE) {
                    $client_name = COMPANY_NAME;
                    $project_name = PROJECT_NAME;
                    $client_wa_cs_number = WA_CS_NUMBER;
                    $buyer_name = $member->member_name;
                    $buyer_mobilephone = $member->member_mobilephone;
                    $content_price = $this->functionLib->format_nominal('', $total_price, 2);

                    $content = "";
                    if ($type == 'member') {
                        $content .= "Penjualan produk ke *{$member_username} ({$member->member_name})* berhasil diproses, berikut item produk:";
                    } else {
                        $content .= "Terimakasih atas pembelian produk dari *{$stockist_name}*.
Berikut item produk pembelian Anda:";
                    }

                    $content .= "{$arr_product_wa}
Dengan total pembelian sebesar *Rp. {$content_price},-*
RefID: {$transaction_code}
                    
Jika Anda punya pertanyaan, silakan hubungi customer service kami di:
wa.me/{$client_wa_cs_number} (WA/Telp)
                    
*-- {$project_name} --*";

                    $this->functionLib->send_waone($content, phoneNumberFilter($buyer_mobilephone));
                }

                $this->db->transCommit();
                $this->createRespon(200, "Transaki penjualan produk berhasil diproses.", ['results' => $data]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function approveTransaction()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            "warehouse_transaction_id" => [
                "label" => "ID",
                "rules" => "required|is_not_unique[inv_warehouse_transaction.warehouse_transaction_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
        ]);

        if ($validation->run((array) $this->request->getVar()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {

            $this->db->transBegin();
            try {
                $datetime = date('Y-m-d H:i:s');
                $update = [
                    "warehouse_transaction_status_datetime" => $datetime,
                    'warehouse_transaction_status' => 'approved'
                ];

                $where = ["warehouse_transaction_id" => $this->request->getVar("warehouse_transaction_id")];
                $this->db->table("inv_warehouse_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $transaction = $this->db->table("inv_warehouse_transaction")->getWhere($where)->getRow();
                $transaction->detail = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $transaction->warehouse_transaction_id])->getResult();

                $transaction_status_data = [
                    "warehouse_transaction_status_warehouse_transaction_id" => $transaction->warehouse_transaction_id,
                    "warehouse_transaction_status_value" => $update["warehouse_transaction_status"],
                    "warehouse_transaction_status_datetime" => $datetime,
                    "warehouse_transaction_status_ref_type" => "admin",
                    "warehouse_transaction_status_ref_id" => session("admin")["admin_id"],
                ];
                // $this->transaction_service->addStatus($transaction_status_data);

                $this->db->transCommit();

                if (WA_NOTIFICATION_IS_ACTIVE) {
                    $arr_product_wa = "";
                    foreach ($transaction->detail as $key => $value) {
                        $arr_product_wa .= "
*{$value->warehouse_transaction_detail_quantity} x {$value->warehouse_transaction_detail_unit_price} {$value->warehouse_transaction_detail_product_name}*";
                    }

                    $member = $this->db->table("sys_member")->select("member_name, member_mobilephone")->getWhere(["member_id" => $transaction->warehouse_transaction_buyer_member_id])->getRow();
                    $project_name = PROJECT_NAME;
                    $client_wa_cs_number = WA_CS_NUMBER;
                    $fmt = numfmt_create('id_ID', \NumberFormatter::CURRENCY);
                    $price =  numfmt_format_currency($fmt, $transaction->warehouse_transaction_total_nett_price, "IDR");

                    $content = "*APPROVAL TRANSAKSI PEMBELIAN BERHASIL*

Hai, {$member->member_name}
Transaksi Pembelian Stokis berhasil diapprove, berikut data transaksi Anda:

Detail produk sebagai berikut:
{$arr_product_wa}
=====================================================
*Total {$price}*

Terimakasih atas kepercayaan Anda dan nikmati berbagai manfaat dari produk.

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

*-- {$project_name} --*";

                    $this->functionLib->send_waone($content, $member->member_mobilephone);
                }
                $this->createRespon(200, "Approval transaksi berhasil diproses");
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function rejectTransaction()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            "warehouse_transaction_id" => [
                "label" => "ID",
                "rules" => "required|is_not_unique[inv_warehouse_transaction.warehouse_transaction_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
        ]);

        if ($validation->run((array) $this->request->getVar()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {

            $this->db->transBegin();
            try {
                $datetime = date('Y-m-d H:i:s');
                $update = [
                    "warehouse_transaction_status" => "void",
                    "warehouse_transaction_status_datetime" => $datetime,
                ];
                $where = ["warehouse_transaction_id" => $this->request->getVar("warehouse_transaction_id")];
                $this->db->table("inv_warehouse_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $transaction = $this->db->table("inv_warehouse_transaction")->getWhere($where)->getRow();
                $transaction->detail = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $transaction->warehouse_transaction_id])->getResult();

                $warehouse_id = 1;
                foreach ($transaction->detail as $key => $detail) {
                    $product = $this->db->table("inv_product")->getWhere(["product_id" => $detail->warehouse_transaction_detail_product_id])->getRow();
                    $this->stock_service->addStock((object)[
                        "quantity" => $detail->warehouse_transaction_detail_quantity,
                        "warehouse_id" => $warehouse_id,
                        "product_id" => $product->product_id,
                        "unit_price" => $detail->warehouse_transaction_detail_unit_price,
                        "note" => "Pengembalian produk transaksi ditolak Kode : " . $transaction->warehouse_transaction_code,
                        "datetime" => $datetime,
                    ]);
                }

                $this->db->transCommit();
                $this->createRespon(200, "Approval transaksi berhasil diproses");
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function updateAwb()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            "warehouse_transaction_awb" => [
                "label" => "ID",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ]);

        if ($validation->run((array) $this->request->getVar()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {

            $this->db->transBegin();
            try {
                $datetime = date('Y-m-d H:i:s');
                $update = [
                    "warehouse_transaction_awb" => $this->request->getVar("warehouse_transaction_awb"),
                    "warehouse_transaction_delivery_status" => "delivered",
                    "warehouse_transaction_status" => "delivered",
                ];
                $where = ["warehouse_transaction_id" => $this->request->getVar("warehouse_transaction_id")];
                $this->db->table("inv_warehouse_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, "Resi berhasil di update");
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function approved()
    {
        $this->db->transBegin();
        try {
            $data = $this->request->getPost('data');
            $success = $failed = 0;

            foreach ($data as $id) {
                $update = [
                    "warehouse_transaction_status" => "complete",
                ];
                $where = ["warehouse_transaction_id" => $id];
                $transaction = $this->stockist_model->edit($update, $where);

                $transaction_status_data = [
                    "warehouse_transaction_status_warehouse_transaction_id" => $id,
                    "warehouse_transaction_status_value" => $update["warehouse_transaction_status"],
                    "warehouse_transaction_status_datetime" => date("Y-m-d H:i:s"),
                    "warehouse_transaction_status_ref_type" => "member",
                    "warehouse_transaction_status_ref_id" => $transaction->warehouse_transaction_buyer_member_id,
                ];
                $this->transaction_service->addStatus($transaction_status_data);

                if ($transaction->warehouse_transaction_buyer_type === 'stockist') {
                    foreach ($transaction->detail as $key => $detail) {
                        $product = $this->db->table("inv_product")->getWhere(["product_id" => $detail->warehouse_transaction_detail_product_id])->getRow();
                        $this->stock_service->addStockStockist((object)[
                            "quantity" => $detail->warehouse_transaction_detail_quantity,
                            "member_id" => $transaction->warehouse_transaction_buyer_member_id,
                            "product_id" => $product->product_id,
                            "unit_price" => $detail->warehouse_transaction_detail_unit_price,
                            "note" => "Penerimaan produk transaksi Kode : " . $transaction->warehouse_transaction_code,
                            "datetime" => date("Y-m-d H:i:s"),
                        ]);
                    }
                }

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menerima transaksi!';
            if ($success == 0) {
                $message = 'Gagal menerima transaksi';
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), 'Bad Request');
        }
    }

    public function get_count_trx()
    {
        $data = [];
        $data['stockist'] = $this->db->table("inv_warehouse_transaction")->selectCount("warehouse_transaction_id")->where("warehouse_transaction_buyer_type = 'stockist'")->where("warehouse_transaction_status NOT IN ('complete', 'pending')")->get()->getRow("warehouse_transaction_id");
        $data['detail_stockist'] = [
            "pengemasan" => $this->db->table("inv_warehouse_transaction")->selectCount("warehouse_transaction_id")->getWhere(['warehouse_transaction_buyer_type' => "stockist", "warehouse_transaction_status" => "paid"])->getRow("warehouse_transaction_id"),
            "pengiriman" => $this->db->table("inv_warehouse_transaction")->selectCount("warehouse_transaction_id")->getWhere(['warehouse_transaction_buyer_type' => "stockist", "warehouse_transaction_status" => "delivered"])->getRow("warehouse_transaction_id"),
        ];
        $data['member'] = $this->db->table("inv_warehouse_transaction")->selectCount("warehouse_transaction_id")->where("warehouse_transaction_buyer_type = 'member'")->where("warehouse_transaction_status NOT IN ('complete', 'pending')")->get()->getRow("warehouse_transaction_id");
        $data['detail_member'] = [
            "pengemasan" => $this->db->table("inv_warehouse_transaction")->selectCount("warehouse_transaction_id")->getWhere(['warehouse_transaction_buyer_type' => "member", "warehouse_transaction_status" => "paid"])->getRow("warehouse_transaction_id"),
            "pengiriman" => $this->db->table("inv_warehouse_transaction")->selectCount("warehouse_transaction_id")->getWhere(['warehouse_transaction_buyer_type' => "member", "warehouse_transaction_status" => "delivered"])->getRow("warehouse_transaction_id"),
        ];
        $data['withdraw_ewallet'] = $this->db->table("sys_ewallet_withdrawal")->selectCount("ewallet_withdrawal_id")->getWhere(["ewallet_withdrawal_status" => "pending"])->getRow('ewallet_withdrawal_id');
        $data['message'] = $this->db->table("site_message")->selectCount("message_id")->getWhere(["message_status" => 0, "message_receiver_ref_type" => "admin"])->getRow('message_id');

        $this->createRespon(200, "Success get data", $data);
    }
}
