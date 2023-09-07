<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\ProductModel;
use Config\Services;

class Package extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->productModel = new ProductModel();
        $this->db = \Config\Database::connect();
    }

    public function getDataPackage()
    {
        $tableName = 'inv_product_package';
        $columns = array(
            'product_package_id',
            'product_package_name',
            'product_package_type',
            'product_package_point',
            'product_package_note',
            'product_package_image',
            'product_package_is_active',
        );
        $joinTable = '';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                $data['result']['package_detail'] = $this->db->table('inv_product_package_detail')->join('inv_product', 'product_id = product_package_detail_product_id')->getWhere(['product_package_detail_product_package_id' => $row['product_package_id']])->getResult('array');
                $price = 0;
                $weight = 0;
                foreach ($data['result']['package_detail'] as $value) {
                    $price += $value['product_package_detail_price'] * $value['product_package_detail_quantity'];
                    $weight += $value['product_weight'] * $value['product_package_detail_quantity'];
                }
                $data['results'][$key]['product_package_weight'] = $weight;
                $data['results'][$key]['product_package_price_formatted'] = $this->functionLib->format_nominal("Rp ", $price, 2);
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getProduct()
    {
        $data['results'] = $this->productModel->getAllProduct();

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function detailProduct()
    {
        $product_id = $this->request->getGet('id');

        $data = array();
        $data['results'] = $this->productModel->getProductByID($product_id);

        if ($data['results']) {
            $data['results']->product_input_datetime_formatted =  $this->functionLib->convertDatetime($data['results']->product_input_datetime);
            $data['results']->product_member_price_formatted =  $this->functionLib->format_nominal('', $data['results']->product_member_price, 2);
            $data['results']->product_price_formatted =  $this->functionLib->format_nominal('', $data['results']->product_price, 2);
        } else {
            $this->createRespon(400, 'Produk tidak ditemukan', ['results' => []]);
        }


        $this->createRespon(200, 'Data Produk', $data);
    }

    public function detailSeries()
    {
        $data = array();
        $data['results'] = $this->productModel->getSeriesByID($this->request->getGet('series_id'));

        $this->createRespon(200, 'Data Produk', $data);
    }

    public function addProduct()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'product_name' => [
                'label' => 'product_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Product tidak boleh kosong',
                ],
            ],
            'product_price' => [
                'label' => 'product_price',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga product tidak boleh kosong',
                    'integer' => 'Harga product member hanya boleh berisi angka',
                ],
            ],
            'product_is_active' => [
                'label' => 'product_is_active',
                'rules' => 'in_list[1,0]',
                'errors' => [
                    'in_list' => 'Produk aktif hanya boleh 1 atau 0'
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

                $data['product_code'] = $this->productModel->getProductCode();
                $data['product_name'] = $this->request->getVar('product_name');
                $data['product_price'] = $this->request->getVar('product_price');
                $data['product_member_price'] = $this->request->getVar('product_price');
                $data['product_weight'] = $this->request->getVar('product_weight') ? $this->request->getVar('product_weight') : 0;
                $data['product_description'] = $this->request->getVar('product_description');
                $data['product_is_active'] = $this->request->getVar('product_is_active');
                $data['product_input_datetime'] = $date;
                $data['product_image'] = $this->request->getVar('product_image');

                $this->db->table('inv_product')->insert($data);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $id = $this->db->insertID();
                $data['product_id'] = $id;

                $warehouses = $this->db->table('inv_warehouse')->get()->getResult();
                foreach ($warehouses as $warehouse) {
                    $this->db->table('inv_warehouse_product_stock')->insert([
                        "warehouse_product_stock_warehouse_id" => $warehouse->warehouse_id,
                        "warehouse_product_stock_product_id" => $id,
                    ]);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Proses gagal.", 1);
                    }
                }

                $stockists = $this->db->table('inv_stockist')->get()->getResult();
                foreach ($stockists as $stockist) {
                    $this->db->table('inv_stockist_product_stock')->insert([
                        "stockist_product_stock_stockist_id" => $stockist->stockist_id,
                        "stockist_product_stock_product_id" => $id,
                    ]);
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Proses gagal.", 1);
                    }
                }

                $members = $this->db->table('sys_member')->get()->getResult();
                foreach ($members as $member) {
                    $this->db->table('inv_member_product_stock')->insert([
                        "member_product_stock_member_id" => $member->member_id,
                        "member_product_stock_product_id" => $id,
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

    public function updateProduct()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'product_id' => [
                'label' => 'product_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID Product tidak boleh kosong',
                ],
            ],
            'product_name' => [
                'label' => 'product_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Product tidak boleh kosong',
                ],
            ],
            'product_price' => [
                'label' => 'product_price',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga product tidak boleh kosong',
                    'integer' => 'Harga product member hanya boleh berisi angka',
                ],
            ],
            'product_is_active' => [
                'label' => 'product_is_active',
                'rules' => 'in_list[1,0]',
                'errors' => [
                    'in_list' => 'Produk aktif hanya boleh 1 atau 0'
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
            $product_image =  $this->request->getVar('product_image');

            try {

                $product_id = $this->request->getVar('product_id');

                $date = date('Y-m-d H:i:s');

                $data['product_name'] = $this->request->getVar('product_name');
                $data['product_member_price'] = $this->request->getVar('product_price');
                $data['product_price'] = $this->request->getVar('product_price');
                $data['product_weight'] = $this->request->getVar('product_weight');
                $data['product_description'] = $this->request->getVar('product_description');
                $data['product_is_active'] = $this->request->getVar('product_is_active');
                $data['product_input_datetime'] = $date;

                if ($product_image) {
                    $data['product_image'] = $product_image;
                }

                $this->db->table('inv_product')->update($data, ['product_id' => $product_id]);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data product", 1);
                }

                $message = "Berhasil memasukan data";
                $this->createRespon(200, $message, ['results' => $data]);
            } catch (\Throwable $th) {
                //throw $th;
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function activePackage()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_product_package')->update(['product_package_is_active' => 1], ['product_package_id' => $id]);

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
            $message = 'Berhasil mengaktifkan data paket!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan data paket';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data paket!');
        }
    }

    public function nonActivePackage()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_product_package')->update(['product_package_is_active' => 0], ['product_package_id' => $id]);

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
            $message = 'Berhasil menonaktifkan data paket!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan data paket';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data paket!');
        }
    }

    public function deletePackage()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_product_package')->delete(['product_package_id' => $id]);
                $affectedRows = $this->db->affectedRows();

                $this->db->table('inv_product_package_detail')->delete(['product_package_detail_product_package_id' => $id]);

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
            $message = 'Berhasil menghapus data paket!';
            if ($success == 0) {
                $message = 'Gagal menghapus data paket';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data paket!');
        }
    }

    public function uploadImage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'file' => [
                'label' => 'File',
                'rules' => 'max_size[file,3072]|mime_in[file,image/png,image/jpg,image/jpeg]',
                'errors' => [
                    'max_size' => 'File harus tidak lebih dari 3 mb',
                    'mime_in' => 'File format tidak valid'
                ],
            ]
        ]);

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            try {
                //code...   
                $img = $this->request->getFile('file');
                $filename = $img->getRandomName();

                $image = \Config\Services::image()
                    ->withFile($img)
                    ->resize(720, 480, true, 'height')
                    ->save(UPLOAD_PATH . URL_IMG_CONTENT . $filename);

                $data = [
                    'name' =>  $filename,
                    'type'  => $img->getClientMimeType(),
                    'temp' => $img->getTempName()
                ];
                $message = "Berhasil Mengupload File";

                $this->createRespon(200, $message, $data);
            } catch (\Throwable $th) {
                //throw $th;
                $this->createRespon(400, 'CODE_ERROR', $th);
            }
        }
    }

    public function addPackage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'package_name' => [
                'label' => 'Nama Paket Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'product_id.*' => [
                'label' => 'Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run((array) $this->request->getVar()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        }

        try {
            $this->db->transBegin();

            $arr_insert_package = [
                "product_package_image" => $this->request->getPost("package_image"),
                "product_package_name" => $this->request->getPost("package_name"),
                "product_package_note" => $this->request->getPost("package_description"),
                "product_package_point" => 0,
                "product_package_type" => $this->request->getPost("package_type"),
            ];

            $product_id = $this->request->getPost("product_id");
            $product_price = $this->request->getPost("product_price");
            $product_qty = $this->request->getPost("product_qty");

            $this->db->table("inv_product_package")->insert($arr_insert_package);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal tambah paket produk", 1);
            }

            $package_id = $this->db->insertID();

            foreach ($product_id as $key => $value) {
                $arr_insert_package_detail = [
                    "product_package_detail_price" => $product_price[$key],
                    "product_package_detail_product_id" => $value,
                    "product_package_detail_product_package_id" => $package_id,
                    "product_package_detail_quantity" => $product_qty[$key],
                ];

                $this->db->table("inv_product_package_detail")->insert($arr_insert_package_detail);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal tambah detail paket produk", 1);
                }
            }

            $this->db->transCommit();
            $this->createRespon(200, "Tambah paket produk berhasil", []);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), ['results' => []]);
        }
    }

    public function updatePackage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'package_name' => [
                'label' => 'Nama Paket Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'product_id.*' => [
                'label' => 'Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
        ]);

        if ($validation->run((array) $this->request->getVar()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        }

        try {
            $this->db->transBegin();

            $arr_update_package = [
                "product_package_image" => $this->request->getPost("package_image"),
                "product_package_name" => $this->request->getPost("package_name"),
                "product_package_note" => $this->request->getPost("package_description"),
                "product_package_point" => 0,
                "product_package_type" => $this->request->getPost("package_type"),
            ];

            $product_id = $this->request->getPost("product_id");
            $product_price = $this->request->getPost("product_price");
            $product_qty = $this->request->getPost("product_qty");

            $this->db->table("inv_product_package")->where("product_package_id", $this->request->getPost("product_package_id"))->update($arr_update_package);

            if ($this->db->affectedRows() < 0) {
                throw new \Exception("Gagal ubah paket produk", 1);
            }

            $this->db->table("inv_product_package_detail")->where("product_package_detail_product_package_id", $this->request->getPost("product_package_id"))->delete();

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengosongkan detail paket produk", 1);
            }

            foreach ($product_id as $key => $value) {
                $arr_insert_package_detail = [
                    "product_package_detail_price" => $product_price[$key],
                    "product_package_detail_product_id" => $value,
                    "product_package_detail_product_package_id" => $this->request->getPost("product_package_id"),
                    "product_package_detail_quantity" => $product_qty[$key],
                ];

                $this->db->table("inv_product_package_detail")->insert($arr_insert_package_detail);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal ubah detail paket produk", 1);
                }
            }

            $this->db->transCommit();
            $this->createRespon(200, "Ubah paket produk berhasil", []);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), ['results' => []]);
        }
    }

    public function detailPackage()
    {
        $package_id = $this->request->getGet('id');

        $data = [];

        $total_weight = 0;
        $total_price = 0;
        $data['results'] = $this->db->table("inv_product_package")->getWhere(["product_package_id" => $package_id])->getRow();
        $data['results']->detail = $this->db->table("inv_product_package_detail")->join("inv_product", "product_id = product_package_detail_product_id")->getWhere(["product_package_detail_product_package_id" => $package_id])->getResult();

        foreach ($data['results']->detail as $key => $value) {
            $total_weight += $value->product_weight * $value->product_package_detail_quantity;
            $total_price += $value->product_package_detail_quantity * $value->product_package_detail_price;
        }

        $data['results']->product_package_price = $this->functionLib->format_nominal('Rp ', $total_price, 2);
        $data['results']->product_package_weight = $total_weight;

        $this->createRespon(200, 'Data Package', $data);
    }
}
