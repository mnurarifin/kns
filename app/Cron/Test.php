<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Test extends BaseController
{
    public function xendit_invoice()
    {
        $service = service("Payment");
        $params = [
            "external_id" => "dev_{$trx_id}",
            "payer_email" => "sample_email@xendit.co",
            "description" => "Trip to Bali",
            "amount" => 32000
        ];
        $invoice = (object)$service->createInvoice($params);

        if ($type == "warehouse") {
            $table = "inv_warehouse_transaction_payment";
            $arr_data = [
                "warehouse_transaction_payment_external_id" => $invoice->external_id,
            ];
        }

        $this->db->table($table)->insert($arr_data);
        if ($this->db->insertedRows() <= 0) {
            throw new \Exception("Gagal menambah pembayaran.", 1);
        }
    }

    public function xendit_pay()
    {
        $params = "
            \"id\": \"63bb9a37cae6e377f1fea5a4\",
            \"amount\": 32000,
            \"status\": \"PAID\",
            \"created\": \"2023-01-09T04:38:17.036Z\",
            \"is_high\": false,
            \"paid_at\": \"2023-01-08T21:43:41.000Z\",
            \"updated\": \"2023-01-09T04:43:45.867Z\",
            \"user_id\": \"639d4b5f9728ad59f99bcb94\",
            \"currency\": \"IDR\",
            \"bank_code\": \"BCA\",
            \"payment_id\": \"c9fdabfb-3678-4052-899e-698f353f728d\",
            \"description\": \"Trip to Bali\",
            \"external_id\": \"demo_147580196270\",
            \"paid_amount\": 32000,
            \"payer_email\": \"sample_email@xendit.co\",
            \"merchant_name\": \"Kimstella\",
            \"payment_method\": \"BANK_TRANSFER\",
            \"payment_channel\": \"BCA\",
            \"payment_destination\": \"3816510003597\"
        }";
    }

    public function test()
    {
        $this->db = \Config\Database::connect();
        $bonus_transfer = $this->db->table("sys_bonus_transfer")->select("sys_bonus_transfer.*")->join('sys_member', 'member_id = bonus_transfer_member_id')->getWhere(["bonus_transfer_id" => 49])->getRowArray();
        $this->db->table("sys_bonus_transfer_failed")->insert($bonus_transfer);
    }
}
