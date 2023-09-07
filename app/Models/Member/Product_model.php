<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;
use App\Libraries\Notification;

class Product_model extends Model
{
    public $request;

    public function init($request = null)
    {
        $this->request = $request;
    }

    public function getStock($id)
    {
        $tableName = "inv_member_product_stock";
        $columns = [
            "member_product_stock_id",
            "member_product_stock_member_id",
            "member_product_stock_product_id",
            "member_product_stock_balance",
            "product_id",
            "product_name",
            "product_code",
            "product_image",
            "product_bv",
        ];
        $joinTable = " JOIN inv_product ON product_id = member_product_stock_product_id";
        $whereCondition = " member_product_stock_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value['product_image'] != '' && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $value['product_image'])) {
                $arr_data["results"][$key]['product_image'] = UPLOAD_URL . URL_IMG_PRODUCT . $value['product_image'];
            } else {
                $arr_data["results"][$key]['product_image'] = UPLOAD_URL . URL_IMG_PRODUCT . "_default.png";
            }
        }

        return $arr_data;
    }

    public function getStockLog($id)
    {
        $tableName = "inv_member_product_stock_log";
        $columns = [
            "member_product_stock_log_id",
            "member_product_stock_log_member_id",
            "member_product_stock_log_product_id",
            "member_product_stock_log_type",
            "member_product_stock_log_quantity",
            "member_product_stock_log_unit_price",
            "member_product_stock_log_balance",
            "member_product_stock_log_note",
            "member_product_stock_log_datetime",
            "product_name",
        ];
        $joinTable = " JOIN inv_product ON product_id = member_product_stock_log_product_id";
        $whereCondition = " member_product_stock_log_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["member_product_stock_log_datetime_formatted"] = convertDatetime($value["member_product_stock_log_datetime"], "id");
        }

        return $arr_data;
    }

    public function getExchangeLog($id)
    {
        $tableName = "inv_member_serial_exchange";
        $columns = [
            "member_serial_exchange_id",
            "member_serial_exchange_member_id",
            "member_serial_exchange_serial_type_ref",
            "member_serial_exchange_serial_type_ref_id",
            "member_serial_exchange_serial_type_ref_name",
            "member_serial_exchange_serial_type_ref_bv",
            "member_serial_exchange_quantity",
            "member_serial_exchange_datetime",
        ];

        $joinTable = "";
        $whereCondition = " member_serial_exchange_member_id = {$id}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            $arr_data["results"][$key]["member_serial_exchange_datetime_formatted"] = convertDatetime($value["member_serial_exchange_datetime"], "id");
            $arr_data["results"][$key]["detail"] = $this->db->table("inv_member_serial_exchange_detail")->getWhere(["member_serial_exchange_detail_member_serial_exchange_id" => $value["member_serial_exchange_id"]])->getResult();
        }

        return $arr_data;
    }

    public function getTypeSerial()
    {
        $sql = "
        SELECT serial_type_id,
        serial_type_name,
        serial_type_bv,
        serial_type_image_filename,
        'activation' AS serial_type
        FROM sys_serial_type
        ";
        $serial_type = $this->db->query($sql)->getResult("array");
        
        $sql = "
        SELECT serial_ro_type_id AS serial_type_id,
        serial_ro_type_name AS serial_type_name,
        serial_ro_type_bv AS serial_type_bv,
        serial_ro_type_image_filename AS serial_type_image_filename,
        'repeatorder' AS serial_type
        FROM sys_serial_ro_type
        ";
        $serial_ro_type = $this->db->query($sql)->getResult("array");
        $arr_data["results"] = array_merge((array)$serial_type, (array)$serial_ro_type);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value['serial_type_image_filename'] != '' && file_exists(FCPATH . $value['serial_type_image_filename'])) {
                $arr_data["results"][$key]['serial_type_image_filename'] = BASEURL . "{$value['serial_type_image_filename']}";
            } else {
                $arr_data["results"][$key]['serial_type_image_filename'] = BASEURL . "/app-assets/images/package/_default.png";
            }
        }

        return $arr_data;
    }

    //ambil data produk untuk penukaran serial
    public function getProduct($id)
    {
        $sql = "
        SELECT *
        FROM inv_product
        WHERE product_id = '{$id}'
        ";

        return $this->db->query($sql)->getRow();
    }

    //ambil data tipe serial untuk penukaran serial
    public function getSerialType($type, $id)
    {
        if ($type == "activation") {
            $sql = "
            SELECT
            serial_type_id,
            serial_type_name,
            serial_type_bv
            FROM sys_serial_type
            WHERE serial_type_id = '{$id}'
            ";
        } else if ($type == "repeatorder") {
            $sql = "
            SELECT
            serial_ro_type_id AS serial_type_id,
            serial_ro_type_name AS serial_type_name,
            serial_ro_type_bv AS serial_type_bv
            FROM sys_serial_ro_type
            WHERE serial_ro_type_id = '{$id}'
            ";
        } else {
            throw new \Exception("Tipe tidak ditemukan.", 1);
        }

        return $this->db->query($sql)->getRow();
    }

    public function exchangeSerial($arr_data, $arr_item, $arr_serial, $arr_stock_log)
    {
        $this->db->table("inv_member_serial_exchange")->insert($arr_data);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah data penukaran serial.", 1);
        }
        $exchange_id = $this->db->insertID();

        foreach ($arr_item as $item) {
            $item["member_serial_exchange_detail_member_serial_exchange_id"] = $exchange_id;

            $this->db->table("inv_member_serial_exchange_detail")->insert($item);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambah detail penukaran serial.", 1);
            }
        }

        foreach ($arr_stock_log as $stock_log) {
            $this->db->table("inv_member_product_stock_log")->insert($stock_log);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambah riwayat stok produk.", 1);
            }

            $this->db->table("inv_member_product_stock")->update([
                "member_product_stock_balance" => $stock_log["member_product_stock_log_balance"],
            ], [
                "member_product_stock_member_id" => $stock_log["member_product_stock_log_member_id"],
                "member_product_stock_product_id" => $stock_log["member_product_stock_log_product_id"],
            ]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah data stok produk.", 1);
            }
        }

        $arr_serial_wa = "";
        foreach ($arr_serial as $serial) {
            if ($serial["serial_type"] == "activation") {
                $this->db->table("log_serial_distribution")->insert([
                    "serial_distribution_serial_id" => $serial["serial_id"],
                    "serial_distribution_serial_type_id" => $serial["serial_serial_type_id"],
                    "serial_distribution_owner_status" => "member",
                    "serial_distribution_owner_ref_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_distribution_owner_datetime" => $arr_data["member_serial_exchange_datetime"],
                    "serial_distribution_note" => "Penukaran serial.",
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menyimpan riwayat distribusi serial", 1);
                }

                $this->db->table("sys_serial_member_stock")->insert([
                    "serial_member_stock_serial_id" => $serial["serial_id"],
                    "serial_member_stock_serial_pin" => $serial["serial_pin"],
                    "serial_member_stock_member_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_member_stock_serial_type_id" => $serial["serial_serial_type_id"],
                    "serial_member_stock_datetime" => $arr_data["member_serial_exchange_datetime"],
                    "serial_member_stock_is_expired" => 0,
                    "serial_member_stock_expired_date" => $serial["expired_datetime"],
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menyimpan serial member stok", 1);
                }

                $this->db->table("sys_serial")->update([
                    "serial_expired_date" => $serial["expired_datetime"],
                    "serial_is_active" => 1,
                    "serial_active_datetime" => $arr_data["member_serial_exchange_datetime"],
                    "serial_active_ref_type" => "member",
                    "serial_active_ref_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_last_owner_status" => "member",
                    "serial_last_owner_ref_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_last_owner_datetime" => $arr_data["member_serial_exchange_datetime"],
                ], ["serial_id" => $serial["serial_id"]]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal mengubah serial", 1);
                }
            } else if ($serial["serial_type"] == "repeatorder") {
                $this->db->table("log_serial_ro_distribution")->insert([
                    "serial_ro_distribution_serial_ro_id" => $serial["serial_id"],
                    "serial_ro_distribution_serial_ro_type_id" => $serial["serial_serial_type_id"],
                    "serial_ro_distribution_owner_status" => "member",
                    "serial_ro_distribution_owner_ref_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_ro_distribution_owner_datetime" => $arr_data["member_serial_exchange_datetime"],
                    "serial_ro_distribution_note" => "Penukaran serial.",
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menyimpan riwayat distribusi serial", 1);
                }

                $this->db->table("sys_serial_ro_member_stock")->insert([
                    "serial_ro_member_stock_serial_ro_id" => $serial["serial_id"],
                    "serial_ro_member_stock_serial_ro_pin" => $serial["serial_pin"],
                    "serial_ro_member_stock_member_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_ro_member_stock_serial_ro_type_id" => $serial["serial_serial_type_id"],
                    "serial_ro_member_stock_datetime" => $arr_data["member_serial_exchange_datetime"],
                    "serial_ro_member_stock_is_expired" => 0,
                    "serial_ro_member_stock_expired_date" => $serial["expired_datetime"],
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menyimpan serial member stok", 1);
                }

                $this->db->table("sys_serial_ro")->update([
                    "serial_ro_expired_date" => $serial["expired_datetime"],
                    "serial_ro_is_active" => 1,
                    "serial_ro_active_datetime" => $arr_data["member_serial_exchange_datetime"],
                    "serial_ro_active_ref_type" => "member",
                    "serial_ro_active_ref_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_ro_last_owner_status" => "member",
                    "serial_ro_last_owner_ref_id" => $arr_data["member_serial_exchange_member_id"],
                    "serial_ro_last_owner_datetime" => $arr_data["member_serial_exchange_datetime"],
                ], ["serial_ro_id" => $serial["serial_id"]]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal mengubah serial", 1);
                }
            } else {
                throw new \Exception("Tipe serial tidak ditemukan.", 1);
            }
            $arr_serial_wa .= "
*{$serial['serial_id']}*";
        }

        if(WA_NOTIFICATION_IS_ACTIVE) {
            $this->mlm_service = service('Mlm');
            $this->notification_lib = new Notification();

            $client_name = COMPANY_NAME;
            $client_wa_cs_number = WA_CS_NUMBER;
            $member_name = $this->mlm_service->get_member_name_by_member_id($arr_data["member_serial_exchange_member_id"]);
            $member_mobilephone = $this->mlm_service->get_member_mobilephone_by_member_id($arr_data["member_serial_exchange_member_id"]);

            $content = "*Penukaran Serial Berhasil*
Hai {$member_name},
Penukaran *{$arr_data['member_serial_exchange_quantity']}* serial *{$arr_data['member_serial_exchange_serial_type_ref_name']}* berhasil diproses.

Detail serial sebagai berikut:
{$arr_serial_wa}

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}
            ";

            $this->notification_lib->send_waone($content, phoneNumberFilter($member_mobilephone));
        }
    }
}