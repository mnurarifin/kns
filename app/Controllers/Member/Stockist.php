<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Stockist_model;
use App\Models\Member\Transaction_model;
use App\Models\Common_model;
use App\Libraries\Notification;
use Config\Services;

class Stockist extends BaseController
{
    protected $stockist_model;
    protected $common_model;
    protected $transaction_model;
    protected $notification_lib;

    protected $activation_service;
    protected $transaction_service;
    protected $stock_service;
    protected $report_service;
    protected $repeatorder_service;
    protected $payment_service;
    protected $ewallet_service;

    public function __construct()
    {
        $this->stockist_model = new Stockist_model();
        $this->common_model = new Common_model();
        $this->stockist_model = new stockist_model();
        $this->transaction_model = new Transaction_model();
        $this->notification_lib = new Notification();
        $this->activation_service = service('Activation');
        $this->transaction_service = service('Transaction');
        $this->stock_service = service('Stock');
        $this->report_service = service('Report');
        $this->repeatorder_service = service('Repeatorder');
        $this->payment_service = service('Payment');
        $this->ewallet_service = service('Ewallet');
    }

    public function index()
    {
        $this->buy();
    }

    public function buy()
    {
        $data = [];

        $this->template->title("Transaksi Produk");
        $this->template->breadcrumbs(["Transaksi Stokis", "Transaksi Produk"]);
        $this->template->content("Member/Stockist/stockistBuyView", $data);
        $this->template->show("Template/Member/main");
    }

    public function sell()
    {
        $data = [];

        $this->template->title("Transaksi Penjualan");
        $this->template->breadcrumbs(["Transaksi Stokis", "Transaksi Penjualan"]);
        $this->template->content("Member/Stockist/stockistSellView", $data);
        $this->template->show("Template/Member/main");
    }

    public function sell_success()
    {
        $data = [];

        $this->template->title("Aktivasi Berhasil");
        $this->template->breadcrumbs(["Transaksi Stokis", "Aktivasi Berhasil"]);
        $this->template->content("Member/Stockist/stockistSellSuccessView", $data);
        $this->template->show("Template/Member/main");
    }

    public function receipt_payment()
    {
        $data = [];

        $this->template->title("Konfirmasi Pembayaran");
        $this->template->breadcrumbs(["Transaksi Stokis", "Konfirmasi Pembayaran"]);
        $this->template->content("Member/Stockist/stockistReceiptView", $data);
        $this->template->show("Template/Member/main");
    }

    public function log()
    {
        $data = [];

        $this->template->title("Riwayat Transaksi");
        $this->template->breadcrumbs(["Transaksi Stokis", "Riwayat Transaksi"]);
        $this->template->content("Member/Stockist/stockistTransactionHistoryView", $data);
        $this->template->show("Template/Member/main");
    }

    public function member_log()
    {
        $data = [];

        $this->template->title("Riwayat Transaksi");
        $this->template->breadcrumbs(["Transaksi Stokis", "Riwayat Transaksi"]);
        $this->template->content("Member/Stockist/stockistTransactionMemberHistoryView", $data);
        $this->template->show("Template/Member/main");
    }

    public function list()
    {
        $data = [];

        $this->template->title("Daftar Master/Stokis");
        $this->template->breadcrumbs(["Transaksi Stokis", "Daftar Master/Stokis"]);
        $this->template->content("Member/Stockist/stockistListView", $data);
        $this->template->show("Template/Member/main");
    }

    public function stock()
    {
        $data = [];

        $this->template->title("Stok Produk");
        $this->template->breadcrumbs(["Transaksi Stokis", "Stok Produk"]);
        $this->template->content("Member/Stockist/stockistStockView", $data);
        $this->template->show("Template/Member/main");
    }

    public function stock_log()
    {
        $data = [];

        $this->template->title("Riwayat Stok Produk");
        $this->template->breadcrumbs(["Transaksi Stokis", "Riwayat Stok Produk"]);
        $this->template->content("Member/Stockist/stockistStockHistoryView", $data);
        $this->template->show("Template/Member/main");
    }

    public function receivement($type = '')
    {
        $data = [];
        $data['type'] = $type;

        $this->template->title("Penerimaan Stok");
        $this->template->breadcrumbs(["Transaksi", "Penerimaan Stok"]);

        if ($type == '') {
            $this->template->content("Member/Stockist/stockistReceivementView", $data);
        } else if ($type == 'stockist') {
            $this->template->content("Member/Stockist/stockistReceivementMasterView", $data);
        }

        $this->template->show("Template/Member/main");
    }

    public function transaction()
    {
        $data = [];

        $this->template->title("Data Transaksi Mitra");
        $this->template->breadcrumbs(["Transaksi Mitra", "Data Transaksi Mitra"]);
        $this->template->content("Member/Stockist/stockistTransactionListView", $data);
        $this->template->show("Template/Member/main");
    }

    public function packaging()
    {
        $data = [];

        $this->template->title("Pengemasan");
        $this->template->breadcrumbs(["Transaksi Mitra", "Pengemasan"]);
        $this->template->content("Member/Stockist/stockistTransactionPackagingView", $data);
        $this->template->show("Template/Member/main");
    }

    public function delivery()
    {
        $data = [];

        $this->template->title("Pengiriman");
        $this->template->breadcrumbs(["Transaksi Mitra", "Pengiriman"]);
        $this->template->content("Member/Stockist/stockistTransactionDeliveryView", $data);
        $this->template->show("Template/Member/main");
    }

    public function saldo()
    {
        $data = [];

        $this->template->title("Saldo Stokis");
        $this->template->breadcrumbs(["Transaksi Stokis", "Saldo Stokis"]);
        $this->template->content("Member/Stockist/stockistSaldoView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_saldo()
    {
        $this->stockist_model->init($this->request);
        $data = $this->stockist_model->getSaldo(session("member")["member_id"]);

        $this->db->transCommit();
        $this->restLib->responseSuccess("Data saldo.", ['results' => $data]);
    }

    public function claim_saldo()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "amount" => [
                "label" => "Nominal",
                "rules" => "required|numeric|greater_than[0]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "numeric" => "Format {field} tidak sesuai.",
                    "greater_than" => "{field} harus lebih dari 0.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        try {
            $this->db->transBegin();

            $saldo = $this->db->table("sys_ewallet")->getWhere(["ewallet_member_id" => session('member')['member_id']])->getRow();
            $bank_info = $this->db->table("sys_member")->select("member_bank_id, member_bank_name, member_bank_account_name, member_bank_account_no")->getWhere(["member_id" => session('member')['member_id']])->getRow();

            // $this->db->table("sys_ewallet")->where("ewallet_member_id", session('member')['member_id'])->update(["ewallet_paid" => $saldo->ewallet_acc]);
            $this->db->table("sys_ewallet")->where("ewallet_member_id", session('member')['member_id'])->update(["ewallet_paid" => $saldo->ewallet_paid + $params->amount]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal withdraw saldo", 1);
            }

            $arr_data_log = [
                "ewallet_log_member_id" => session('member')['member_id'],
                // "ewallet_log_value" => $saldo->ewallet_acc - $saldo->ewallet_paid,
                "ewallet_log_value" => $params->amount,
                "ewallet_log_type" => 'out',
                "ewallet_log_note" => 'Withdraw saldo',
                "ewallet_log_datetime" => date("Y-m-d H:i:s")
            ];

            $this->db->table("sys_ewallet_log")->insert($arr_data_log);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambahkan log withdraw", 1);
            }

            $arr_data_withdrawal = [
                "ewallet_withdrawal_member_id" => session('member')['member_id'],
                "ewallet_withdrawal_member_name" => session('member')['member_name'],
                // "ewallet_withdrawal_value" => $saldo->ewallet_acc - $saldo->ewallet_paid,
                "ewallet_withdrawal_value" => $params->amount,
                "ewallet_withdrawal_bank_id" => $bank_info->member_bank_id,
                "ewallet_withdrawal_bank_name" => $bank_info->member_bank_name,
                "ewallet_withdrawal_bank_account_name" => $bank_info->member_bank_account_name,
                "ewallet_withdrawal_bank_account_no" => $bank_info->member_bank_account_no,
                "ewallet_withdrawal_note" => "Withdraw saldo",
                "ewallet_withdrawal_datetime" => date("Y-m-d H:i:s")
            ];

            $this->db->table("sys_ewallet_withdrawal")->insert($arr_data_withdrawal);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambahkan daftar withdrawal", 1);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Berhasil withdraw saldo.", []);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_saldo_summary()
    {
        $this->stockist_model->init($this->request);
        $data = $this->stockist_model->getSaldoSummary(session("member")["member_id"]);

        $this->db->transCommit();
        $this->restLib->responseSuccess("Data summary saldo.", $data);
    }

    public function get_stock()
    {
        $this->stockist_model->init($this->request);
        $data = $this->stockist_model->getStock(session("member")["member_id"]);

        $this->db->transCommit();
        $this->restLib->responseSuccess("Data stock.", $data);
    }

    public function get_member_transaction($status = "")
    {
        $this->stockist_model->init($this->request);
        $data = $this->stockist_model->getMemberTransaction(session("member")["member_id"], $status);

        $this->db->transCommit();
        $this->restLib->responseSuccess("Data transaksi mitra.", $data);
    }

    public function get_list()
    {
        $this->stockist_model->init($this->request);
        $data = $this->stockist_model->getList();

        $this->db->transCommit();
        $this->restLib->responseSuccess("Data stockist.", $data);
    }

    public function get_stock_log()
    {
        $this->stockist_model->init($this->request);
        $data = $this->stockist_model->getStockLog(session("member")["member_id"]);

        $this->db->transCommit();
        $this->restLib->responseSuccess("Data riwayat stock.", $data);
    }

    public function excelStockistStockHistory()
    {
        $id = session("member")["member_id"];
        $tableHead = array(
            'Tanggal',
            'Produk',
            'Status',
            'Jumlah',
            'Sisa Stok',
            'Keterangan',
        );

        $tableAlign = json_decode($this->request->getPost('align'));

        $tableName = 'inv_stockist_product_stock_log';
        $columns = array(
            "stockist_product_stock_log_datetime",
            "product_name" => "stockist_product_stock_log_product_name",
            "stockist_product_stock_log_type",
            "stockist_product_stock_log_quantity",
            "stockist_product_stock_log_balance",
            "stockist_product_stock_log_note",
        );
        $joinTable = "JOIN inv_product ON product_id = stockist_product_stock_log_product_id";
        $whereCondition = " stockist_product_stock_log_stockist_member_id = {$id}";
        $groupBy = '';

        $request['columns'] = $columns;
        $request['filter'] = (array) $this->request->getGet('filter');
        $request['dir'] = 'DESC';
        $request['sort'] = 'stockist_product_stock_log_datetime';

        $results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition, 1000, $groupBy);

        // if (!empty($results)) {
        //     foreach ($results as $key => $value) {
        //         array_splice($results[$key], 1, 0, ['bonus_transfer_subtotal' => $value['bonus_transfer_total'] + $value['bonus_transfer_adm_charge_value'] + $value['bonus_transfer_tax_value']]);
        //     }
        // }

        $title = "Daftar Riwayat Stok Produk";

        Services::DataTableLib()->exportToExcel($tableHead, $results, $title, $tableAlign);
    }

    public function get_receiver()
    {
        $params = getRequestParamsData($this->request, "POST");

        $data = $this->transaction_model->getMemberAddress($params->member_id);

        $this->restLib->responseSuccess("Data stock.", ['results' => $data]);
    }

    public function get_transaction_delivered($type = '')
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;

            $this->stockist_model->init($this->request);
            $data = $this->stockist_model->getTransactionDelivered(session("member")["member_id"], $type);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data penerimaan transaksi.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_transaction()
    {
        $data = [];
        // $data['total'] = (int)$this->db->table("inv_stockist_transaction")->selectCount("stockist_transaction_id")->where("stockist_transaction_status NOT IN ('pending', 'complete')")->where("stockist_transaction_stockist_member_id", session('member')['member_id'])->get()->getRow('stockist_transaction_id') ?? 0;
        $data['packaging'] = (int)$this->db->table("inv_stockist_transaction")->selectCount("stockist_transaction_id")->where("stockist_transaction_status = 'paid'")->where("stockist_transaction_stockist_member_id", session('member')['member_id'])->get()->getRow('stockist_transaction_id') ?? 0;
        $data['delivery'] = (int)$this->db->table("inv_stockist_transaction")->selectCount("stockist_transaction_id")->where("stockist_transaction_status = 'delivered'")->where("stockist_transaction_stockist_member_id", session('member')['member_id'])->get()->getRow('stockist_transaction_id') ?? 0;
        $data['total'] = $data['packaging'] + $data['delivery'];

        $this->restLib->responseSuccess("Data transaksi.", $data);
    }

    public function get_transaction_history()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $transaction_id = property_exists($params, "id") ? $params->id : FALSE;
            $this->stockist_model->init($this->request);
            $data = $this->stockist_model->getTransactionHistory(session("member")["member_id"], $transaction_id);

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
            $this->stockist_model->init($this->request);
            $data = $this->stockist_model->getTransactionPayment(session("member")["member_id"], $transaction_id);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data riwayat repeat order.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
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
                    "stockist_transaction_status" => "complete",
                ];
                $where = ["stockist_transaction_id" => $id];
                $this->db->table("inv_stockist_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Status tidak bisa diproses.", 1);
                }
                $update = [
                    "stockist_transaction_status_datetime" => $this->datetime,
                ];
                $where = ["stockist_transaction_id" => $id];
                $this->db->table("inv_stockist_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Status tidak bisa diproses.", 1);
                }
                $transaction = $this->db->table("inv_stockist_transaction")->getWhere($where)->getRow();

                $this->ewallet_service->add_ewallet($transaction->stockist_transaction_stockist_member_id, $transaction->stockist_transaction_total_nett_price);
                $this->ewallet_service->add_ewallet_log(
                    $transaction->stockist_transaction_stockist_member_id,
                    $transaction->stockist_transaction_total_nett_price,
                    "in",
                    "Penerimaan transaksi kode : " . $transaction->stockist_transaction_code,
                    $this->datetime
                );

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
            $this->restLib->responseSuccess($message, $dataActive);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function transaction_delivered()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->db->transBegin();
        try {
            $data = $params->data;
            $success = $failed = 0;

            foreach ($data as $id) {
                $method = $this->db->table("inv_stockist_transaction")
                    ->select("stockist_transaction_delivery_method")
                    ->getWhere(["stockist_transaction_id" => $id])
                    ->getRow('stockist_transaction_delivery_method');

                if (!$method) {
                    throw new \Exception("Status tidak bisa diproses.", 1);
                }

                $update = [
                    "stockist_transaction_status" => $method == 'pickup' ? 'complete' : 'delivered',
                    "stockist_transaction_status_datetime" => $this->datetime,
                ];

                $where = ["stockist_transaction_id" => $id];

                $this->db->table("inv_stockist_transaction")->update($update, $where);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Status tidak bisa diproses.", 1);
                }

                $transaction = $this->db->table("inv_stockist_transaction")->getWhere($where)->getRow();

                if ($update["stockist_transaction_status"] == "complete") {
                    $this->ewallet_service->add_ewallet($transaction->stockist_transaction_stockist_member_id, $transaction->stockist_transaction_total_nett_price);
                    $this->ewallet_service->add_ewallet_log(
                        $transaction->stockist_transaction_stockist_member_id,
                        $transaction->stockist_transaction_total_nett_price,
                        "in",
                        "Penerimaan transaksi kode : " . $transaction->stockist_transaction_code,
                        $this->datetime
                    );
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
            $message = 'Berhasil update transaksi!';
            if ($success == 0) {
                $message = 'Gagal update transaksi';
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess($message, $dataActive);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function member_approved()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->db->transBegin();
        try {
            $data = $params->data;
            $success = $failed = 0;

            foreach ($data as $id) {
                $update = [
                    "stockist_transaction_status" => "complete",
                ];
                $where = ["stockist_transaction_id" => $id];
                $this->db->table("inv_stockist_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Status tidak bisa diproses.", 1);
                }
                $update = [
                    "stockist_transaction_status_datetime" => $this->datetime,
                ];
                $where = ["stockist_transaction_id" => $id];
                $this->db->table("inv_stockist_transaction")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Status tidak bisa diproses.", 1);
                }

                if ($this->db->affectedRows() > 0) {
                    $info_trx = $this->db->table("inv_stockist_transaction")->select("stockist_transaction_total_nett_price, stockist_transaction_code")->getWhere($where)->getRow();
                    $info_saldo = $this->db->table("sys_ewallet")->select("ewallet_acc")->getWhere(["ewallet_member_id" => session('member')['member_id']])->getRow("ewallet_acc");

                    $this->db->table("sys_ewallet")->where("ewallet_member_id", session('member')['member_id'])->update(["ewallet_acc" => $info_saldo + $info_trx->stockist_transaction_total_nett_price]);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update ewallet", 1);
                    }

                    $arr_data_log = [
                        "ewallet_log_member_id" => session('member')['member_id'],
                        "ewallet_log_value" => $info_trx->stockist_transaction_total_nett_price,
                        "ewallet_log_type" => 'in',
                        "ewallet_log_note" => "Saldo dari transaksi mitra {$info_trx->stockist_transaction_code}",
                        "ewallet_log_datetime" => date("Y-m-d H:i:s")
                    ];

                    $this->db->table("sys_ewallet_log")->insert($arr_data_log);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal insert log saldo", 1);
                    }

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
            $this->restLib->responseSuccess($message, $dataActive);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
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

        $this->db->transBegin();
        try {
            $type_stockist = $this->db->table("inv_stockist")->select("stockist_type")->getWhere(["stockist_member_id" => session('member')['member_id']])->getRow("stockist_type");
            $price = "product_master_stockist_price";
            if ($type_stockist == "mobile") {
                $price = "product_mobile_stockist_price";
            }

            $total_price = 0;
            $arr_product = [];
            foreach ($params->detail as $key => $detail) {
                $detail = (object) $detail;
                $product = $this->db->table("inv_product")->select("product_id, {$price} as product_price")->getWhere(["product_id" => $detail->product_id])->getRow();

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
            $payment_ewallet = 0;

            if ($params->use_saldo === 'true') {
                $saldo = $this->db->table("sys_ewallet")->select("ewallet_member_id, ewallet_acc, ewallet_paid, (ewallet_acc-ewallet_paid) as saldo")->getWhere(["ewallet_member_id" => session('member')['member_id']])->getRow();
                $arr_update_saldo = [];
                $arr_log = [];

                $sum = $total_nett - (int)$saldo->saldo;

                $arr_log = [
                    'ewallet_log_member_id' => session('member')['member_id'],
                    'ewallet_log_type' => 'out',
                    'ewallet_log_note' => 'Pembelian produk dengan kode transaksi ' . $transaction_code,
                    'ewallet_log_datetime' => date("Y-m-d H:i:s")
                ];

                if ($sum < 0) {
                    $arr_log['ewallet_log_value'] = $total_nett;
                    $payment_ewallet = $total_nett;

                    $arr_update_saldo = ['ewallet_paid' => $saldo->ewallet_paid + $total_nett];
                    $total_nett = 0;
                } else {
                    $total_nett = $sum;
                    $payment_ewallet = $saldo->saldo;

                    $arr_update_saldo = ['ewallet_paid' => $saldo->ewallet_paid + $saldo->saldo];
                    $arr_log['ewallet_log_value'] = $saldo->saldo;
                }

                $this->db->table("sys_ewallet_log")->insert($arr_log);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah log ewallet", 1);
                }

                $this->db->table("sys_ewallet")->where('ewallet_member_id', session('member')['member_id'])->update($arr_update_saldo);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update ewallet", 1);
                }
            }

            $notes_ = "";
            if ($params->transaction_delivery_method == "courier") {
                $notes_ = "Ekspedisi " . $params->transaction_courier_code . " - " . $params->transaction_courier_service;
            }

            $transaction_data = (object) [
                "transaction_code" => $transaction_code,
                "buyer_member_id" => session("member")["member_id"],
                "buyer_type" => "stockist",
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
                "stockist_member_id" => $params->type == "warehouse" ? session("member")["member_id"] : $params->stockist_member_id,
                "payment_ewallet" => $payment_ewallet
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

            if ($params->use_saldo === 'true' && $total_nett <= 0) {
                $this->db->table("inv_warehouse_transaction")->where('warehouse_transaction_id', $data['transaction_id'])->update(['warehouse_transaction_status' => 'paid', 'warehouse_transaction_status_datetime' => date("Y-m-d H:i:s")]);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal ubah data transaksi", 1);
                }
            }

            $member_email = $this->common_model->getOne("sys_member", "member_email", ["member_id" => session("member")["member_id"]]);

            $payment = [];
            if ($total_nett > 0) {
                $payment = $this->payment_service->execute(
                    $params->type == "warehouse" ? "warehouse" : "stockist",
                    $data["transaction_id"],
                    $member_email,
                    "Pembayaran transaksi kode : {$transaction_code}",
                    $total_nett
                );
            }

            $this->db->transCommit();

            if (WA_NOTIFICATION_IS_ACTIVE && $params->type !== "stockist") {
                $arr_product_wa = "";
                foreach ($params->detail as $key => $value) {
                    $value = (object) $value;
                    $product = $this->db->table("inv_product")->getWhere(["product_id" => $value->product_id])->getRow();
                    $arr_product_wa .= "
*{$value->product_qty} x {$product->product_price} {$product->product_name}*";
                }
                $fmt = numfmt_create('id_ID', \NumberFormatter::CURRENCY);
                $price =  numfmt_format_currency($fmt, $total_nett, "IDR");

                $member = $this->db->table("sys_member")->select("member_name, member_mobilephone")->getWhere(["member_id" => session("member")["member_id"]])->getRow();
                $client_name = COMPANY_NAME;
                $project_name = PROJECT_NAME;
                $client_wa_cs_number = WA_CS_NUMBER;
                $content = "*TRANSAKSI PEMBELIAN STOKIS BERHASIL*

Hai {$member->member_name},
Transaksi pembelian Stokis dengan kode *{$transaction_code}* berhasil diproses.

Detail produk sebagai berikut:
{$arr_product_wa}
=====================================================
*Total {$price}*

Segera lakukan pembayaran dan lakukan konfirmasi untuk proses pengiriman produk.

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}

*-- {$project_name} --*";

                $this->notification_lib->send_waone($content, $member->member_mobilephone);
            }

            $this->restLib->responseSuccess("Tambah transaksi berhasil diproses.", ["results" => array_merge((array)$data, (array)$payment)]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function add_transaction_sell()
    {
        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "member_id" => [
                "label" => "Metode Distribusi",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
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

        if ($params->type_buy == "activation") {
            $this->validation->setRules([
                "upline_username" => [
                    "label" => "Username Upline",
                    "rules" => "required|is_not_unique[sys_member_account.member_account_username]|is_not_unique[sys_network.network_code]",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                        "is_not_unique" => "{field} tidak ditemukan.",
                    ],
                ],
            ])->run((array) $params);
        }

        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        if ($params->type_buy == "activation") {
            $this->validation->setRules([
                "network_position" => [
                    "label" => "Posisi",
                    "rules" => "required|in_list[L,R]|check_upline[{upline_username}]",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                        "in_list" => "Format {field} tidak sesuai.",
                        "check_upline" => "Posisi dari upline sudah terisi.",
                    ],
                ],
            ])->run((array) $params);
        }

        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        try {
            $this->db->transBegin();

            $transaction_code = $this->transaction_service->generate_transaction_code("stockist");
            $total_price = 0;

            $data_buyer = $this->db->table("sys_member")->select("member_name, member_account_username")->join('sys_member_account', 'member_id = member_account_member_id')->getWhere(["member_id" => $params->member_id])->getRow();

            $data = [];
            if ($params->type_buy == "repeatorder") {
                foreach ($params->detail as $value) {
                    $product = $this->db->table("inv_product")->getWhere(["product_id" => $value['product_id']])->getRow();

                    $total_price += $product->product_price * $value['quantity'];

                    $check_stock = $this->db->table("inv_stockist_product_stock")->select("stockist_product_stock_balance")->getWhere([
                        "stockist_product_stock_stockist_member_id" => session('member')['member_id'],
                        "stockist_product_stock_product_id" => $value['product_id']
                    ])->getRow('stockist_product_stock_balance');

                    if ($value['quantity'] > $check_stock) {
                        $this->restLib->responseFailed("Stok produk {$product->product_name} tidak mencukupi.", "", []);
                    }

                    $this->db->table("inv_stockist_product_stock")
                        ->where("stockist_product_stock_stockist_member_id", session('member')['member_id'])
                        ->where("stockist_product_stock_product_id", $value['product_id'])
                        ->update([
                            "stockist_product_stock_balance" => $check_stock - $value['quantity']
                        ]);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update stock.", 1);
                    }

                    $this->db->table("inv_stockist_product_stock_log")
                        ->insert([
                            "stockist_product_stock_log_stockist_member_id" => session('member')['member_id'],
                            "stockist_product_stock_log_product_id" => $value['product_id'],
                            "stockist_product_stock_log_type" => 'out',
                            "stockist_product_stock_log_quantity" => $value['quantity'],
                            "stockist_product_stock_log_unit_price" => $product->product_price,
                            "stockist_product_stock_log_balance" => $check_stock - $value['quantity'],
                            "stockist_product_stock_log_note" => "Penjualan ke mitra {$data_buyer->member_name} ($data_buyer->member_account_username)",
                            "stockist_product_stock_log_datetime" => date("Y-m-d H:i:s"),
                        ]);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal insert stock log.", 1);
                    }
                }
            } else {
                if ($params->total_bv < 50) {
                    throw new \Exception("Minimal belanja aktivasi senilai 50 poin bv.", 1);
                }

                if ($params->total_bv > 3000) {
                    throw new \Exception("Maksimal belanja aktivasi senilai 3000 poin bv.", 1);
                }

                $rank = $this->db->table("sys_rank")
                    ->select("rank_name, rank_id, rank_bv")
                    ->where("rank_bv <= '{$params->total_bv}'")
                    ->orderBy("rank_bv", "desc")
                    ->limit(1)
                    ->get()
                    ->getRow();

                $type = 'activation';
                $this->report_service->insert_income_log($type, $params->total_bv, $params->member_id, $this->datetime, $rank->rank_id);

                foreach ($params->detail as $value) {
                    $product = $this->db->table("inv_product")->getWhere(["product_id" => $value['product_id']])->getRow();

                    $total_price += $product->product_price * $value['quantity'];

                    $check_stock = $this->db->table("inv_stockist_product_stock")->select("stockist_product_stock_balance")->getWhere([
                        "stockist_product_stock_stockist_member_id" => session('member')['member_id'],
                        "stockist_product_stock_product_id" => $value['product_id']
                    ])->getRow('stockist_product_stock_balance');

                    if ($value['quantity'] > $check_stock) {
                        $this->restLib->responseFailed("Stok produk {$product->product_name} tidak mencukupi.", "", []);
                    }

                    $this->db->table("inv_stockist_product_stock")
                        ->where("stockist_product_stock_stockist_member_id", session('member')['member_id'])
                        ->where("stockist_product_stock_product_id", $value['product_id'])
                        ->update([
                            "stockist_product_stock_balance" => $check_stock - $value['quantity']
                        ]);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update stock.", 1);
                    }

                    $this->db->table("inv_stockist_product_stock_log")
                        ->insert([
                            "stockist_product_stock_log_stockist_member_id" => session('member')['member_id'],
                            "stockist_product_stock_log_product_id" => $value['product_id'],
                            "stockist_product_stock_log_type" => 'out',
                            "stockist_product_stock_log_quantity" => $value['quantity'],
                            "stockist_product_stock_log_unit_price" => $product->product_price,
                            "stockist_product_stock_log_balance" => $check_stock - $value['quantity'],
                            "stockist_product_stock_log_note" => "Penjualan ke mitra {$data_buyer->member_name} ($data_buyer->member_account_username)",
                            "stockist_product_stock_log_datetime" => date("Y-m-d H:i:s"),
                        ]);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal insert stock log.", 1);
                    }
                }

                $params->network_code = $this->common_model->getOne("sys_member_account", "member_account_username", ['member_account_member_id' => $params->member_id]);
                $params->network_sponsor_network_code = $this->common_model->getOne("sys_member_registration", "member_registration_sponsor_username", ['member_registration_username' => $params->network_code]);
                $params->rank = $rank;
                $params->network_upline_network_code = $params->upline_username;
                $activation = $this->activation_service->execute($params);

                $this->db->table("sys_network")->where("network_member_id", $params->member_id)->update([
                    "network_point_bv" => $params->total_bv,
                    "network_rank_id" => $rank->rank_id,
                    "network_rank_name" => $rank->rank_name
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update poin bv.", 1);
                }

                $data = [
                    "sponsor_username" => $activation["sponsor_username"],
                    "sponsor_member_name" => $activation["sponsor_member_name"],
                    "upline_username" => $activation["upline_username"],
                    "upline_member_name" => $activation["upline_member_name"],
                    "network_position" => $activation["network_position"],
                    "member_mobilephone" => $activation["member_mobilephone"],
                ];
            }

            if ($params->address_type == 'self') {
                $address = $this->db->table("sys_member")->select("member_name, member_mobilephone, member_address, member_province_id, member_city_id, member_subdistrict_id")->getWhere(["member_id" => $params->member_id])->getRow();

                $params->name = $address->member_name;
                $params->mobilephone = $address->member_mobilephone;
                $params->address = $address->member_address;
                $params->province_id = $address->member_province_id;
                $params->city_id = $address->member_city_id;
                $params->subdistrict_id = $address->member_subdistrict_id;
            }

            $arr_insert_trx = [
                'stockist_transaction_code' => $transaction_code,
                'stockist_transaction_status' => 'complete',
                'stockist_transaction_stockist_member_id' => session('member')['member_id'],
                'stockist_transaction_total_nett_price' => $total_price,
                'stockist_transaction_total_price' => $total_price,
                'stockist_transaction_notes' => "Penjualan produk ke mitra {$data_buyer->member_account_username}",
                'stockist_transaction_datetime' => date("Y-m-d H:i:s"),
                'stockist_transaction_buyer_type' => 'member',
                'stockist_transaction_buyer_name' => $params->name,
                'stockist_transaction_buyer_mobilephone' => $params->mobilephone,
                'stockist_transaction_buyer_member_id' => $params->member_id,
                'stockist_transaction_buyer_address' => $params->address,
            ];

            $this->db->table('inv_stockist_transaction')->insert($arr_insert_trx);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menyimpan data transaksi.", 1);
            }

            $transaction_id = $this->db->insertID();

            if ($params->type_buy == "repeatorder") {
                $this->repeatorder_service->add_point_balance($params->member_id, $params->total_bv, $transaction_id, $this->datetime);
            } else {
                if ($params->total_bv - $rank->rank_bv > 0) {
                    $this->repeatorder_service->add_point_balance($params->member_id, $params->total_bv - $rank->rank_bv, $transaction_id, $this->datetime);
                }
                $this->repeatorder_service->insert_netgrow_sponsor($params->member_id, $rank->rank_bv);
                $this->repeatorder_service->update_point_upline($params->member_id, $rank->rank_bv);
            }

            foreach ($params->detail as $value) {
                $product = $this->db->table("inv_product")->getWhere(["product_id" => $value['product_id']])->getRow();

                $arr_insert_trx_detail = [
                    'stockist_transaction_detail_stockist_transaction_id' => $transaction_id,
                    'stockist_transaction_detail_product_id' => $value['product_id'],
                    'stockist_transaction_detail_product_code' => $product->product_code,
                    'stockist_transaction_detail_product_name' => $product->product_name,
                    'stockist_transaction_detail_unit_price' => $product->product_price,
                    'stockist_transaction_detail_unit_nett_price' => $product->product_price,
                    'stockist_transaction_detail_quantity' => $value['quantity'],
                ];

                $this->db->table('inv_stockist_transaction_detail')->insert($arr_insert_trx_detail);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menyimpan data detail transaksi.", 1);
                }
            }

            $this->db->transCommit();

            if ($params->type_buy == "repeatorder") {
                if (WA_NOTIFICATION_IS_ACTIVE) {
                    $arr_product_wa = "";
                    foreach ($params->detail as $value) {
                        $product = $this->db->table("inv_product")->getWhere(["product_id" => $value['product_id']])->getRow();
                        $arr_product_wa .= "
*{$value['quantity']} x {$product->product_price} {$product->product_name}*";
                    }

                    $fmt = numfmt_create('id_ID', \NumberFormatter::CURRENCY);
                    $price =  numfmt_format_currency($fmt, $total_price, "IDR");
                    $client_name = COMPANY_NAME;
                    $project_name = PROJECT_NAME;
                    $client_wa_cs_number = WA_CS_NUMBER;
                    $content = "*TRANSAKSI PEMBELIAN BERHASIL*

Hai {$params->name},
Transaksi pembelian anda dengan kode *{$transaction_code}* berhasil diproses.

Detail produk sebagai berikut:
{$arr_product_wa}
=====================================================
*Total {$price}*

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}

*-- {$project_name} --*";

                    $this->notification_lib->send_waone($content, $params->mobilephone);
                }
            } else {
                if (WA_NOTIFICATION_IS_ACTIVE) {
                    $client_name = COMPANY_NAME;
                    $project_name = PROJECT_NAME;
                    $client_wa_cs_number = WA_CS_NUMBER;
                    $content = "*Aktivasi Mitra Berhasil*

Hai, {$params->member_name}
Aktivasi Akun Anda berhasil diproses, berikut data Anda:

Upline: {$data['upline_username']} ({$data['upline_member_name']})
Posisi: {$data['network_position']}

Segera kembangkan bisnis Anda dan terima kasih atas kepercayaan Anda bersama {$client_name}.

Jika Anda punya pertanyaan, silakan hubungi customer service kami di:
wa.me/{$client_wa_cs_number} (WA/Telp)

*-- {$project_name} --*";

                    $this->notification_lib->send_waone($content, $data['member_mobilephone']);
                }
            }

            $this->restLib->responseSuccess("Transaksi berhasil diproses.", ["results" => $data]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function receive()
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
                "warehouse_transaction_status_datetime" => $this->datetime,
            ];
            $where = ["warehouse_transaction_id" => $params->warehouse_transaction_id];
            $transaction = $this->stockist_model->edit($update, $where);

            $transaction_status_data = [
                "warehouse_transaction_status_warehouse_transaction_id" => $params->warehouse_transaction_id,
                "warehouse_transaction_status_value" => $update["warehouse_transaction_status"],
                "warehouse_transaction_status_datetime" => $this->datetime,
                "warehouse_transaction_status_ref_type" => "member",
                "warehouse_transaction_status_ref_id" => session("member")["member_id"],
            ];
            $this->transaction_service->addStatus($transaction_status_data);

            foreach ($transaction->detail as $key => $detail) {
                $product = $this->db->table("inv_product")->getWhere(["product_id" => $detail->warehouse_transaction_detail_product_id])->getRow();
                $this->stock_service->addStockStockist((object)[
                    "quantity" => $detail->warehouse_transaction_detail_quantity,
                    "member_id" => session("member")["member_id"],
                    "product_id" => $product->product_id,
                    "unit_price" => $detail->warehouse_transaction_detail_unit_price,
                    "note" => "Penerimaan produk transaksi Kode : " . $transaction->warehouse_transaction_code,
                    "datetime" => $this->datetime,
                ]);
            }

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
                "rules" => "required|is_not_unique[inv_stockist_transaction.stockist_transaction_id]|check_status_receive_stockist",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        // $this->db->transBegin();
        try {
            $update = [
                "stockist_transaction_status" => "complete",
                "stockist_transaction_status_datetime" => $this->datetime,
            ];
            $where = ["stockist_transaction_id" => $params->stockist_transaction_id];

            $transaction = $this->stockist_model->edit_stockist($update, $where);

            $transaction_status_data = [
                "stockist_transaction_status_stockist_transaction_id" => $params->stockist_transaction_id,
                "stockist_transaction_status_value" => $update["stockist_transaction_status"],
                "stockist_transaction_status_datetime" => $this->datetime,
                "stockist_transaction_status_ref_type" => "member",
                "stockist_transaction_status_ref_id" => session("member")["member_id"],
            ];
            $this->transaction_service->addStatusStockist($transaction_status_data);

            foreach ($transaction->detail as $key => $detail) {
                $product = $this->db->table("inv_product")->getWhere(["product_id" => $detail->stockist_transaction_detail_product_id])->getRow();

                $this->stock_service->addStockStockist((object)[
                    "quantity" => $detail->stockist_transaction_detail_quantity,
                    "member_id" => session("member")["member_id"],
                    "product_id" => $product->product_id,
                    "unit_price" => $detail->stockist_transaction_detail_unit_price,
                    "note" => "Penerimaan produk transaksi Kode : " . $transaction->stockist_transaction_code,
                    "datetime" => $this->datetime,
                ]);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Stok berhasil diterima.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }


    public function getReceiver()
    {
        $search = $_GET['search'] ? $_GET['search'] : '';
        $sql = "
            SELECT 
                member_id,
                member_account_username,
                member_name
            FROM sys_member
            JOIN sys_member_account ON member_account_member_id = member_id
            WHERE member_account_username LIKE '%$search%'
            OR member_name LIKE '%$search%'
            LIMIT 30
        ";
        $result = $this->db->query($sql);
        $list = array();
        $key = 0;
        foreach ($result->getResult('array') as $row) {
            $list[$key]['id'] = $row['member_id'];
            $list[$key]['text'] = $row['member_account_username'] . ' (' . $row['member_name'] . ')';
            $key++;
        }
        echo json_encode($list);
    }

    public function getUpline()
    {
        $search = $_GET['search'] ? $_GET['search'] : '';
        $sql = "
            SELECT 
                member_id,
                member_account_username,
                member_name
            FROM sys_member
            JOIN sys_member_account ON member_account_member_id = member_id
            JOIN sys_network ON network_member_id = member_id
            WHERE member_account_username LIKE '%$search%'
            OR member_name LIKE '%$search%'
            LIMIT 30
        ";
        $result = $this->db->query($sql);
        $list = array();
        $key = 0;
        foreach ($result->getResult('array') as $row) {
            $list[$key]['id'] = $row['member_account_username'];
            $list[$key]['text'] = $row['member_account_username'] . ' (' . $row['member_name'] . ')';
            $key++;
        }
        echo json_encode($list);
    }

    public function check_network()
    {
        $params = getRequestParamsData($this->request, "GET");

        $check_is_network = $this->db->table("sys_member")->select("member_is_network")->getWhere(["member_id" => $params->member_id])->getRow("member_is_network");

        if ($check_is_network) {
            $message = "repeatorder";
        } else {
            $message = "activation";
        }
        $this->restLib->responseSuccess($message, []);
    }

    public function updateAwb()
    {
        $params = getRequestParamsData($this->request, "POST");
        $this->validation->setRules([
            "stockist_transaction_awb" => [
                "label" => "Nomor Resi",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);

        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        } else {

            $this->db->transBegin();
            try {
                $update = [
                    "stockist_transaction_awb" => $params->stockist_transaction_awb,
                    "stockist_transaction_delivery_awb" => $params->stockist_transaction_awb,
                ];
                $where = ["stockist_transaction_id" => $params->stockist_transaction_id];
                $this->db->table("inv_stockist_transaction")->update($update, $where);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $this->db->transCommit();
                $this->restLib->responseSuccess("Resi berhasil di update", []);
            } catch (\Throwable $e) {
                $this->db->transRollback();
                $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
            }
        }
    }
}
