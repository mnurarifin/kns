<?php

namespace App\Controllers\Admin\Service;

use Config\Services;
use App\Models\Admin\ProductModel;
use App\Models\Admin\StockistModel;



class Stockist extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper(['form', 'url']);
        $this->productModel = new ProductModel;
        $this->stockistModel = new StockistModel;
    }

    public function getData($status = '')
    {
        $whereCondition = '';

        switch ($status) {
            case 'pending':
                $whereCondition = "stockist_status = 'pending'";
                break;

            case 'approved':
                $whereCondition = "stockist_status = 'approved'";
                break;
        }

        $tableName = 'inv_stockist';
        $columns = array(
            'stockist_member_id',
            'stockist_name',
            'stockist_type',
            'stockist_email',
            'stockist_address',
            'stockist_mobilephone',
            'stockist_province_id',
            'stockist_city_id',
            'stockist_longitude',
            'stockist_latitude',
            'stockist_image',
            'stockist_input_datetime',
            'stockist_is_active',
            'stockist_status',
            'member_name',
            'network_code',
            'city_name',
            'province_name',
            'subdistrict_name',
            'member_account_username',
        );
        $joinTable = '
        LEFT JOIN ref_city ON city_id = stockist_city_id 
        LEFT JOIN ref_province ON province_id = stockist_province_id
        LEFT JOIN ref_subdistrict ON subdistrict_id = stockist_subdistrict_id 
        JOIN sys_network ON network_member_id = stockist_member_id
        JOIN sys_member ON member_id = stockist_member_id
        JOIN sys_member_account ON member_account_member_id = member_id
        ';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                $data['results'][$key]['stockist_input_datetime'] = $this->functionLib->convertDatetime($value['stockist_input_datetime']);
            }
        }
        $this->createRespon(200, 'Data Stockist', $data);
    }

    public function detailStockist()
    {
        $data = array();
        $data['results'] = $this->stockistModel->getDetailStockist($this->request->getGet('id'));

        if ($data['results']) {
            $this->createRespon(200, 'Detail Data', $data);
        } else {
            $this->createRespon(400, 'Data tidak ditemukan', ['results' => []]);
        }
    }

    public function getDetail()
    {
        $results = array();
        $results['results'] = [];

        try {
            $stockist_member_id = $this->request->getVar('stockist_member_id');

            if (empty($stockist_member_id)) {
                throw new \Exception("Gagal memproses, silahkan coba lagi kembali.", 1);
            }

            $results['results'] = $this->stockistModel->getDetailStockist($stockist_member_id);

            if (empty($results['results'])) {
                throw new \Exception("Gagal memproses, silahkan coba lagi kembali.", 1);
            }

            $results['results']->member_join_date = $this->functionLib->convertDatetime(date("Y-m-d", strtotime($results['results']->member_join_datetime)));
            $results['results']->stockist_join_date = $this->functionLib->convertDatetime($results['results']->stockist_join_date);

            $this->createRespon(200, 'Data Stockist', $results);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage() . $th->getLine(), $results);
        }
    }

    public function getDataStockist()
    {
        $tableName = 'inv_stockist';
        $columns = array(
            'stockist_member_id',
            'network_code',
            'stockist_name',
            'stockist_is_active',
            'stockist_status'
        );
        $joinTable = 'JOIN sys_network on network_member_id = stockist_member_id';
        $whereCondition = "stockist_status = 'approved'";
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                $data['results'][$key]['stockist_stock_all'] = $this->db->table('inv_stockist_product_stock')->selectSum('stockist_product_stock_balance')->getWhere(['stockist_product_stock_stockist_member_id' => $value['stockist_member_id']])->getRow('stockist_product_stock_balance');
            }
        }

        $this->createRespon(200, 'Data Produk Stokis', $data);
    }

    public function actActive()
    {
        $dataStockist = $this->request->getPost('data');

        if (is_array($dataStockist)) {
            $success = $failed = 0;
            foreach ($dataStockist as $stockistId) {
                $dataUp = ['stockist_is_active' => 1];
                if ($this->functionLib->updateData('inv_stockist', 'stockist_member_id', $stockistId, $dataUp)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil aktifkan data!';
            if ($success == 0) {
                $message = 'Gagal aktifkan Data';
            }

            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function actUnactive()
    {
        $dataStockist = $this->request->getPost('data');

        if (is_array($dataStockist)) {
            $success = $failed = 0;
            foreach ($dataStockist as $stockistId) {
                $dataUp = ['stockist_is_active' => 0];
                if ($this->functionLib->updateData('inv_stockist', 'stockist_member_id', $stockistId, $dataUp)) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataNotAktive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil nonaktifkan data!';
            if ($success == 0) {
                $message = 'Gagal nonaktifkan Data';
            }

            $this->createRespon(200, $message, $dataNotAktive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang dinonaktifkan!');
        }
    }

    public function getProvince()
    {
        $data = array();
        $data['arrProvince'] = $this->functionLib->getListProvince();

        $this->createRespon(200, 'Data Provinsi', $data);
    }

    public function getCity($prov)
    {
        $data = array();
        $data['arrCity'] = $this->functionLib->getListCity($prov);

        $this->createRespon(200, 'Data Kota', $data);
    }

    public function getSubdistrict($city)
    {
        $data = array();
        $data['arrSubdistrict'] = $this->functionLib->getListSubdistrict($city);

        $this->createRespon(200, 'Data Kecamatan', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'member_account_username' => [
                'label' => 'Username',
                'rules' => "required|is_not_unique[sys_member_account.member_account_username]",
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_not_unique' => '{field} tidak terdaftar'
                ],
            ],
            'stockist_type' => [
                'label' => 'Tipe Stokis',
                'rules' => 'required|in_list[master,mobile]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ],
            ],
            'stockist_name' => [
                'label' => 'Nama Stokis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ],
            ],
            'stockist_email' => [
                'label' => 'Email Stokis',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'valid_email' => '{field} tidak valid'
                ],
            ],
            'stockist_address' => [
                'label' => 'Alamat Stokis',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_province_id' => [
                'label' => 'Provinsi',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_city_id' => [
                'label' => 'Kota',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_subdistrict_id' => [
                'label' => 'Kecamatan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $member_id = $this->db->query("SELECT member_account_member_id AS member_id FROM sys_member_account WHERE member_account_username = '" . $this->request->getPost('member_account_username') . "'")->getRow("member_id");
            $sql = "SELECT * FROM inv_stockist WHERE stockist_member_id = '" . $member_id . "'";

            if (!is_null($this->db->query($sql)->getRow())) {
                $result = array(
                    "validationMessage" => ['member_account_username' => 'Stokis dengan username ini sudah terdaftar'],
                );
                $this->createRespon(400, 'validationError', $result);
            }

            $this->db->transBegin();
            try {
                $data = [
                    'stockist_member_id' => $member_id,
                    'stockist_type' => $this->request->getPost('stockist_type'),
                    'stockist_name' => $this->request->getPost('stockist_name'),
                    'stockist_email' => $this->request->getPost('stockist_email'),
                    'stockist_address' => $this->request->getPost('stockist_address'),
                    'stockist_mobilephone' => $this->request->getPost('stockist_mobilephone'),
                    'stockist_city_id' => $this->request->getPost('stockist_city_id'),
                    'stockist_province_id' => $this->request->getPost('stockist_province_id'),
                    'stockist_subdistrict_id' => $this->request->getPost('stockist_subdistrict_id'),
                    'stockist_input_datetime' => date("Y-m-d H:i:s"),
                    'stockist_status' => 'approved'
                ];

                $this->db->table('inv_stockist')->insert($data);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal Tambah Stokis", 1);
                }

                $this->db->table('sys_ewallet')->insert(["ewallet_member_id" => $member_id]);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal Tambah Stokis", 1);
                }

                $product = $this->db->table('inv_product')->select('product_id')->get()->getResultArray();

                foreach ($product as $key => $value) {
                    $stock = [
                        'stockist_product_stock_stockist_member_id' => $member_id,
                        'stockist_product_stock_product_id' => $value['product_id'],
                        'stockist_product_stock_balance' => 0
                    ];

                    $this->db->table('inv_stockist_product_stock')->insert($stock);

                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Gagal Tambah Stokis", 1);
                    }
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil tambah data stokis', $data);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['line' => $th->getLine(), 'file' => $th->getFile()]);
            }
        }
    }

    public function update()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'stockist_name' => [
                'label' => 'Nama Stokis',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_email' => [
                'label' => 'Email Stokis',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'valid_email' => '{field} tidak valid'
                ],
            ],
            'stockist_address' => [
                'label' => 'Alamat Stokis',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_province_id' => [
                'label' => 'Provinsi',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_city_id' => [
                'label' => 'Kota',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'stockist_subdistrict_id' => [
                'label' => 'Kecamatan',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $data = [
                    'stockist_type' => $this->request->getPost('stockist_type'),
                    'stockist_name' => $this->request->getPost('stockist_name'),
                    'stockist_email' => $this->request->getPost('stockist_email'),
                    'stockist_address' => $this->request->getPost('stockist_address'),
                    'stockist_mobilephone' => $this->request->getPost('stockist_mobilephone'),
                    'stockist_city_id' => $this->request->getPost('stockist_city_id'),
                    'stockist_province_id' => $this->request->getPost('stockist_province_id'),
                    'stockist_subdistrict_id' => $this->request->getPost('stockist_subdistrict_id'),
                ];

                $this->db->table('inv_stockist')
                    ->where('stockist_member_id', $this->request->getPost('stockist_member_id'))
                    ->update($data);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal ubah data stokis", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil ubah data stokis', $data);
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, 'Bad Request', $th->getMessage());
            }
        }
    }

    public function detailData()
    {
        $data = array();
        $data['results'] = $this->db->table('inv_stockist')
            ->getWhere(['stockist_member_id' => $this->request->getGet('id')])
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Data Stockist', $data);
        } else {
            $this->createRespon(400, 'Stockist tidak ditemukan', ['results' => []]);
        }
    }

    public function getDataStock()
    {
        $tableName = 'inv_stockist_product_stock';
        $columns = array(
            'stockist_product_stock_id',
            'stockist_product_stock_stockist_member_id',
            'stockist_product_stock_product_id',
            'stockist_product_stock_balance',
            'product_name',
            'stockist_name',
        );
        $joinTable = ' JOIN inv_product ON product_id = stockist_product_stock_product_id JOIN inv_stockist ON stockist_member_id = stockist_product_stock_stockist_member_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getDataStockLog()
    {
        $tableName = 'inv_stockist_product_stock_log';
        $columns = array(
            'stockist_product_stock_log_id',
            'stockist_product_stock_log_stockist_member_id',
            'stockist_product_stock_log_product_id',
            'stockist_product_stock_log_type',
            'stockist_product_stock_log_quantity',
            'stockist_product_stock_log_unit_price',
            'stockist_product_stock_log_balance',
            'stockist_product_stock_log_note',
            'stockist_product_stock_log_datetime',
            'product_name',
            'stockist_name',
        );
        $joinTable = ' JOIN inv_product ON product_id = stockist_product_stock_log_product_id JOIN inv_stockist ON stockist_member_id = stockist_product_stock_log_stockist_member_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['stockist_product_stock_log_datetime']) {
                    $data['results'][$key]['stockist_product_stock_log_datetime_formatted'] = $this->functionLib->convertDatetime($row['stockist_product_stock_log_datetime']);
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function get_member_detail()
    {
        $sql = "SELECT member_name FROM sys_member JOIN sys_member_account ON member_account_member_id = member_id WHERE member_account_username = '" . $this->request->getGet('username') . "'";

        $member_name = $this->db->query($sql)->getRow("member_name");
        if (is_null($member_name)) {
            $this->createRespon(400, "Tidak ditemukan");
        }

        $this->createRespon(200, 'Berhasil mengambil data.', ["results" => $member_name]);
    }
}
