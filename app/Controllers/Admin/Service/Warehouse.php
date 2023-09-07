<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\WarehouseModel;
use Config\Services;

class Warehouse extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->warehouseModel = new WarehouseModel();
        $this->db = \Config\Database::connect();
    }


    public function getDataWarehouse()
    {
        $tableName = 'inv_warehouse';
        $columns = array(
            'warehouse_id',
            'warehouse_name',
            'warehouse_address',
            'warehouse_is_active',
            'warehouse_input_datetime',
            'warehouse_input_administrator_id',
        );
        $joinTable = '';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['warehouse_input_datetime']) {
                    $data['results'][$key]['warehouse_input_datetime_formatted'] = $this->functionLib->convertDatetime($row['warehouse_input_datetime']);
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getWarehouse()
    {
        $data['results'] = $this->warehouseModel->getAllWarehouse();

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function detailWarehouse()
    {
        $warehouse_id = $this->request->getGet('id');

        $data = array();
        $data['results'] = $this->warehouseModel->getWarehouseByID($warehouse_id);

        if ($data['results']) {
            $data['results']->warehouse_input_datetime_formatted =  $this->functionLib->convertDatetime($data['results']->warehouse_input_datetime);
        } else {
            $this->createRespon(400, 'Gudang tidak ditemukan', ['results' => []]);
        }

        $this->createRespon(200, 'Data Gudang', $data);
    }

    public function addWarehouse()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'warehouse_name' => [
                'label' => 'warehouse_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Gudang tidak boleh kosong',
                ],
            ],
            'warehouse_address' => [
                'label' => 'warehouse_address',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat Gudang tidak boleh kosong',
                ],
            ],
            'warehouse_is_active' => [
                'label' => 'warehouse_is_active',
                'rules' => 'in_list[1,0]',
                'errors' => [
                    'in_list' => 'Gudang aktif hanya boleh 1 atau 0'
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
                $date = date('Y-m-d H:i:s');

                $data['warehouse_name'] = $this->request->getVar('warehouse_name');
                $data['warehouse_address'] = $this->request->getVar('warehouse_address');
                $data['warehouse_is_active'] = $this->request->getVar('warehouse_is_active');
                $data['warehouse_input_datetime'] = $date;

                $this->db->table('inv_warehouse')->insert($data);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $id = $this->db->insertID();
                $data['warehouse_id'] = $id;

                $products = $this->db->table('inv_product')->get()->getResult();
                foreach ($products as $product) {
                    $this->db->table('inv_warehouse_product_stock')->insert([
                        "warehouse_product_stock_warehouse_id" => $id,
                        "warehouse_product_stock_product_id" => $product->product_id,
                    ]);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Proses gagal.", 1);
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

    public function updateWarehouse()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'warehouse_id' => [
                'label' => 'warehouse_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID Warehouse tidak boleh kosong',
                ],
            ],
            'warehouse_name' => [
                'label' => 'warehouse_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Warehouse tidak boleh kosong',
                ],
            ],
            'warehouse_address' => [
                'label' => 'warehouse_address',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Harga warehouse tidak boleh kosong',
                ],
            ],
            'warehouse_is_active' => [
                'label' => 'warehouse_is_active',
                'rules' => 'in_list[1,0]',
                'errors' => [
                    'in_list' => 'Gudang aktif hanya boleh 1 atau 0'
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
            try {
                $warehouse_id = $this->request->getVar('warehouse_id');

                $date = date('Y-m-d H:i:s');

                $data['warehouse_name'] = $this->request->getVar('warehouse_name');
                $data['warehouse_address'] = $this->request->getVar('warehouse_address');
                $data['warehouse_is_active'] = $this->request->getVar('warehouse_is_active');
                $data['warehouse_input_datetime'] = $date;

                $this->db->table('inv_warehouse')->update($data, ['warehouse_id' => $warehouse_id]);
                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data warehouse", 1);
                }

                $message = "Berhasil memasukan data";
                $this->createRespon(200, $message, ['results' => $data]);
            } catch (\Throwable $th) {
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function activeWarehouse()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_warehouse')->update(['warehouse_is_active' => 1], ['warehouse_id' => $id]);

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $data = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan data warehouse!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan data warehouse';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data warehouse!');
        }
    }

    public function nonActiveWarehouse()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_warehouse')->update(['warehouse_is_active' => 0], ['warehouse_id' => $id]);

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $data = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menonaktifkan data warehouse!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan data warehouse';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data warehouse!');
        }
    }

    public function deleteWarehouse()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_warehouse')->delete(['warehouse_id' => $id]);
                $affectedRows = $this->db->affectedRows();
                
                $this->db->table('inv_warehouse_product_stock')->delete(['warehouse_product_stock_warehouse_id' => $id]);

                if ($affectedRows > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $data = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menghapus data warehouse!';
            if ($success == 0) {
                $message = 'Gagal menghapus data warehouse';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data warehouse!');
        }
    }
}
