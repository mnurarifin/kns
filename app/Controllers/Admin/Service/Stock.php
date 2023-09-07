<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\StockModel;
use Config\Services;

class Stock extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->stockModel = new StockModel();
        $this->db = \Config\Database::connect();
    }

    public function getDataStock()
    {
        $tableName = 'inv_warehouse_product_stock';
        $columns = array(
            'warehouse_product_stock_id',
            'warehouse_product_stock_warehouse_id',
            'warehouse_product_stock_product_id',
            'warehouse_product_stock_balance',
            'product_name',
            'warehouse_name',
        );
        $joinTable = ' JOIN inv_product ON product_id = warehouse_product_stock_product_id JOIN inv_warehouse ON warehouse_id = warehouse_product_stock_warehouse_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getStock()
    {
        $data['results'] = $this->stockModel->getAllStock();

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function detailReceivement()
    {
        $stock_id = $this->request->getGet('id');

        $data = array();
        $data['results'] = $this->stockModel->getReceivementByID($stock_id);

        if ($data['results']) {
        } else {
            $this->createRespon(400, 'Penerimaan tidak ditemukan', ['results' => []]);
        }

        $this->createRespon(200, 'Data Stok', $data);
    }

    public function detailStock()
    {
        $data = array();
        $data['results'] = $this->db->table('inv_warehouse_product_stock')
            ->select('warehouse_product_stock_id, warehouse_name, warehouse_product_stock_balance, product_name')
            ->join('inv_product', 'product_id = warehouse_product_stock_product_id')
            ->join('inv_warehouse', 'warehouse_id = warehouse_product_stock_warehouse_id')
            ->getWhere(['warehouse_product_stock_id' => $this->request->getGet('id')])
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Detail Data', $data);
        } else {
            $this->createRespon(400, 'Data tidak ditemukan', ['results' => []]);
        }
    }

    public function updateStock()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'warehouse_product_stock_id' => [
                'label' => 'Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID Stock tidak boleh kosong',
                ],
            ],
            'balance' => [
                'label' => 'Jumlah',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Stock tidak boleh kosong',
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
                $datetime = date("Y-m-d H:i:s");
                $stock_id = $this->request->getVar('warehouse_product_stock_id');
                $new_balance = $this->request->getVar('balance');

                $data['warehouse_product_stock_balance'] = $this->request->getVar('balance');

                $stock = $this->db->table("inv_warehouse_product_stock")->getWhere(["warehouse_product_stock_id" => $stock_id])->getRow();
                if ($stock->warehouse_product_stock_balance > $new_balance) {
                    $type = "out";
                    $qty = $stock->warehouse_product_stock_balance - $new_balance;
                } else if ($stock->warehouse_product_stock_balance < $new_balance) {
                    $type = "in";
                    $qty = $new_balance - $stock->warehouse_product_stock_balance;
                } else {
                    throw new \Exception("Stok sama", 1);
                }
                $admin_name = session("admin")["admin_name"];
                $this->stockModel->log($stock->warehouse_product_stock_warehouse_id, $stock->warehouse_product_stock_product_id, $type, $qty, $stock->warehouse_product_stock_balance, "Opname stok oleh {$admin_name}", $datetime);

                $this->db->table('inv_warehouse_product_stock')->update($data, ['warehouse_product_stock_id' => $stock_id]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update data stock", 1);
                }

                $message = "Berhasil memasukan data";
                $this->db->transCommit();
                $this->createRespon(200, $message, ['results' => $data]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function addReceivement()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'warehouse_receipt_warehouse_id' => [
                'label' => 'Gudang',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gudang tidak boleh kosong',
                ],
            ],
            'warehouse_receipt_supplier' => [
                'label' => 'Supplier',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Supplier tidak boleh kosong',
                ],
            ],
            'warehouse_receipt_detail.*.product_id' => [
                'label' => 'Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Produk tidak boleh kosong',
                ],
            ],
            'warehouse_receipt_detail.*.product_qty' => [
                'label' => 'Jumlah',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah tidak boleh kosong',
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
                $datetime = date("Y-m-d H:i:s");
                $details = $this->request->getVar('warehouse_receipt_detail');
                $warehouse_id = $this->request->getVar('warehouse_receipt_warehouse_id');
                $supplier = $this->request->getVar('warehouse_receipt_supplier');
                $note = $this->request->getVar('warehouse_receipt_note');
                $code = $this->request->getVar('warehouse_receipt_code');

                $data['warehouse_product_stock_balance'] = $this->request->getVar('balance');

                $this->db->table("inv_warehouse_receipt")->insert([
                    "warehouse_receipt_warehouse_id" => $warehouse_id,
                    "warehouse_receipt_code" => $code,
                    "warehouse_receipt_supplier" => $supplier,
                    "warehouse_receipt_note" => $note,
                    "warehouse_receipt_input_datetime" => $datetime,
                    "warehouse_receipt_input_administrator_id" => session('admin')['admin_id'],
                ]);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update data stock", 1);
                }
                $insert_id = $this->db->insertID();

                foreach ($details as $detail) {
                    $stock = $this->db->table("inv_warehouse_product_stock")->getWhere(["warehouse_product_stock_warehouse_id" => $warehouse_id, "warehouse_product_stock_product_id" => $detail['product_id']])->getRow();
                    $this->stockModel->log($warehouse_id, $detail['product_id'], "in", $detail['product_qty'], $stock->warehouse_product_stock_balance, "Penerimaan Kode " . $code, $datetime);

                    $this->db->table('inv_warehouse_product_stock')->set("warehouse_product_stock_balance", "warehouse_product_stock_balance+" . $detail['product_qty'], FALSE)
                        ->where(["warehouse_product_stock_warehouse_id" => $warehouse_id, "warehouse_product_stock_product_id" => $detail['product_id']])->update();
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update data stock", 1);
                    }

                    $this->db->table("inv_warehouse_receipt_detail")->insert([
                        "warehouse_receipt_detail_receipt_id" => $insert_id,
                        "warehouse_receipt_detail_product_id" => $detail['product_id'],
                        "warehouse_receipt_detail_quantity" => $detail['product_qty'],
                    ]);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal update data stock", 1);
                    }
                }

                $message = "Berhasil memasukan data";
                $this->db->transCommit();
                $this->createRespon(200, $message, ['results' => $data]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function getDataStockLog()
    {
        $tableName = 'inv_warehouse_product_stock_log';
        $columns = array(
            'warehouse_product_stock_log_id',
            'warehouse_product_stock_log_warehouse_id',
            'warehouse_product_stock_log_product_id',
            'warehouse_product_stock_log_type',
            'warehouse_product_stock_log_quantity',
            'warehouse_product_stock_log_unit_price',
            'warehouse_product_stock_log_balance',
            'warehouse_product_stock_log_note',
            'warehouse_product_stock_log_datetime',
            'product_name',
            'warehouse_name',
        );
        $joinTable = ' JOIN inv_product ON product_id = warehouse_product_stock_log_product_id JOIN inv_warehouse ON warehouse_id = warehouse_product_stock_log_warehouse_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['warehouse_product_stock_log_datetime']) {
                    $data['results'][$key]['warehouse_product_stock_log_datetime_formatted'] = $this->functionLib->convertDatetime($row['warehouse_product_stock_log_datetime']);
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getDataReceivement()
    {
        $tableName = 'inv_warehouse_receipt';
        $columns = array(
            'warehouse_receipt_id',
            'warehouse_receipt_warehouse_id',
            'warehouse_receipt_code',
            'warehouse_receipt_supplier',
            'warehouse_receipt_note',
            'warehouse_receipt_input_datetime',
            'warehouse_receipt_input_administrator_id',
            'warehouse_name',
        );
        $joinTable = ' JOIN inv_warehouse ON warehouse_id = warehouse_receipt_warehouse_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['warehouse_receipt_input_datetime']) {
                    $data['results'][$key]['warehouse_receipt_input_datetime_formatted'] = $this->functionLib->convertDatetime($row['warehouse_receipt_input_datetime']);
                    $data['results'][$key]['detail'] = $this->db->table("inv_warehouse_receipt_detail")->getWhere(["warehouse_receipt_detail_receipt_id" => $row['warehouse_receipt_id']])->getResult();
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getWarehouse()
    {
        $tableName = 'inv_warehouse';
        $columns = array(
            'warehouse_id',
            'warehouse_name',
        );
        $whereCondition = "";
        $joinTable = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getProduct()
    {
        $tableName = 'inv_product';
        $columns = array(
            'product_id',
            'product_name',
            'product_price',
            'product_code',
            'warehouse_product_stock_balance'
        );
        $whereCondition = "";
        $joinTable = " JOIN inv_warehouse_product_stock ON warehouse_product_stock_product_id = product_id";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }
}
