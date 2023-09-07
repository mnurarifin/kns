<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Stock_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function substractStock($data)
    {
        $sql = "
        UPDATE inv_warehouse_product_stock
        SET warehouse_product_stock_balance = warehouse_product_stock_balance - {$data->quantity}
        WHERE warehouse_product_stock_warehouse_id = {$data->warehouse_id}
        AND warehouse_product_stock_product_id = {$data->product_id}
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah stok.", 1);
        }
        $stock = $this->db->table("inv_warehouse_product_stock")->getWhere(["warehouse_product_stock_warehouse_id" => $data->warehouse_id, "warehouse_product_stock_product_id" => $data->product_id])->getRow();

        $this->db->table("inv_warehouse_product_stock_log")->insert([
            "warehouse_product_stock_log_warehouse_id" => $data->warehouse_id,
            "warehouse_product_stock_log_product_id" => $data->product_id,
            "warehouse_product_stock_log_type" => "out",
            "warehouse_product_stock_log_quantity" => $data->quantity,
            "warehouse_product_stock_log_unit_price" => $data->unit_price,
            "warehouse_product_stock_log_balance" => $stock->warehouse_product_stock_balance,
            "warehouse_product_stock_log_note" => $data->note,
            "warehouse_product_stock_log_datetime" => $data->datetime,
        ]);
    }

    public function substractStockStockist($data)
    {
        $sql = "
        UPDATE inv_stockist_product_stock
        SET stockist_product_stock_balance = stockist_product_stock_balance - {$data->quantity}
        WHERE stockist_product_stock_stockist_member_id = {$data->stockist_member_id}
        AND stockist_product_stock_product_id = {$data->product_id}
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah stok.", 1);
        }
        $stock = $this->db->table("inv_stockist_product_stock")->getWhere(["stockist_product_stock_stockist_member_id" => $data->stockist_member_id, "stockist_product_stock_product_id" => $data->product_id])->getRow();
        if ($stock->stockist_product_stock_balance < 0) {
            throw new \Exception("Stok tidak mencukupi.", 1);
        }

        $this->db->table("inv_stockist_product_stock_log")->insert([
            "stockist_product_stock_log_stockist_member_id" => $data->stockist_member_id,
            "stockist_product_stock_log_product_id" => $data->product_id,
            "stockist_product_stock_log_type" => "out",
            "stockist_product_stock_log_quantity" => $data->quantity,
            "stockist_product_stock_log_unit_price" => $data->unit_price,
            "stockist_product_stock_log_balance" => $stock->stockist_product_stock_balance,
            "stockist_product_stock_log_note" => $data->note,
            "stockist_product_stock_log_datetime" => $data->datetime,
        ]);
    }

    public function addStock($data)
    {
        foreach ($data as $key => $value) {
            $sql = "
            UPDATE inv_warehouse_product_stock
            SET warehouse_product_stock_balance = warehouse_product_stock_balance + {$value->quantity}
            WHERE warehouse_product_stock_warehouse_id = {$value->warehouse_id}
            AND warehouse_product_stock_product_id = {$value->product_id}
            ";
            $this->db->query($sql);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah stok.", 1);
            }
            $stock = $this->db->table("inv_warehouse_product_stock")->getWhere(["warehouse_product_stock_warehouse_id" => $value->warehouse_id, "warehouse_product_stock_product_id" => $value->product_id])->getRow();

            $this->db->table("inv_warehouse_product_stock_log")->insert([
                "warehouse_product_stock_log_warehouse_id" => $value->warehouse_id,
                "warehouse_product_stock_log_product_id" => $value->product_id,
                "warehouse_product_stock_log_type" => "in",
                "warehouse_product_stock_log_quantity" => $value->quantity,
                "warehouse_product_stock_log_unit_price" => $value->unit_price,
                "warehouse_product_stock_log_balance" => $stock->warehouse_product_stock_balance,
                "warehouse_product_stock_log_note" => $value->note,
                "warehouse_product_stock_log_datetime" => $value->datetime,
            ]);
        }
    }

    public function checkStock($warehouse_id, $product_id, $quantity)
    {
        $stock = $this->db->table("inv_warehouse_product_stock")
            ->getWhere(["warehouse_product_stock_warehouse_id" => $warehouse_id, "warehouse_product_stock_product_id" => $product_id, "warehouse_product_stock_balance >=" => $quantity])
            ->getRow();

        if (is_null($stock)) {
            return FALSE;
        }

        return TRUE;
    }

    public function checkStockStockist($stockist_member_is, $product_id, $quantity)
    {
        $stock = $this->db->table("inv_stockist_product_stock")
            ->getWhere(["stockist_product_stock_stockist_member_id" => $stockist_member_is, "stockist_product_stock_product_id" => $product_id, "stockist_product_stock_balance >=" => $quantity])
            ->getRow();

        if (is_null($stock)) {
            return FALSE;
        }

        return TRUE;
    }

    public function addStockStockist($data)
    {
        $sql = "
        UPDATE inv_stockist_product_stock
        SET stockist_product_stock_balance = stockist_product_stock_balance + {$data->quantity}
        WHERE stockist_product_stock_stockist_member_id = {$data->member_id}
        AND stockist_product_stock_product_id = {$data->product_id}
        ";

        $this->db->query($sql);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah stok.", 1);
        }

        $this->db->table("inv_stockist_product_stock_log")->insert([
            "stockist_product_stock_log_stockist_member_id" => $data->member_id,
            "stockist_product_stock_log_product_id" => $data->product_id,
            "stockist_product_stock_log_type" => "in",
            "stockist_product_stock_log_quantity" => $data->quantity,
            "stockist_product_stock_log_unit_price" => $data->unit_price,
            "stockist_product_stock_log_balance" => $this->db->table("inv_stockist_product_stock")->getWhere(["stockist_product_stock_stockist_member_id" => $data->member_id, "stockist_product_stock_product_id" => $data->product_id])->getRow("stockist_product_stock_balance"),
            "stockist_product_stock_log_note" => $data->note,
            "stockist_product_stock_log_datetime" => $data->datetime,
        ]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah riwayat stok.", 1);
        }
    }

    public function addStockWarehouse($data)
    {
        $sql = "
        UPDATE inv_warehouse_product_stock
        SET warehouse_product_stock_balance = warehouse_product_stock_balance + {$data->quantity}
        WHERE warehouse_product_stock_warehouse_id = {$data->member_id}
        AND warehouse_product_stock_product_id = {$data->product_id}
        ";
        $this->db->query($sql);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah stok.", 1);
        }

        $this->db->table("inv_warehouse_product_stock_log")->insert([
            "warehouse_product_stock_log_warehouse_id" => 1,
            "warehouse_product_stock_log_product_id" => $data->product_id,
            "warehouse_product_stock_log_type" => "in",
            "warehouse_product_stock_log_quantity" => $data->quantity,
            "warehouse_product_stock_log_unit_price" => $data->unit_price,
            "warehouse_product_stock_log_balance" => $this->db->table("inv_warehouse_product_stock")->getWhere(["warehouse_product_stock_warehouse_id" => 1, "warehouse_product_stock_product_id" => $data->product_id])->getRow("warehouse_product_stock_balance"),
            "warehouse_product_stock_log_note" => $data->note,
            "warehouse_product_stock_log_datetime" => $data->datetime,
        ]);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal menambah riwayat stok.", 1);
        }
    }
}
