<?php

namespace App\Services;

use App\Controllers\BaseController;
use Xendit\Xendit;

class Payment_services extends BaseController
{
    public function __construct()
    {
        Xendit::setApiKey(PAYMENT_SECRET_KEY);
        $this->db = \Config\Database::connect();
    }

    public function execute($type, $trx_id, $buyer_email, $note, $amount, $datetime = FALSE)
    {
        // $timestamp = strtotime($datetime);
        if ($amount <= 0) {
            throw new \Exception("Nominal transaksi tidak boleh 0", 1);
        }

        $params = [
            // "external_id" => "{$timestamp}_{$type}_{$trx_id}",
            "external_id" => "{$type}_{$trx_id}",
            "payer_email" => "{$buyer_email}",
            "description" => "{$note}",
            "amount" => $amount
        ];
        $invoice = (object)$this->createInvoice($params);

        $invoice->created = date("Y-m-d H:i:s", strtotime($invoice->created));
        $invoice->updated = date("Y-m-d H:i:s", strtotime($invoice->updated));
        $invoice->expiry_date = date("Y-m-d H:i:s", strtotime($invoice->expiry_date));


        if ($type == "warehouse") {
            $table = "inv_warehouse_transaction_payment";
            $arr_data = [
                "warehouse_transaction_payment_id" => $invoice->id,
                "warehouse_transaction_payment_warehouse_transaction_id" => $trx_id,
                "warehouse_transaction_payment_external_id" => $invoice->external_id,
                "warehouse_transaction_payment_external_id" => $invoice->external_id,
                "warehouse_transaction_payment_user_id" => $invoice->user_id,
                "warehouse_transaction_payment_status" => $invoice->status,
                "warehouse_transaction_payment_amount" => $invoice->amount,
                "warehouse_transaction_payment_invoice_url" => $invoice->invoice_url,
                "warehouse_transaction_payment_expired_datetime" => $invoice->expiry_date,
                "warehouse_transaction_payment_created_datetime" => $invoice->created,
                "warehouse_transaction_payment_updated_datetime" => $invoice->updated,
                "warehouse_transaction_payment_data" => json_encode($invoice),
            ];
        } else if ($type == "stockist" || $type == "master") {
            $table = "inv_stockist_transaction_payment";
            $arr_data = [
                "stockist_transaction_payment_id" => $invoice->id,
                "stockist_transaction_payment_stockist_transaction_id" => $trx_id,
                "stockist_transaction_payment_external_id" => $invoice->external_id,
                "stockist_transaction_payment_external_id" => $invoice->external_id,
                "stockist_transaction_payment_user_id" => $invoice->user_id,
                "stockist_transaction_payment_status" => $invoice->status,
                "stockist_transaction_payment_amount" => $invoice->amount,
                "stockist_transaction_payment_invoice_url" => $invoice->invoice_url,
                "stockist_transaction_payment_expired_datetime" => $invoice->expiry_date,
                "stockist_transaction_payment_created_datetime" => $invoice->created,
                "stockist_transaction_payment_updated_datetime" => $invoice->updated,
                "stockist_transaction_payment_data" => json_encode($invoice),
            ];
        } else {
            throw new \Exception("Tipe transaksi tidak diketahui", 1);
        }


        $this->db->table($table)->insert($arr_data);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah pembayaran.", 1);
        }

        return ["invoice_url" => $invoice->invoice_url];
    }

    public function paid($params)
    {
        $transaction_buyer_type = FALSE;
        $type = strpos($params->external_id, "warehouse") !== FALSE ? "warehouse" : "stockist";
        $hu = 0;
        $hu_price = [];

        if ($type == "warehouse") {
            $arr_data = [
                "warehouse_transaction_payment_method" => $params->payment_method,
                "warehouse_transaction_payment_channel" => $params->payment_channel,
                "warehouse_transaction_payment_destination" => isset($params->payment_destination) ? $params->payment_destination : "",
                "warehouse_transaction_payment_status" => $params->status,
                "warehouse_transaction_payment_updated_datetime" => $params->datetime,
                "warehouse_transaction_payment_paid_datetime" => $params->datetime,
                "warehouse_transaction_payment_data_callback" => json_encode($params),
            ];

            $this->db->table("inv_warehouse_transaction_payment")->update($arr_data, ["warehouse_transaction_payment_id" => $params->id]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah pembayaran.", 1);
            }
            $transaction = $this->db->table("inv_warehouse_transaction")->join("inv_warehouse_transaction_payment", "warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id")
                ->getWhere(["warehouse_transaction_payment_id" => $params->id])->getRow();

            $transaction_buyer_type = $transaction->warehouse_transaction_buyer_type;

            $transaction_detail = $this->db->table("inv_warehouse_transaction_detail")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $transaction->warehouse_transaction_id])->getResult();
            foreach ($transaction_detail as $detail) {
                $hu += $detail->warehouse_transaction_detail_quantity;
                for ($i = 0; $i < $detail->warehouse_transaction_detail_quantity; $i++) {
                    $hu_price[] = $detail->warehouse_transaction_detail_unit_price;
                }
            }
        } else if ($type == "stockist" || $type == "master") {
            $arr_data = [
                "stockist_transaction_payment_method" => $params->payment_method,
                "stockist_transaction_payment_channel" => $params->payment_channel,
                "stockist_transaction_payment_destination" => isset($params->payment_destination) ? $params->payment_destination : "",
                "stockist_transaction_payment_status" => $params->status,
                "stockist_transaction_payment_updated_datetime" => $params->datetime,
                "stockist_transaction_payment_paid_datetime" => $params->datetime,
                "stockist_transaction_payment_data_callback" => json_encode($params),
            ];

            $this->db->table("inv_stockist_transaction_payment")->update($arr_data, ["stockist_transaction_payment_id" => $params->id]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah pembayaran.", 1);
            }
            $transaction = $this->db->table("inv_stockist_transaction")->join("inv_stockist_transaction_payment", "stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id")
                ->getWhere(["stockist_transaction_payment_id" => $params->id])->getRow();

            $transaction_buyer_type = $transaction->stockist_transaction_buyer_type;

            $transaction_detail = $this->db->table("inv_stockist_transaction_detail")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $transaction->stockist_transaction_id])->getResult();
            foreach ($transaction_detail as $detail) {
                $hu += $detail->stockist_transaction_detail_quantity;
                for ($i = 0; $i < $detail->stockist_transaction_detail_quantity; $i++) {
                    $hu_price[] = $detail->stockist_transaction_detail_unit_price;
                }
            }
        } else {
            throw new \Exception("Tipe transaksi tidak diketahui", 1);
        }

        $member_id = $type == "warehouse" ? $transaction->warehouse_transaction_buyer_member_id : $transaction->stockist_transaction_buyer_member_id;

        return (object)[
            "type" => $type == "warehouse" ? "warehouse" : "stockist",
            "update" => $type == "warehouse" ? ["warehouse_transaction_status" => "paid", "warehouse_transaction_status_datetime" => $params->updated, 'warehouse_transaction_buyer_member_id' => $member_id] : ["stockist_transaction_status" => "paid", "stockist_transaction_status_datetime" => $params->updated, "stockist_transaction_buyer_member_id" => $member_id],
            "where" => $type == "warehouse" ? ["warehouse_transaction_id" => str_replace($type . "_", "", $params->external_id)] : ["stockist_transaction_id" => str_replace($type . "_", "", $params->external_id)],
            "transaction_id" => $type == "warehouse" ? $transaction->warehouse_transaction_payment_warehouse_transaction_id : $transaction->stockist_transaction_payment_stockist_transaction_id,
            "external_id" => $params->external_id,
            "buyer_member_id" => $member_id,
            "value" => $type == "warehouse" ? $transaction->warehouse_transaction_total_price : $transaction->stockist_transaction_total_price,
            "transaction_code" => $type == "warehouse" ? $transaction->warehouse_transaction_code : $transaction->stockist_transaction_code,
            "transaction_buyer_type" => $transaction_buyer_type,
            "hu" => $hu,
            "hu_price" => $hu_price,
        ];
    }

    public function expired($params)
    {
        $transaction_buyer_type = FALSE;
        $type = strpos($params->external_id, "warehouse") !== FALSE ? "warehouse" : "stockist";

        if ($type == "warehouse") {
            $arr_data = [
                "warehouse_transaction_payment_status" => $params->status,
                "warehouse_transaction_payment_updated_datetime" => $params->updated,
                "warehouse_transaction_payment_data_callback" => json_encode($params),
            ];

            $this->db->table("inv_warehouse_transaction_payment")->update($arr_data, ["warehouse_transaction_payment_id" => $params->id]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah pembayaran.", 1);
            }
            $transaction = $this->db->table("inv_warehouse_transaction")->join("inv_warehouse_transaction_payment", "warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id")
                ->getWhere(["warehouse_transaction_payment_id" => $params->id])->getRow();

            $transaction_buyer_type = $transaction->warehouse_transaction_buyer_type;
        } else if ($type == "stockist" || $type == "master") {
            $arr_data = [
                "stockist_transaction_payment_status" => $params->status,
                "stockist_transaction_payment_updated_datetime" => $params->updated,
                "stockist_transaction_payment_data_callback" => json_encode($params),
            ];

            $this->db->table("inv_stockist_transaction_payment")->update($arr_data, ["stockist_transaction_payment_id" => $params->id]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah pembayaran.", 1);
            }
            $transaction = $this->db->table("inv_stockist_transaction")->join("inv_stockist_transaction_payment", "stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id")
                ->getWhere(["stockist_transaction_payment_id" => $params->id])->getRow();

            $transaction_buyer_type = $transaction->stockist_transaction_buyer_type;
        } else {
            throw new \Exception("Tipe transaksi tidak diketahui", 1);
        }

        return (object)[
            "type" => $type,
            "update" => $type == "warehouse" ? ["warehouse_transaction_status" => "expired"] : ["stockist_transaction_status" => "expired"],
            "where" => $type == "warehouse" ? ["warehouse_transaction_id" => str_replace($type . "_", "", $params->external_id)] : ["stockist_transaction_id" => str_replace($type . "_", "", $params->external_id)],
            "transaction_id" => $type == "warehouse" ? $transaction->warehouse_transaction_payment_warehouse_transaction_id : $transaction->stockist_transaction_payment_stockist_transaction_id,
            "external_id" => $params->external_id,
            "buyer_member_id" => $type == "warehouse" ? $transaction->warehouse_transaction_buyer_member_id : $transaction->stockist_transaction_buyer_member_id,
            "member_id" => $type == "warehouse" ? $transaction->warehouse_transaction_warehouse_id : $transaction->stockist_transaction_stockist_member_id,
            "value" => $type == "warehouse" ? $transaction->warehouse_transaction_total_price : $transaction->stockist_transaction_total_price,
            "transaction_code" => $type == "warehouse" ? $transaction->warehouse_transaction_code : $transaction->stockist_transaction_code,
            "transaction_buyer_type" => $transaction_buyer_type,
        ];
    }

    public function createInvoice($params)
    {
        $createInvoice = \Xendit\Invoice::create($params);
        return $createInvoice;
    }

    public function getInvoice($id)
    {
        $getInvoice = \Xendit\Invoice::retrieve($id);
    }

    public function createDisbursementBatch(array $params)
    {
        $createDisbursementBatch = \Xendit\Disbursements::createBatch($params);
        $this->db->table("payment_disbursement")->update([
            "disbursement_response_json" => json_encode($createDisbursementBatch),
        ], [
            "disbursement_id" => $params["reference"],
        ]);
        return $createDisbursementBatch;
    }
}
