<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\SerialModel;
use Config\Services;
use App\Libraries\Notification;

class Serial extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);

        $this->SerialModel = new SerialModel();
        $this->notification_lib = new Notification();
        $this->arrType = ['sys', 'bin', 'uni'];
    }

    public function getDataSerial($type, $serial = 1)
    {
        if (in_array($type, $this->arrType)) {
            if ($type == 'sys') {
                $tableName = 'sys_serial';
                $columns = array(
                    'serial_id',
                    'serial_pin',
                    'serial_serial_type_id',
                    'serial_is_used',
                    'serial_used_member_id',
                    'serial_used_datetime',
                    'serial_expired_date',
                    'serial_is_active',
                );

                $joinTable = '';

                $whereCondition = "serial_serial_type_id = {$serial}";
            } else {
                $tableName = 'sys_serial_ro';
                $columns = array(
                    'serial_ro_id' => 'serial_id',
                    'serial_ro_pin' => 'serial_pin',
                    'serial_ro_serial_ro_type_id' => 'serial_serial_type_id',
                    'serial_ro_is_used' => 'serial_is_used',
                    'serial_ro_used_member_id' => 'serial_used_member_id',
                    'serial_ro_used_datetime' => 'serial_used_datetime',
                    'serial_ro_expired_date' => 'serial_expired_date',
                    'serial_ro_is_active' => 'serial_is_active',
                );

                $joinTable = '';

                $whereCondition = "serial_ro_serial_ro_type_id = {$serial}";
            }

            $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
            if (count($data['results']) > 0) {
                foreach ($data['results'] as $key => $row) {
                    $data['results'][$key]['serial_used_datetime'] = $row['serial_used_datetime'] ? $this->functionLib->convertDatetime($row['serial_used_datetime'], 'id') : '-';
                    $data['results'][$key]['serial_expired_date'] = $row['serial_expired_date'] ? $this->functionLib->convertDatetime($row['serial_expired_date'], 'id') : '-';
                }
            }
            $this->createRespon(200, 'Data Serial', $data);
        } else {
            $this->createRespon(400, 'Tipe Serial tidak terdaftar');
        }
    }

    public function addRegistrationSerial()
    {
        $validation = Services::validation();
        $validation->setRules([
            'serial.*.member_id' => [
                'label' => 'Member',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'serial.*.serial_qty' => [
                'label' => 'Jumlah Serial',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berisi angka',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $result = [];

            $datetime = date('Y-m-d H:i:s');
            try {
                $serial_detail = [];
                $serial = $this->request->getVar('serial');

                foreach ($serial as $key => $value) {
                    $stock_serial = $this->SerialModel->getStockSerialUpgrade('sys', $value['serial_qty']);

                    array_push(
                        $serial_detail,
                        [
                            'member_name' => $value['member_name'],
                            'member_mobilephone' => $value['member_mobilephone'],
                            'serial' => $stock_serial
                        ]
                    );

                    foreach ($stock_serial as $key => $val) {
                        # code...
                        $data_serial = [
                            'serial_active_datetime' => $datetime,
                            'serial_active_ref_type' => 'member',
                            'serial_active_ref_id' => $value['member_id'],
                            'serial_is_active' => 1,
                            'serial_last_owner_status' => 'member',
                            'serial_last_owner_ref_id' => $value['member_id'],
                            'serial_last_owner_datetime' => $datetime
                        ];

                        $this->db->table('sys_serial')->where('serial_id', $val->serial_id)->update($data_serial);

                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                        }

                        $data_serial_distribution = [
                            'serial_distribution_serial_id' => $val->serial_id,
                            'serial_distribution_serial_type_id' => 1,
                            'serial_distribution_owner_status' => $data_serial['serial_last_owner_status'],
                            'serial_distribution_owner_ref_id' => $data_serial['serial_last_owner_ref_id'],
                            'serial_distribution_owner_datetime' => $data_serial['serial_last_owner_datetime'],
                            'serial_distribution_note' => '',
                        ];

                        $this->db->table('log_serial_distribution')->insert($data_serial_distribution);

                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                        }
                    }
                }

                $this->db->transCommit();

                if (WA_NOTIFICATION_IS_ACTIVE) {
                    $client_name = COMPANY_NAME;
                    $project_name = PROJECT_NAME;
                    $client_wa_cs_number = WA_CS_NUMBER;
                    foreach ($serial_detail as $key => $value) {
                        $arr_serial = "";

                        foreach ($value['serial'] as $key_ => $value_) {
                            $arr_serial .= "
*{$value_->serial_id} : {$value_->serial_pin}*";
                        }

                        $content = "*PENGIRIMAN SERIAL BERHASIL*

Hai {$value['member_name']},
Pengiriman serial berhasil diproses.

Detail serial sebagai berikut:
{$arr_serial}

Jika ada pertanyaan silakan hubungi Customer Service kami
*wa.me/{$client_wa_cs_number} (WA/Telp)*

Terima kasih atas perhatian dan kepercayaan Anda bersama {$client_name}

*-- {$project_name} --*";

                        $this->functionLib->send_waone($content, phoneNumberFilter($value['member_mobilephone']));
                    }
                }
                $this->createRespon(200, 'Berhasil mengirim serial', $result);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['line' => $th->getLine(), 'file' => $th->getFile(), 'trace' => $th->getTrace()]);
            }
        }
    }

    public function sendRepeatOrderSerial()
    {
        $validation = Services::validation();
        $validation->setRules([
            'serial.*.serial_type_id' => [
                'label' => 'Tipe Serial',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'serial.*.member_id' => [
                'label' => 'Member',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'serial.*.serial_qty' => [
                'label' => 'Jumlah Serial',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berisi angka',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $result = array();
            $datetime = date('Y-m-d H:i:s');

            try {
                $serial = $this->request->getVar('serial');

                foreach ($serial as $key => $value) {

                    $serial_type_id = $value['serial_type_id'];
                    $stock_serial = $this->SerialModel->getStockSerial('bin', $value['serial_qty']);
                    $serial_type_price = $this->functionLib->getOne('bin_serial_type', 'serial_type_price', array('serial_type_id' => $serial_type_id));
                    $member_network_code = $this->functionLib->getOne('bin_member_ref', 'member_ref_network_code', array('member_ref_member_id' => $value['member_id']));


                    if (empty($serial_type_price)) {
                        throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                    }

                    foreach ($stock_serial as $key => $val) {
                        # code...
                        $data_serial = [
                            'serial_is_sold' => 1,
                            'serial_sold_member_id' => $value['member_id'],
                            'serial_sold_datetime' => $datetime,
                            'serial_is_active' => 1,
                            'serial_seller_administrator_id' => session('admin')['admin_id'],
                        ];

                        $this->db->table('bin_serial')->where('serial_id', $val->serial_id)->update($data_serial);

                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                        }

                        $data_serial_stockist = [
                            'serial_stockist_serial_id' => $val->serial_id,
                            'serial_stockist_serial_pin' => $val->serial_pin,
                            'serial_stockist_serial_network_code' => $member_network_code,
                            'serial_stockist_serial_type_id' => $serial_type_id,
                            'serial_stockist_serial_buyer_member_id' => $value['member_id'],
                            'serial_stockist_serial_buy_datetime' => $datetime,
                            'serial_stockist_serial_is_active' => 1,
                            'serial_stockist_serial_price' => $serial_type_price
                        ];

                        $this->db->table('bin_serial_stockist')->insert($data_serial_stockist);

                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                        }
                        $result['data'][] = $data_serial_stockist;
                    }
                }

                $this->createRespon(200, 'Berhasil memproses penjualan serial', $result);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), $result);
            }
        }
    }

    public function sendUpgradeSerial()
    {
        $validation = Services::validation();
        $validation->setRules([
            'serial.*.serial_type_id' => [
                'label' => 'Tipe Serial',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'serial.*.member_id' => [
                'label' => 'Member',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'serial.*.serial_qty' => [
                'label' => 'Jumlah Serial',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berisi angka',
                ],
            ],
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $result = array();
            $datetime = date('Y-m-d H:i:s');

            try {
                $serial = $this->request->getVar('serial');

                foreach ($serial as $key => $value) {

                    $stock_serial = $this->SerialModel->getStockSerial('sys', $value['serial_qty'], $value['serial_type_id']);

                    $serial_type_price = $this->functionLib->getOne('sys_serial_type', 'serial_type_price', array('serial_type_id' => $value['serial_type_id']));
                    $member_network_code = $this->functionLib->getOne('bin_member_ref', 'member_ref_network_code', array('member_ref_member_id' => $value['member_id']));

                    if (empty($serial_type_price)) {
                        throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                    }

                    foreach ($stock_serial as $key => $val) {
                        $data_serial = [
                            'serial_is_sold' => 1,
                            'serial_sold_member_id' => $value['member_id'],
                            'serial_sold_datetime' => $datetime,
                            'serial_is_active' => 1,
                            'serial_seller_administrator_id' => session('admin')['admin_id'],
                        ];

                        $this->db->table('sys_serial')->where('serial_id', $val->serial_id)->update($data_serial);

                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                        }

                        $data_serial_stockist = [
                            'serial_stockist_serial_id' => $val->serial_id,
                            'serial_stockist_serial_pin' => $val->serial_pin,
                            'serial_stockist_serial_network_code' => $member_network_code,
                            'serial_stockist_serial_type_id' => $value['serial_type_id'],
                            'serial_stockist_serial_buyer_member_id' => $value['member_id'],
                            'serial_stockist_serial_buy_datetime' => $datetime,
                            'serial_stockist_serial_is_active' => 1,
                            'serial_stockist_serial_price' => $serial_type_price
                        ];

                        $this->db->table('sys_serial_stockist')->insert($data_serial_stockist);

                        if ($this->db->affectedRows() < 0) {
                            throw new \Exception("Terjadi kesalahan, silahkan ulangi beberapa saat lagi.", 1);
                        }
                        $result['data'][] = $data_serial_stockist;
                    }
                }

                $this->createRespon(200, 'Berhasil memproses penjualan serial', $result);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), $result);
            }
        }
    }

    public function getSerialTypeRo()
    {
        try {
            $data = array();
            $data['results'] = $this->SerialModel->getSerialType();

            $this->createRespon(200, "Berhasil mengambil data", $data);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage());
        }
    }

    public function activeSerial($type)
    {
        try {
            if (!in_array($type, $this->arrType)) {
                throw new \Exception("Tipe Serial tidak terdaftar", 1);
            }

            $serial_id = $this->request->getPost('data');
            if (!is_array($serial_id)) {
                throw new \Exception("Belum ada serial yang dipilih", 1);
            }

            $success = $failed = 0;
            foreach ($serial_id as $value) {
                if ($this->SerialModel->active($type, $value, session('admin')['admin_id'])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil mengaktifkan Data Serial!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Serial!';
            }
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage());
        }
    }

    public function nonactiveSerial($type)
    {
        try {
            if (!in_array($type, $this->arrType)) {
                throw new \Exception("Tipe Serial tidak terdaftar", 1);
            }

            $serial_id = $this->request->getPost('data');
            if (!is_array($serial_id)) {
                throw new \Exception("Belum ada serial yang dipilih", 1);
            }

            $success = $failed = 0;
            foreach ($serial_id as $value) {
                if ($this->SerialModel->inactive($type, $value, session('admin')['admin_id'])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            $dataActive = [
                'success' => $success,
                'failed' => $failed,
            ];
            $message = 'Berhasil menonaktifkan Serial!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan Serial!';
            }
            $this->createRespon(200, $message, $dataActive);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage());
        }
    }

    public function getDetailSerial($type)
    {
        if (in_array($type, $this->arrType)) {
            $serial_id = $this->request->getGet('data');

            $result = $this->SerialModel->detailSerial($type, $serial_id);
            if (empty($result)) {
                $this->createRespon(400, 'Tipe Serial tidak terdaftar');
            }

            $result['serial_create_datetime'] = $this->functionLib->convertDatetime($result['serial_create_datetime'], 'id');
            $result['serial_active_datetime'] = $this->functionLib->convertDatetime($result['serial_active_datetime'], 'id');
            $result['serial_last_owner_datetime'] = $this->functionLib->convertDatetime($result['serial_last_owner_datetime'], 'id');
            $result['serial_used_datetime'] = $this->functionLib->convertDatetime($result['serial_used_datetime'], 'id');

            return $this->createRespon(200, 'OK', $result);
        } else {
            $this->createRespon(400, 'Tipe Serial tidak terdaftar');
        }
    }

    public function getTotalSerialPerusahaan($type)
    {
        if (!empty($type)) {
            $result = $this->SerialModel->totalSerialPerusahaan($type);
            if (!$result) {
                return $this->createRespon(400, 'Tidak dapat menemukan data. Stok serial perusahaan kosong.');
            } else {
                return $this->createRespon(200, 'OK', ['result' => $result]);
            }
        } else {
            return $this->createRespon(400, 'Tipe Jaringan tidak boleh kosong');
        }
    }

    public function createSerial()
    {
        if ($this->session->administrator_group_id != 1) {
            $this->createRespon(403, 'Unauthorized');
        }

        $validation = Services::validation();
        $validation->setRules([
            'netType' => [
                'label' => 'Tipe Serial',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'jumlah' => [
                'label' => 'Jumlah Serial',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
        ]);
        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $type = $this->request->getPost('netType');
            $total = $this->request->getPost('jumlah');
            $datetime = date("Y-m-d H:i:s");

            $getlastSerial = $nextSerial = $this->db->query("SELECT MAX(serial_id) AS serial FROM {$type}_serial")->getRow()->serial;
            $code = "";
            if ($type != 'sys') {
                $getlastCode = $this->db->query("SELECT MAX(serial_network_code) AS serial FROM {$type}_serial")->getRow()->serial;
                $code = substr($getlastCode, 3);
                $code = $code + 1;
            }

            for ($c = 1; $c <= $total; $c++) {
                if ($code != "") {
                    $network_code = "BN-" . $code;
                }
                $data[] = [
                    'serial_id' => ++$nextSerial,
                    'serial_pin' => $this->functionLib->generateNumber(6),
                    'serial_network_code' => $network_code,
                    'serial_is_sold' => 0,
                    'serial_is_active' => 0,
                    'serial_is_used' => 0,
                    'serial_create_datetime' => $datetime,
                    'serial_expired_date' => date("Y-m-d", strtotime("+1 year", time())),
                ];
                $code++;
            }
            $builder = $this->db->table($type . '_serial');
            if ($builder->insertBatch($data)) {
                $response = 'Tambah serial berhasil. Silakan download File berikut: <a href="' . base_url('serial/excelCreate/' . $type . '/' . $getlastSerial) . '" target="_blank" class="btn btn-outline-primary mr-1 mb-1><i class="bx bx-file"></i><span class="align-middle ml-25">Download Excel</span></a>';

                $this->createRespon(200, 'OK', ['message' => $response]);
            } else {
                $this->createRespon(400, 'Bad Request', ['message' => 'Gagal Tambah Serial']);
            }
        }
    }

    public function getDataSalesSerial($type)
    {
        $tableName = $type . '_serial';
        $columns = array(
            'serial_id',
            'serial_pin',
            'serial_serial_type_id',
            'serial_is_used',
            'serial_active_ref_id',
            'serial_last_owner_ref_id',
            'buyer_member.member_name' => 'buyer_name',
            'buyer_code.network_code' => 'buyer_network_code',
            'owner_member.member_name' => 'owner_name',
            'owner_code.network_code' => 'owner_network_code',
            'serial_active_datetime AS buy_datetime',
            'serial_used_member_id',
        );
        $joinTable = '
                JOIN sys_member AS owner_member ON serial_last_owner_ref_id = owner_member.member_id
                JOIN sys_network AS owner_code ON owner_code.network_member_id = owner_member.member_id
                JOIN sys_member AS buyer_member ON serial_active_ref_id = buyer_member.member_id
                JOIN sys_network AS buyer_code ON buyer_code.network_member_id = buyer_member.member_id
            ';

        $whereCondition = 'serial_active_ref_id <> 0';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['buy_datetime']) {
                    $data['results'][$key]['buy_datetime'] = $this->functionLib->convertDatetime($row['buy_datetime'], 'id');
                }
            }
        }
        $this->createRespon(200, 'Data Serial', $data);
    }

    public function getDetailSalesSerial($type)
    {
        if (!in_array($type, $this->arrType)) {
            $this->createRespon(400, 'Tipe Serial tidak terdaftar');
        }
        $serial_id = $this->request->getGet('serial_id');
        if (empty($serial_id)) {
            $this->createRespon(200, 'Detail Penjualan Serial Tidak ditemukan', $data);
        }
        $detail_sales_serial = $this->SerialModel->getDetailSalesSerial($type, $serial_id);
        if (!empty($detail_sales_serial)) {
            foreach ($detail_sales_serial as $key => $row) {
                if ($row->tanggal) {
                    $detail_sales_serial[$key]->tanggal = $this->functionLib->convertDatetime($row->tanggal, 'id');
                }
            }
            return $this->createRespon(200, 'OK', $detail_sales_serial);
        } else {
            return $this->createRespon(400, 'Data detail penjualan serial tidak ditemukan.', $detail_sales_serial);
        }
    }

    public function getStockistOption()
    {
        $data = array();
        $data = [];
        try {
            //code...
            $stockistOption = $this->SerialModel->getStockistOption($this->request->getVar('search'));

            foreach ($stockistOption as $key => $value) {
                $data[$key]['id'] = $value->stockist_member_id;
                $data[$key]['text'] = "({$value->network_code}) " . $value->member_name . " ~ {$value->stockist_name}";
                $data[$key]['network_code'] = $value->network_code;
                $data[$key]['member_name'] = $value->member_name;
                $data[$key]['stockist_name'] = $value->stockist_name;
            }


            echo json_encode($data);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage());
        }
    }

    public function getDataSerialUpgrade($type)
    {
        if (in_array($type, $this->arrType)) {
            $tableName = $type . '_serial';
            $columns = array(
                'serial_id',
                'serial_pin',
                'serial_network_code',
                'serial_serial_type_id',
                'serial_pv',
                'serial_is_sold',
                'serial_sold_member_id',
                'serial_sold_datetime',
                'serial_is_used',
                'serial_used_member_id',
                'serial_used_datetime',
                'serial_expired_date',
                'serial_is_active',
            );

            $joinTable = '';

            $whereCondition = "serial_serial_type_id = 3 AND serial_id LIKE 'UPG%'";

            $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
            if (count($data['results']) > 0) {
                foreach ($data['results'] as $key => $row) {
                    $data['results'][$key]['serial_sold_datetime'] = $row['serial_sold_datetime'] ? $this->functionLib->convertDatetime($row['serial_sold_datetime'], 'id') : '-';
                    $data['results'][$key]['serial_used_datetime'] = $row['serial_used_datetime'] ? $this->functionLib->convertDatetime($row['serial_used_datetime'], 'id') : '-';
                    $data['results'][$key]['serial_expired_date'] = $row['serial_expired_date'] ? $this->functionLib->convertDatetime($row['serial_expired_date'], 'id') : '-';
                }
            }
            $this->createRespon(200, 'Data Serial', $data);
        } else {
            $this->createRespon(400, 'Tipe Serial tidak terdaftar');
        }
    }
}
