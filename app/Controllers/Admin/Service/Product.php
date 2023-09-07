<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\ProductModel;
use Config\Services;

class Product extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->productModel = new ProductModel();
        $this->db = \Config\Database::connect();
    }

    public function getDataProduct()
    {
        $tableName = 'inv_product';
        $columns = array(
            'product_id',
            'product_code',
            'product_name',
            'product_price',
            'product_master_stockist_price',
            'product_mobile_stockist_price',
            'product_member_price',
            'product_weight',
            'product_description',
            'product_image',
            'product_input_datetime',
            'product_is_active',
        );
        $joinTable = '';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($value['product_image'] != '' && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $value['product_image'])) {
                    $data['results'][$key]['product_image'] = UPLOAD_URL . URL_IMG_PRODUCT . $value['product_image'];
                } else {
                    $data['results'][$key]['product_image'] = BASEURL . "/logo.png";
                }

                if ($value['product_input_datetime']) {
                    $data['results'][$key]['product_input_datetime_formatted'] = $this->functionLib->convertDatetime($value['product_input_datetime']);
                }
                $data['results'][$key]['product_price_formatted'] = $this->functionLib->format_nominal("Rp ", $value['product_price'], 2);
                $data['results'][$key]['product_member_price_formatted'] =  $this->functionLib->format_nominal('Rp ', $value['product_member_price'], 2);
                $data['results'][$key]['product_master_stockist_price_formatted'] =  $this->functionLib->format_nominal('Rp ', $value['product_master_stockist_price'], 2);
                $data['results'][$key]['product_mobile_stockist_price_formatted'] =  $this->functionLib->format_nominal('Rp ', $value['product_mobile_stockist_price'], 2);
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
            if ($data['results']->product_image != '' && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $data['results']->product_image)) {
                $data['results']->product_image_url = UPLOAD_URL . URL_IMG_PRODUCT . $data['results']->product_image;
            } else {
                $data['results']->product_image_url = BASEURL . "/logo.png";
            }

            $data['results']->product_input_datetime_formatted =  $this->functionLib->convertDatetime($data['results']->product_input_datetime);
            $data['results']->product_price_formatted =  $this->functionLib->format_nominal('', $data['results']->product_price, 2);
            $data['results']->product_member_price_formatted =  $this->functionLib->format_nominal('', $data['results']->product_member_price, 2);
            $data['results']->product_master_stockist_price_formatted =  $this->functionLib->format_nominal('', $data['results']->product_master_stockist_price, 2);
            $data['results']->product_mobile_stockist_price_formatted =  $this->functionLib->format_nominal('', $data['results']->product_mobile_stockist_price, 2);
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
                    'required' => 'Nama Produk tidak boleh kosong',
                ],
            ],
            'product_price' => [
                'label' => 'product_price',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga Produk tidak boleh kosong',
                    'integer' => 'Harga Produk member hanya boleh berisi angka',
                ],
            ],
            // 'product_type' => [
            //     'label' => 'product_type',
            //     'rules' => 'required|in_list[activation,repeatorder]',
            //     'errors' => [
            //         'required' => 'Tipe Produk tidak boleh kosong',
            //         'in_list' => 'Tipe Produk tidak sesuai',
            //     ],
            // ],
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

                $data['product_code'] = $this->productModel->getProductCode($this->request->getVar('product_name'));
                $data['product_name'] = $this->request->getVar('product_name');
                $data['product_price'] = $this->request->getVar('product_price');
                $data['product_member_price'] = $this->request->getVar('product_price');
                $data['product_master_stockist_price'] = $this->request->getVar('product_master_stockist_price');
                $data['product_mobile_stockist_price'] = $this->request->getVar('product_mobile_stockist_price');
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
                        "stockist_product_stock_stockist_member_id" => $stockist->stockist_member_id,
                        "stockist_product_stock_product_id" => $id,
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
                    'required' => 'ID Produk tidak boleh kosong',
                ],
            ],
            'product_name' => [
                'label' => 'product_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Produk tidak boleh kosong',
                ],
            ],
            'product_price' => [
                'label' => 'product_price',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga Produk tidak boleh kosong',
                    'integer' => 'Harga Produk member hanya boleh berisi angka',
                ],
            ],
            // 'product_type' => [
            //     'label' => 'product_type',
            //     'rules' => 'required|in_list[activation,repeatorder]',
            //     'errors' => [
            //         'required' => 'Tipe Produk tidak boleh kosong',
            //         'in_list' => 'Tipe Produk tidak sesuai',
            //     ],
            // ],
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

                $data['product_code'] = $this->productModel->getProductCode($this->request->getVar('product_name'));
                $data['product_name'] = $this->request->getVar('product_name');
                $data['product_member_price'] = $this->request->getVar('product_price');
                $data['product_price'] = $this->request->getVar('product_price');
                $data['product_master_stockist_price'] = $this->request->getVar('product_master_stockist_price');
                $data['product_mobile_stockist_price'] = $this->request->getVar('product_mobile_stockist_price');
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

    public function activeProduct()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_product')->update(['product_is_active' => 1], ['product_id' => $id]);

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
            $message = 'Berhasil mengaktifkan data product!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan data product';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data product!');
        }
    }

    public function nonActiveProduct()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_product')->update(['product_is_active' => 0], ['product_id' => $id]);

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
            $message = 'Berhasil menonaktifkan data product!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan data product';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data product!');
        }
    }

    public function deleteProduct()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('inv_product')->delete(['product_id' => $id]);
                $affectedRows = $this->db->affectedRows();

                $this->db->table('inv_warehouse_product_stock')->delete(['warehouse_product_stock_product_id' => $id]);

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
            $message = 'Berhasil menghapus data product!';
            if ($success == 0) {
                $message = 'Gagal menghapus data product';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data product!');
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

                $img->move(UPLOAD_PATH . URL_IMG_PRODUCT, $filename);

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

    public function getOptionProduct($type = "activation")
    {
        $data = array();
        $data['results'] = $this->db->table("inv_product")->select("product_id, product_code, product_name, product_price")->getWhere(["product_type" => $type])->getResult();

        $this->createRespon(200, 'Data Product', $data);
    }
}
