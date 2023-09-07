<?php

namespace App\Controllers\Partner;

use App\Controllers\BaseController;
use App\Libraries\Rest;
use App\Models\Common_model;
use App\Libraries\Notification;

class Callback extends BaseController
{
    public function __construct()
    {
        $this->common_model = new Common_model();
        $this->payment_service = service("Payment");
        $this->transaction_service = service("Transaction");
        $this->stock_service = service("Stock");
        $this->network_service = service("Network");
        $this->restLib = new Rest();
        $this->notification_lib = new Notification();
        $this->datetime = date("Y-m-d H:i:s");
    }

    public function validateToken()
    {
        try {
            if ($_SERVER["HTTP_X_CALLBACK_TOKEN"] !== PAYMENT_CALLBACK_TOKEN) {
                throw new \Exception("Token callback tidak sesuai.", 1);
            }
        } catch (\Exception $e) {
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function execute()
    {
        $this->validateToken();

        $this->db->transBegin();
        $arr_notif_sponsor = [];
        $arr_notif_member = [];
        $arr_notif_ro = [];

        try {
            $params = $this->request->getJSON();
            $params->datetime = $this->datetime;
            $params->date = $this->date;
            $status = $params->status;
            // parse tz to datetime
            $params->created = date("Y-m-d H:i:s", strtotime($params->created));
            $params->updated = date("Y-m-d H:i:s", strtotime($params->updated));

            if ($params->status == "PAID") {
                $payment = $this->payment_service->paid($params);
                $total_hu = $payment->hu;
                $hu_price = $payment->hu_price;
                $detail_trx = $this->transaction_service->paid($payment->type, $payment->update, $payment->where);

                if ($payment->transaction_buyer_type == "member") {
                    $clone = $payment->buyer_member_id == 0 ? FALSE : TRUE;
                    $is_clone = $payment->buyer_member_id != 0 ? FALSE : TRUE;
                    $j = 0;
                    for ($i = 0; $i < $total_hu; $i++) {
                        if (!$clone) {
                            $params = $this->db->table("sys_member_registration")
                                ->join("sys_member_account", "member_account_username = member_registration_sponsor_username")
                                ->getWhere(["member_registration_transaction_type" => $payment->type, "member_registration_transaction_id" => $payment->transaction_id])
                                ->getRow();

                            // $params = $this->db->table("sys_member_registration")->getWhere(["member_registration_id" => $member_registration->member_registration_id])->getRow();
                            $params->network_code = $this->network_service->get_network_code();
                            $params->parent_member_id = 0;

                            $registration = $this->registration($params);
                            $params->member_id = $registration->member_id;

                            $this->db->table("sys_member_registration")->update([
                                "member_id" => $registration->member_id,
                                "member_registration_username" => $params->network_code,
                            ], ["member_registration_id" => $params->member_registration_id]);
                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal ubah member registration", 1);
                            }
                            $params->transaction_code = $payment->transaction_code;

                            // cek frontline 5
                            $sql = "SELECT *
                            FROM sys_netgrow_node
                            WHERE netgrow_node_member_id = {$params->member_account_member_id}
                            AND netgrow_node_level = 1";
                            $downline = $this->db->query($sql)->getResult();

                            if (count($downline) < 5) {
                                $activation_service = service("Activation");
                                $activation_service->execute($params, $hu_price[$i], "activation");

                                // $this->db->table("sys_member_registration")->update([
                                //     "member_is_network" => 1,
                                // ], ["member_registration_id" => $params->member_registration_id]);
                                // if ($this->db->affectedRows() <= 0) {
                                //     throw new \Exception("Gagal ubah member registration", 1);
                                // }
                            }
                            $clone = TRUE;
                            $payment->buyer_member_id = $params->member_id;

                            $mobilephone_sponsor = $this->db->table("sys_member")
                                ->select("member_mobilephone")
                                ->getWhere(["member_id" => $params->member_account_member_id])
                                ->getRow('member_mobilephone');

                            $arr_notif_sponsor = [
                                'network_code' => $params->network_code,
                                'name' => $params->member_name,
                                'mobilephone' => $mobilephone_sponsor
                            ];

                            $arr_notif_member = [
                                'name' => $registration->member_name,
                                'mobilephone' => $registration->member_mobilephone,
                                'network_code' => $registration->member_network_code
                            ];

                            $arr_notif_member['detail_trx'] = $detail_trx;
                        } else {
                            $j += 1;
                            if ($this->db->table("sys_member")->getWhere(["member_id" => $payment->buyer_member_id])->getRow("member_is_network") == "0") {
                                break;
                            }

                            $params = $this->db->table("sys_member")->join("sys_member_account", "member_account_member_id = member_id")->join("sys_network", "network_member_id = member_id")->getWhere(["member_id" => $payment->buyer_member_id])->getRow();
                            $member_count = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $params->member_id])->getResult();
                            $suffix = count($member_count) + 1;
                            $params->member_registration_sponsor_username = $params->network_code;
                            $params->network_code = "{$params->network_code}-{$suffix}";
                            $params->parent_member_id = $params->member_id;

                            $registration = $this->registration($params);

                            $params->member_id = $registration->member_id;
                            $params->member_network_slug = $params->network_slug;
                            $params->transaction_code = $payment->transaction_code;

                            $activation_service = service("Activation");
                            $activation_service->execute($params, $hu_price[$i], "repeatorder");

                            if ($j == 1 && $is_clone) {
                                $arr_notif_ro = [
                                    'name' => $registration->member_name,
                                    'mobilephone' => $registration->member_mobilephone,
                                    'detail_trx' => $detail_trx
                                ];
                            }
                        }
                    }
                } else if ($payment->transaction_buyer_type == "stockist" || $payment->transaction_buyer_type == "master") {
                } else {
                    throw new \Exception("Buyer tipe tidak diketahui.", 1);
                }
            } else if ($params->status == "EXPIRED") {
                $payment = $this->payment_service->expired($params);
                $this->transaction_service->expired($payment->type, $payment->update, $payment->where, $payment->where);

                if ($payment->type == "stockist") {
                    $detail_trx_stockist = $this->db->table("inv_stockist_transaction_detail")->select("stockist_transaction_detail_product_id, stockist_transaction_detail_quantity, stockist_transaction_detail_unit_price")->getWhere(["stockist_transaction_detail_stockist_transaction_id" => $payment->transaction_id])->getResult();

                    if (!empty($detail_trx_stockist)) {
                        foreach ($detail_trx_stockist as $key => $value) {
                            $this->stock_service->addStockStockist((object)[
                                'member_id' => $payment->member_id,
                                'product_id' => $value->stockist_transaction_detail_product_id,
                                'quantity' => $value->stockist_transaction_detail_quantity,
                                'unit_price' => $value->stockist_transaction_detail_unit_price,
                                'note' => 'Pengembalian Stok dari transaksi Expired ' . $payment->transaction_code,
                                'datetime' => date("Y-m-d H:i:s")
                            ]);
                        }
                    }
                } elseif ($payment->type == 'warehouse') {
                    $detail_trx_warehouse = $this->db->table("inv_warehouse_transaction_detail")->select("warehouse_transaction_detail_product_id, warehouse_transaction_detail_unit_price, warehouse_transaction_detail_quantity")->getWhere(["warehouse_transaction_detail_warehouse_transaction_id" => $payment->transaction_id])->getResult();

                    if (!empty($detail_trx_warehouse)) {
                        foreach ($detail_trx_warehouse as $key => $value) {
                            $this->stock_service->addStockWarehouse((object)[
                                'member_id' => $payment->member_id,
                                'product_id' => $value->warehouse_transaction_detail_product_id,
                                'quantity' => $value->warehouse_transaction_detail_quantity,
                                'unit_price' => $value->warehouse_transaction_detail_unit_price,
                                'note' => 'Pengembalian Stok dari transaksi Expired ' . $payment->transaction_code,
                                'datetime' => date("Y-m-d H:i:s")
                            ]);
                        }
                    }
                }
            } else {
                throw new \Exception("Status tidak diketahui.", 1);
            }

            if ($payment->type == 'warehouse') {
                $transaction_status_data = [
                    "warehouse_transaction_status_warehouse_transaction_id" => $payment->transaction_id,
                    "warehouse_transaction_status_value" => $status,
                    "warehouse_transaction_status_datetime" => $this->datetime,
                    "warehouse_transaction_status_ref_type" => "system",
                    "warehouse_transaction_status_ref_id" => $payment->buyer_member_id,
                ];

                $this->db->table("inv_warehouse_transaction_status")->insert($transaction_status_data);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal ubah status", 1);
                }

                $buyer_member_id = $this->db->query("SELECT * FROM inv_warehouse_transaction WHERE warehouse_transaction_id = '{$payment->transaction_id}' AND warehouse_transaction_buyer_member_id = '0'")->getRow("warehouse_transaction_buyer_member_id");
                if (!is_null($buyer_member_id) && $buyer_member_id != $payment->buyer_member_id) {
                    $this->db->query("UPDATE inv_warehouse_transaction SET warehouse_transaction_buyer_member_id = '{$payment->buyer_member_id}' WHERE warehouse_transaction_id = '{$payment->transaction_id}'");
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal ubah pembeli", 1);
                    }
                }
            } else if ($payment->type == "stockist") {
                $transaction_status_data = [
                    "stockist_transaction_status_stockist_transaction_id" => $payment->transaction_id,
                    "stockist_transaction_status_value" => $status,
                    "stockist_transaction_status_datetime" => $this->datetime,
                    "stockist_transaction_status_ref_type" => "system",
                    "stockist_transaction_status_ref_id" => $payment->buyer_member_id,
                ];

                $this->db->table("inv_stockist_transaction_status")->insert($transaction_status_data);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal ubah status", 1);
                }

                $buyer_member_id = $this->db->query("SELECT * FROM inv_stockist_transaction WHERE stockist_transaction_id = '{$payment->transaction_id}' AND stockist_transaction_buyer_member_id = '0'")->getRow("stockist_transaction_buyer_member_id");
                if (!is_null($buyer_member_id) && $buyer_member_id != $payment->buyer_member_id) {
                    $this->db->query("UPDATE inv_stockist_transaction SET stockist_transaction_buyer_member_id = '{$payment->buyer_member_id}' WHERE stockist_transaction_id = '{$payment->transaction_id}'");
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal ubah pembeli", 1);
                    }
                }
            }

            $this->db->transCommit();

            $client_url = URL_PRODUCTION;
            $client_name = COMPANY_NAME;
            if (!empty($arr_notif_sponsor) && WA_NOTIFICATION_IS_ACTIVE) {
                $content = "*Penambahan Mitra Baru Berhasil!* 
Selamat! Anda berhasil mensponsori mitra baru dengan ID mitra *{$arr_notif_sponsor['network_code']}* atas nama *{$arr_notif_sponsor['name']}*.

Ket: Jika Anda sudah mensponsori lebih dari 5 mitra baru segera login ke Virtual Office Anda untuk melakukan placement/penempatan mitra dalam waktu kurang dari 15 menit dari sekarang.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";

                $this->notification_lib->send_waone($content, phoneNumberFilter($arr_notif_sponsor['mobilephone']));
            }

            if (!empty($arr_notif_member) && WA_NOTIFICATION_IS_ACTIVE) {
                $trx = '';
                foreach ($arr_notif_member['detail_trx'] as $key => $value) {
                    $trx .= "
{$value->product_name} - {$value->quantity}";
                }

                $content_member = "Pembelian Produk Berhasil!
Bapak/Ibu *{$arr_notif_member['name']}* Terima kasih telah melakukan pembayaran atas pembelanjaan produk perdana di {$client_name}.

Rincian belanja Anda:
{$trx}

Selamat Anda telah bergabung dengan PT. Kimstella Network Sejahtera. Silahkan akses Virtual Office Anda di:
www.kimstella.co.id/login

ID Mitra : *{$arr_notif_member['network_code']}*

Terima kasih atas kepercayaan anda bersama kami.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";

                $this->notification_lib->send_waone($content_member, phoneNumberFilter($arr_notif_member['mobilephone']));
            }

            if (!empty($arr_notif_ro) && WA_NOTIFICATION_IS_ACTIVE) {
                $trx = '';
                foreach ($arr_notif_ro['detail_trx'] as $key => $value) {
                    $trx .= "
{$value->product_name} - {$value->quantity}";
                }

                $content_ro = "*Pembelian Produk Berhasil!*
Bapak/Ibu *{$arr_notif_ro['name']}* Terima kasih telah melakukan pembayaran atas pembelanjaan produk di {$client_name}.

Rincian belanja Anda:
{$trx}

Terima kasih atas kepercayaan anda bersama kami.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";

                $this->notification_lib->send_waone($content_ro, phoneNumberFilter($arr_notif_ro['mobilephone']));
            }

            $this->restLib->responseSuccess("Pembayaran berhasil diproses.", ["results" => $params]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function registration($params)
    {
        $arr_data = [];
        // $arr_data['member']['member_parent_member_id'] = $params->parent_member_id; //jika 1 grup hu maka isikan parent_id
        $arr_data['member']['member_parent_member_id'] = $params->parent_member_id;
        $arr_data['member']['member_name'] = $params->member_name;
        $arr_data['member']['member_email'] = $params->member_email;
        $arr_data['member']['member_mobilephone'] = phoneNumberFilter($params->member_mobilephone);
        $arr_data['member']['member_gender'] = $params->member_gender;
        $arr_data['member']['member_birth_place'] = $params->member_birth_place;
        $arr_data['member']['member_birth_date'] = $params->member_birth_date;
        $arr_data['member']['member_address'] = $params->member_address;
        $arr_data['member']['member_subdistrict_id'] = $params->member_subdistrict_id;
        $arr_data['member']['member_city_id'] = $params->member_city_id;
        $arr_data['member']['member_province_id'] = $params->member_province_id;
        $arr_data['member']['member_bank_id'] = $params->member_bank_id;
        $arr_data['member']['member_bank_name'] = $this->common_model->GetOne('ref_bank', 'bank_name', ['bank_id' => $params->member_bank_id]);
        $arr_data['member']['member_bank_account_name'] = $params->member_bank_account_name;
        $arr_data['member']['member_bank_account_no'] = $params->member_bank_account_no;
        $arr_data['member']['member_bank_branch'] = property_exists($params, 'member_bank_branch') ? $params->member_bank_branch : '';
        $arr_data['member']['member_identity_type'] = $params->member_identity_type;
        // $arr_data['member']['member_identity_type'] = property_exists($params, 'member_identity_type') ? $params->member_identity_type : 'KTP';
        $arr_data['member']['member_identity_no'] = $params->member_identity_no;
        $arr_data['member']['member_identity_image'] = property_exists($params, 'member_identity_image') ? $params->member_identity_image : '';
        $arr_data['member']['member_join_datetime'] = isset($params->datetime) ? $params->datetime : $this->datetime;
        $arr_data['member']['member_job'] = property_exists($params, 'member_job') ? $params->member_job : '';
        $arr_data['member']['member_is_network'] = '0';

        // $arr_data['account']['member_account_username'] = $params->member_account_username;
        $arr_data['account']['member_account_username'] = $params->network_code;
        // $arr_data['account']['member_account_password'] = $params->member_account_password;
        $arr_data['account']['member_account_password'] = isset($params->member_registration_password) ? $params->member_registration_password : $params->member_account_password;
        $arr_data['account']['member_account_pin'] = generateNumber(6);

        $this->membership_service = service("Membership");
        $member_id = $this->membership_service->save_member($arr_data); //mendapatkan ID member

        return (object)[
            "member_id" => $member_id,
            'member_mobilephone' => phoneNumberFilter($params->member_mobilephone),
            'member_name' => $params->member_name,
            'member_network_code' => $params->network_code
        ];
    }

    public function disbursement()
    {
        $this->validateToken();

        $this->db->transBegin();

        $arr_notif_member = [];
        try {
            $datetime = date("Y-m-d H:i:s");
            $date = date("Y-m-d", strtotime($datetime));
            $params = $this->request->getJSON();
            $disbursement = $this->db->table("payment_disbursement")->getWhere(["disbursement_external_id" => $params->id])->getRow();

            // validasi sudah sukses / check
            if ($disbursement->disbursement_status == "CHECK" || $disbursement->disbursement_status == "COMPLETED") {
                throw new \Exception("Disbursement status {$disbursement->disbursement_status}", 1);
            }

            if ($disbursement) {
                $update = [
                    "disbursement_total_disbursed_count" => $params->total_disbursed_count,
                    "disbursement_total_disbursed_amount" => $params->total_disbursed_amount,
                    "disbursement_total_error_count" => $params->total_error_count,
                    "disbursement_total_error_amount" => $params->total_error_amount,
                    "disbursement_updated" => $params->updated,
                    "disbursement_approved_at" => $params->approved_at,
                    "disbursement_approved_id" => $params->approver_id,
                    "disbursement_status" => $params->status,
                    "disbursement_callback_request_json" => json_encode($params),
                    "disbursement_callback_response_json" => json_encode($params),
                ];
                $where = ["disbursement_external_id" => $params->id];
                $this->db->table("payment_disbursement")->update($update, $where);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update disbursement", 1);
                }

                if ($params->status == "DELETED") {
                    $params->disbursements = $this->db->query("SELECT * FROM payment_disbursement_detail WHERE disbursement_detail_disbursement_id = '{$disbursement->disbursement_id}'")->getResult();
                    foreach ($params->disbursements as $_key_disbursement => $_disbursement) {
                        $params->disbursements[$_key_disbursement]->updated = date('Y-m-d H:i:s', strtotime($params->updated));
                        $params->disbursements[$_key_disbursement]->status = $params->status;
                        $params->disbursements[$_key_disbursement]->valid_name = "";
                        $params->disbursements[$_key_disbursement]->bank_reference = "";
                        $params->disbursements[$_key_disbursement]->failure_code = "DELETED_BY_CLIENT";
                        $params->disbursements[$_key_disbursement]->failure_message = NULL;
                        $params->disbursements[$_key_disbursement]->external_id = $_disbursement->disbursement_detail_id;
                    }
                }

                foreach ($params->disbursements as $detail) {
                    $update = [
                        "disbursement_detail_updated" => $detail->updated,
                        "disbursement_detail_status" => $detail->status,
                        "disbursement_detail_valid_name" => $detail->valid_name,
                        "disbursement_detail_bank_reference" => $detail->bank_reference,
                        "disbursement_detail_failure_code" => isset($detail->failure_code) ? $detail->failure_code : NULL,
                        "disbursement_detail_failure_message" => isset($detail->failure_message) ? $detail->failure_message : NULL,
                    ];
                    $where = ["disbursement_detail_id" => $detail->external_id];
                    $this->db->table("payment_disbursement_detail")->update($update, $where);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update detail disbursement", 1);
                    }

                    $disbursement_detail = $this->db->table("payment_disbursement_detail")->getWhere($where)->getRow();

                    if ($disbursement->disbursement_type_disbursement == 'commission') {
                        $update = [
                            "bonus_transfer_status" => $detail->status == "COMPLETED" ? "success" : "failed",
                            "bonus_transfer_status_datetime" => $datetime,
                        ];
                        $where = ["bonus_transfer_id" => $disbursement_detail->disbursement_detail_internal_id];
                        $this->db->table("sys_bonus_transfer")->update($update, $where);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update bonus transfer", 1);
                        }

                        $bonus_transfer_id = $this->db->table("payment_disbursement_detail")->getWhere(["disbursement_detail_id" => $detail->external_id])->getRow("disbursement_detail_internal_id");
                        $bonus_transfer = $this->db->table("sys_bonus_transfer")->select("sys_bonus_transfer.*")->join('sys_member', 'member_id = bonus_transfer_member_id')->getWhere(["bonus_transfer_id" => $bonus_transfer_id])->getRowArray();
                        if ($detail->status == "FAILED" || $detail->status == "DELETED") {
                            $this->db->table("sys_bonus_transfer_failed")->insert($bonus_transfer);
                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal menambah bonus transfer failed", 1);
                            }

                            $this->db->table("sys_bonus_transfer")->delete(["bonus_transfer_id" => $bonus_transfer_id]);
                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal hapus bonus transfer", 1);
                            }

                            $sql = "UPDATE sys_bonus SET
                            bonus_sponsor_paid = bonus_sponsor_paid - {$bonus_transfer['bonus_transfer_sponsor']},
                            bonus_gen_node_paid = bonus_gen_node_paid - {$bonus_transfer['bonus_transfer_gen_node']},
                            bonus_power_leg_paid = bonus_power_leg_paid - {$bonus_transfer['bonus_transfer_power_leg']},
                            bonus_matching_leg_paid = bonus_matching_leg_paid - {$bonus_transfer['bonus_transfer_matching_leg']},
                            bonus_cash_reward_paid = bonus_cash_reward_paid - {$bonus_transfer['bonus_transfer_cash_reward']}
                            WHERE bonus_member_id = {$bonus_transfer['bonus_transfer_member_id']}
                            ";
                            $this->db->query($sql);
                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal update bonus", 1);
                            }
                        }
                    } elseif ($disbursement->disbursement_type_disbursement == 'stockist') {
                        $update = [
                            "stockist_transfer_status" => $detail->status == "COMPLETED" ? "success" : "failed",
                            "stockist_transfer_status_datetime" => $datetime,
                        ];
                        $where = ["stockist_transfer_id" => $disbursement_detail->disbursement_detail_internal_id];
                        $this->db->table("sys_stockist_transfer")->update($update, $where);
                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update bonus transfer", 1);
                        }

                        $stockist_transfer_id = $this->db->table("payment_disbursement_detail")->getWhere(["disbursement_detail_id" => $detail->external_id])->getRow("disbursement_detail_internal_id");
                        $stockist_transfer = $this->db->table("sys_stockist_transfer")->getWhere(["stockist_transfer_id" => $stockist_transfer_id])->getRowArray();
                        $ewallet_withdrawal = $this->db->table("sys_ewallet_withdrawal")->getWhere(["ewallet_withdrawal_id" => $stockist_transfer['stockist_transfer_ewallet_withdrawal_id']])->getRow();

                        $this->db->table("sys_ewallet_withdrawal")
                            ->where("ewallet_withdrawal_id", $stockist_transfer['stockist_transfer_ewallet_withdrawal_id'])
                            ->update([
                                'ewallet_withdrawal_status' => $detail->status == 'COMPLETED' ? 'success' : 'failed',
                                'ewallet_withdrawal_status_datetime' => date("Y-m-d H:i:s")
                            ]);

                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal ubah status withdrawal", 1);
                        }

                        if ($detail->status == "FAILED" || $detail->status == "DELETED") {
                            $this->db->table("sys_stockist_transfer_failed")->insert($stockist_transfer);
                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal menambah stokis transfer failed", 1);
                            }

                            $this->db->table("sys_stockist_transfer")->delete(["stockist_transfer_id" => $stockist_transfer_id]);
                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal hapus stokis transfer", 1);
                            }

                            $sql_balance = "
                            UPDATE sys_ewallet
                            SET ewallet_acc = ewallet_acc + $ewallet_withdrawal->ewallet_withdrawal_value
                            WHERE ewallet_member_id = '$ewallet_withdrawal->ewallet_withdrawal_member_id'
                            ";

                            $this->db->query($sql_balance);

                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal ubah data saldo", 1);
                            }

                            $ewallet_log = [
                                'ewallet_log_member_id' => $ewallet_withdrawal->ewallet_withdrawal_member_id,
                                'ewallet_log_value' => $ewallet_withdrawal->ewallet_withdrawal_value,
                                'ewallet_log_type' => 'in',
                                'ewallet_log_note' => 'transfer saldo gagal',
                                'ewallet_log_datetime' => date("Y-m-d H:i:s")
                            ];

                            $this->db->table('sys_ewallet_log')->insert($ewallet_log);

                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal tambah log ewallet.", 4);
                            }
                        }
                    } elseif ($disbursement->disbursement_type_disbursement == 'investor') {
                        $status = $detail->status == "COMPLETED" ? "approve" : "rejected";
                        $where = ["investor_withdrawal_id" => $disbursement_detail->disbursement_detail_internal_id];

                        $findTransferInvestorWithdrawal = $this->db->table("report_investor_withdrawal")->getWhere($where)->getRow();

                        if (!$findTransferInvestorWithdrawal) {
                            throw new \Exception("Investor Withdrawal tidak ditemukan", 1);
                        }

                        $update = [
                            "investor_withdrawal_status" => $status,
                        ];

                        $this->db->table("report_investor_withdrawal")->update($update, $where);

                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal Update Withdrawal", 1);
                        }

                        $this->db->table('report_investor')
                            ->set('investor_pending_payment', 'investor_pending_payment - ' . $findTransferInvestorWithdrawal->investor_withdrawal_value, false)
                            ->where('investor_id', $findTransferInvestorWithdrawal->investor_withdrawal_investor_id)
                            ->update();

                        if ($this->db->affectedRows() <= 0) {
                            throw new \Exception("Gagal update pending payment report investor", 1);
                        }

                        if ($status == 'approve') {
                            $this->db->table('report_investor')
                                ->set('investor_paid', 'investor_paid + ' . $findTransferInvestorWithdrawal->investor_withdrawal_value, false)
                                ->where('investor_id', $findTransferInvestorWithdrawal->investor_withdrawal_investor_id)
                                ->update();

                            if ($this->db->affectedRows() <= 0) {
                                throw new \Exception("Gagal update paid report investor", 1);
                            }
                        }
                    } else {
                        throw new \Exception("Tipe transfer tidak ditemukan", 1);
                    }

                    if ($detail->status == "COMPLETED" && $disbursement->disbursement_type_disbursement == 'commission') {
                        $member = $this->db->table("sys_member")
                            ->select("member_mobilephone, network_code")
                            ->join('sys_bonus_transfer', 'member_id = bonus_transfer_member_id')
                            ->join('sys_network', 'network_member_id = member_id')
                            ->getWhere($where)
                            ->getRow();
                        array_push($arr_notif_member, [
                            'mobilephone' => $member->member_mobilephone,
                            'network_code' => $member->network_code,
                            'nominal' => setNumberFormat($bonus_transfer['bonus_transfer_total']),
                            'charge' => setNumberFormat($bonus_transfer['bonus_transfer_adm_charge_value']),
                            'tax' => setNumberFormat($bonus_transfer['bonus_transfer_tax_value']),
                            'sponsor' => setNumberFormat($bonus_transfer['bonus_transfer_sponsor']),
                            'gen_node' => setNumberFormat($bonus_transfer['bonus_transfer_gen_node']),
                            'power_leg' => setNumberFormat($bonus_transfer['bonus_transfer_power_leg']),
                            'matching_leg' => setNumberFormat($bonus_transfer['bonus_transfer_matching_leg']),
                            'cash_reward' => setNumberFormat($bonus_transfer['bonus_transfer_cash_reward']),
                        ]);
                    } elseif ($detail->status == "COMPLETED" && $disbursement->disbursement_type_disbursement == 'stockist') {
                        $member = $this->db->table("sys_member")
                            ->select("member_name, member_mobilephone, network_code")
                            ->join('sys_stockist_transfer', 'member_id = stockist_transfer_member_id')
                            ->join('sys_network', 'network_member_id = member_id')
                            ->getWhere($where)
                            ->getRow();
                        array_push($arr_notif_member, [
                            'name' => $member->member_name,
                            'mobilephone' => $member->member_mobilephone,
                            'nominal' => setNumberFormat($stockist_transfer['stockist_transfer_total']),
                            'date' => convertDatetime(date("Y-m-d H:i:s"))
                        ]);
                    }
                }
            } else {
                throw new \Exception("ID {$params->id} tidak ditemukan", 1);
            }

            $this->db->transCommit();

            $client_url = URL_PRODUCTION;
            $client_name = COMPANY_NAME;
            if (!empty($arr_notif_member) && WA_NOTIFICATION_IS_ACTIVE) {
                foreach ($arr_notif_member as $key => $value) {
                    if ($disbursement->disbursement_type_disbursement == 'commission') {
                        $content = "*Transfer Komisi Berhasil!*
Selamat! Komisi Anda *{$value['network_code']}* hari ini telah ditransfer dari {$client_name} sebesar *Rp {$value['nominal']}*

Detail komisi sebagai berikut:

Sponsor : {$value['sponsor']}
Generasi : {$value['gen_node']}
Power Leg : {$value['power_leg']}
Matching Leg : {$value['matching_leg']}
Cash Reward : {$value['cash_reward']}

Potongan Admin : {$value['charge']}
Komisi Diterima : {$value['nominal']}

Terima kasih atas kepercayaan anda bersama kami.

Tingkatkan komisi Anda dengan sering bercerita ke banyak orang tentang manfaat produk dari {$client_name}.

*Kimstella* 
_Semua bisa sukses!_
{$client_url}

--------------------------
NB: Berdasarkan peraturan Pajak Penghasilan pasal 21, mitra dihimbau untuk melaporkan pendapatan komisi dan membayarkan pajak sesuai dengan peraturan yang berlaku.
";
                    } elseif ($disbursement->disbursement_type_disbursement == 'stockist') {
                        $content = "Halo, *{$value['member_name']}*
Selamat Yah... Penarikan Saldo Kamu Senilai *{$value['nominal']}* hari ini {$value['date']} Berhasil diproses dan Sudah Terkirim ke Rekening Bank Kamu, Buruan Dicheck Yah...

Yuk! Tingkatkan Terus Omsetnya Untuk Mendapatkan Income Lebih Banyak Lagi. Semangat!

*Kimstella* 
_SemuaBisaSukses!_";
                    }

                    $this->notification_lib->send_waone($content, phoneNumberFilter($value['mobilephone']));
                }
            }

            $this->restLib->responseSuccess("Disbursement berhasil diproses.", ["results" => $params]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }
}
