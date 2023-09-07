<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Delivery;

class Common extends BaseController
{
    public function __construct()
    {
    }

    //validasi input kode upline
    public function check_upline()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $this->validation->setRules([
                'sponsor_username' => [
                    'label' => 'Username Sponsor',
                    'rules' => 'required|is_not_unique[sys_member_account.member_account_username]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong.',
                        'is_not_unique' => '{field} tidak ditemukan.'
                    ]
                ],
                'upline_username' => [
                    'label' => 'Username Upline',
                    'rules' => 'required|is_not_unique[sys_member_account.member_account_username]|check_crossline[{sponsor_username}]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong.',
                        'is_not_unique' => '{field} tidak ditemukan.',
                        'check_crossline' => "Upline dan sponsor crossline."
                    ]
                ],
            ])->run((array) $params);
            if ($this->validation->getErrors()) {
                $this->restLib->responseFailed('Silahkan periksa kembali inputan Anda.', 'validation', $this->validation->getErrors());
            }

            $obj_result = $this->db->table('sys_network')
                ->select('member_name, member_email, member_mobilephone')
                ->join('sys_member', 'member_id = network_member_id')
                ->getWhere(['network_code' => $params->upline_network_code])
                ->getRow();

            $response = [
                'message' => 'Data Upline',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function check_member()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $this->validation->setRules([
                'network_code' => [
                    'label' => 'Member',
                    'rules' => 'required|is_not_unique[sys_network.network_code]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong.',
                        'is_not_unique' => '{field} tidak ditemukan.'
                    ]
                ],
            ])->run((array) $params);

            if ($this->validation->getErrors()) {
                $this->restLib->responseFailed('Silahkan periksa kembali inputan Anda.', 'validation', $this->validation->getErrors());
            }

            $obj_result = $this->db->table('sys_network')
                ->select('member_name, member_email, member_mobilephone, network_code')
                ->join('sys_member', 'member_id = network_member_id')
                ->getWhere(['network_code' => $params->network_code])
                ->getRow();

            $response = [
                'message' => 'Data Member',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_stockist()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $where = ["stockist_is_active" => "1", "stockist_status" => "approved"];
            if (isset($params->type)) {
                $where = array_merge($where, ["stockist_type" => $params->type]);
                if (isset($params->member_id)) {
                    $where = array_merge($where, ["stockist_member_id <>" => $params->member_id]);
                }
            }

            $obj_result = $this->db->table('inv_stockist')->select("stockist_member_id, CONCAT(stockist_name,' (',city_name,')') AS stockist_name")
                ->join("ref_city", "city_id = stockist_city_id")
                ->getWhere($where)->getResult();

            $response = [
                'message' => 'List data stokis',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_ref_bank()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('ref_bank')->getWhere('bank_is_active = 1')->getResult();

            foreach ($obj_result as $key => $value) {
                if ($value->bank_logo != '' && file_exists(UPLOAD_PATH . URL_IMG_BANK . $value->bank_logo)) {
                    $bank_logo = $value->bank_logo;
                } else {
                    $bank_logo = '_default.png';
                }
                $obj_result[$key]->bank_logo = UPLOAD_URL . UPLOAD_PATH . URL_IMG_BANK . $bank_logo;
            }

            $response = [
                'message' => 'List data bank',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_bank_company()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('sys_bank_company')
                ->select("bank_company_id, bank_company_bank_id, bank_name, bank_logo, bank_company_bank_acc_name, bank_company_bank_acc_number, bank_company_bank_is_active")
                ->join('ref_bank', 'bank_company_bank_id = bank_id')
                ->getWhere(['bank_is_active' => 1, 'bank_company_bank_is_active' => '1'])
                ->getResult();

            if (!empty($obj_result)) {
                foreach ($obj_result as $key => $value) {
                    if ($value->bank_logo != '' && file_exists(UPLOAD_PATH . URL_IMG_BANK . $value->bank_logo)) {
                        $bank_logo = $value->bank_logo;
                    } else {
                        $bank_logo = '_default.png';
                    }
                    $obj_result[$key]->bank_logo = UPLOAD_URL . URL_IMG_BANK . $bank_logo;
                }
            }

            $response = [
                'message' => 'Bank Perusahaan',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_administrator_group()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('site_administrator_group')->orderBy('administrator_group_id', 'ASC')->getWhere([
                "administrator_group_is_active" => "1",
                "administrator_group_id !=" => "1",
            ])->getResult();

            $response = [
                'message' => 'List data hak akses administrator',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_administrator_menu()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('site_administrator_menu')->orderBy('administrator_menu_order_by', 'ASC')->getWhere([
                "administrator_menu_is_active" => "1",
                "administrator_menu_par_id" => "0",
            ])->getResult();
            foreach ($obj_result as $key => $value) {
                $obj_result[$key]->submenu = $this->db->table('site_administrator_menu')->orderBy('administrator_menu_order_by', 'ASC')->getWhere([
                    "administrator_menu_is_active" => "1",
                    "administrator_menu_par_id" => $value->administrator_menu_id,
                ])->getResult();
            }

            $response = [
                'message' => 'List data menu administrator',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_ref_courier()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('ref_courier')->orderBy('courier_code', 'ASC')->getWhere("courier_is_active = '1'")->getResult();

            $response = [
                'message' => 'List data kurir',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_ref_province()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('ref_province')->orderBy('province_name', 'ASC')->getWhere("province_is_active = '1'")->getResult();

            $response = [
                'message' => 'List data provinsi',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_ref_country()
    {
        try {
            validateMethod($this->request, 'get');

            $obj_result = $this->db->table('ref_country')->orderBy('country_name', 'ASC')->getWhere("country_is_active = '1'")->getResult();

            $response = [
                'message' => 'List data negara',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_ref_city()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $message = 'List data kota';
            $where = "city_is_active = '1'";
            if ((array) $params) {
                if ($params->province_id) {
                    $message = 'List data city by province';
                    $where .= " AND city_province_id = {$params->province_id}";
                }
            }

            $obj_result = $this->db->table('ref_city')->select("city_id, CONCAT(city_type, ' ', city_name) AS city_name")->orderBy('city_name', 'ASC')->getWhere($where)->getResult();

            $response = [
                'message' => $message,
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_ref_subdistrict()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $message = 'List data kecamatan';
            $where = '1';
            if ((array) $params) {
                if ($params->city_id) {
                    $message = 'List data subdistrict by city';
                    $where .= " AND subdistrict_city_id = {$params->city_id}";
                }
            }

            $obj_result = $this->db->table('ref_subdistrict')->orderBy('subdistrict_name', 'ASC')->getWhere($where)->getResult();
            foreach ($obj_result as $key => $value) {
                if ($obj_result[$key]->subdistrict_latitude == null) {
                    $obj_result[$key]->subdistrict_latitude = '';
                }
                if ($obj_result[$key]->subdistrict_longitude == null) {
                    $obj_result[$key]->subdistrict_longitude = '';
                }
            }

            $response = [
                'message' => $message,
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_serial_type()
    {
        try {
            validateMethod($this->request, 'get');

            $sql = "SELECT 'activation' AS serial_type, serial_type_id, serial_type_name FROM sys_serial_type UNION SELECT 'repeatorder' AS serial_type, serial_ro_type_id, serial_ro_type_name FROM sys_serial_ro_type";
            $obj_result = $this->db->query($sql)->getResult();

            $response = [
                'message' => 'List data tipe serial',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_serial_type_activation_upgrade()
    {
        try {
            validateMethod($this->request, 'get');

            $sql = "SELECT 'activation' AS serial_type, serial_type_id, serial_type_name FROM sys_serial_type";
            $obj_result = $this->db->query($sql)->getResult();

            $response = [
                'message' => 'List data tipe serial',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_rank()
    {
        try {
            validateMethod($this->request, 'get');

            $sql = "SELECT rank_id, rank_name, rank_bv, rank_nominal_qualified FROM sys_rank";
            $obj_result = $this->db->query($sql)->getResult();

            $response = [
                'message' => 'List data paket',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_product()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $sql = "SELECT
            product_id,
            product_name,
            product_price,
            product_weight,
            product_image,
            product_bv
            FROM inv_product
            WHERE product_is_active = '1'
            ";
            $obj_result = $this->db->query($sql)->getResult();

            foreach ($obj_result as $key => $value) {
                if ($obj_result[$key]->product_image && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $obj_result[$key]->product_image)) {
                    $obj_result[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . $obj_result[$key]->product_image;
                } else {
                    $obj_result[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . "_default.png";
                }
            }

            $response = [
                'message' => 'List data produk',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_all_product()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $sql = "SELECT
            product_id,
            product_name,
            product_price,
            product_weight,
            product_image,
            product_description,
            -- product_type,
            product_bv
            FROM inv_product
            WHERE product_is_active = '1'
            ";
            $obj_result = $this->db->query($sql)->getResult();

            foreach ($obj_result as $key => $value) {
                if ($obj_result[$key]->product_image && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $obj_result[$key]->product_image)) {
                    $obj_result[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . $obj_result[$key]->product_image;
                } else {
                    $obj_result[$key]->product_image = BASEURL . "/no-image.png";
                }
            }

            $response = [
                'message' => 'List data produk',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_package()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $sql = "SELECT
            product_package_id,
            product_package_name,
            product_package_note,
            SUM(product_package_detail_price * product_package_detail_quantity) AS product_package_price,
            SUM(product_weight * product_package_detail_quantity) AS product_package_weight
            FROM inv_product_package
            JOIN inv_product_package_detail ON product_package_detail_product_package_id = product_package_id
            JOIN inv_product ON product_id = product_package_detail_product_id
            WHERE product_package_type = '{$params->type}' AND product_package_is_active = '1'
            GROUP BY product_package_id
            ";
            $obj_result = $this->db->query($sql)->getResult();

            $response = [
                'message' => 'List data paket',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_delivery_cost()
    {
        $params = getRequestParamsData($this->request, "GET");

        try {
            $this->deliveryLib = new Delivery();
            if ($params->transaction_type == "warehouse") {
                $from = DELIVERY_WAREHOUSE_SUBDISTRICT;
            } else {
                $from = $this->db->table("inv_stockist")->getWhere(["stockist_member_id" => $params->stockist_member_id])->getRow("stockist_city_id");
            }

            if (!$from) {
                $data["results"] = [];
            } else {
                $data["results"] = $this->deliveryLib->deliveryCost($from, $params->transaction_subdistrict_id, $params->transaction_total_weight, $params->transaction_courier_code);
            }

            $this->restLib->responseSuccess("Data ongkos kirim.", $data);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }

    public function get_list_product_with_stock()
    {
        try {
            validateMethod($this->request, 'get');
            $params = getRequestParamsData($this->request, 'get');

            $type = $this->db->table("inv_stockist")->select("stockist_type")->getWhere(["stockist_member_id" => $params->stockist_id])->getRow('stockist_type');
            $product_price = "product_master_stockist_price";

            if ($type == 'mobile') {
                $product_price = "product_mobile_stockist_price";
            }

            $sql = "SELECT
            product_id,
            product_name,
            product_code,
            {$product_price} as product_price,
            product_weight,
            product_image,
            stockist_product_stock_balance,
            warehouse_product_stock_balance
            FROM inv_product
            JOIN inv_warehouse_product_stock ON warehouse_product_stock_product_id = product_id
            JOIN inv_stockist_product_stock ON stockist_product_stock_product_id = product_id
            WHERE product_is_active = '1' AND stockist_product_stock_stockist_member_id = {$params->stockist_id}
            ";
            $obj_result = $this->db->query($sql)->getResult();

            foreach ($obj_result as $key => $value) {
                if ($obj_result[$key]->product_image && file_exists(UPLOAD_PATH . URL_IMG_PRODUCT . $obj_result[$key]->product_image)) {
                    $obj_result[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . $obj_result[$key]->product_image;
                } else {
                    $obj_result[$key]->product_image = UPLOAD_URL . URL_IMG_PRODUCT . "_default.png";
                }

                $obj_result[$key]->stockist_product_stock_balance = (int)$obj_result[$key]->stockist_product_stock_balance;
                $obj_result[$key]->warehouse_product_stock_balance = (int)$obj_result[$key]->warehouse_product_stock_balance;
            }

            $response = [
                'message' => 'List data produk',
                'data' => [
                    'results' => $obj_result,
                ],
            ];

            $this->restLib->responseSuccess($response['message'], $response['data']);
        } catch (\Exception $e) {
            $error = !empty($this->error_source) ? $this->error_source : ['file' => $e->getFile(), 'line' => $e->getLine()];
            $this->restLib->responseFailed($e->getMessage(), 'process', [], $error);
        }
    }
}
