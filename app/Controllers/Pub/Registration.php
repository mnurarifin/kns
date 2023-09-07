<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Registration_model;
use App\Libraries\Notification;

class Registration extends BaseController
{
    public function __construct()
    {
        $this->registration_model = new Registration_model();
        $this->network_service = service("Network");
        $this->registration_service = service("Registration");
        $this->transaction_service = service("Transaction");
        $this->payment_service = service("Payment");
        $this->notification_lib = new Notification();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Registrasi");
        $this->template->breadcrumbs(["Registrasi", "Registrasi"]);
        $this->template->content("Pub/Registration/registrationView", $data);
        $this->template->show("Template/Pub/blank");
    }

    public function registration_success()
    {
        $data = [];

        $this->template->title("Registrasi Sukses");
        $this->template->breadcrumbs(["Registrasi", "Registrasi Sukses"]);
        $this->template->content("Pub/Registration/registrationSuccessView", $data);
        $this->template->show("Template/Pub/blank");
    }

    public function get_member_name()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->db->transBegin();
        try {
            $data = [];
            if (property_exists($params, "network_slug")) {
                $data = $this->registration_model->getMemberName($params->network_slug);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data mitra.", ["results" => $data]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function register()
    {
        $params = getRequestParamsData($this->request, "POST");
        // if (BASEURL != "http://localhost:8080") {
        $this->validation->setRules([
            "sponsor_username" => [
                "label" => "Username Sponsor",
                "rules" => "required|is_not_unique[sys_member_account.member_account_username]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            // "serial_id" => [
            //     "label" => "Serial",
            //     "rules" => "required|is_not_unique[sys_serial.serial_id]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "is_not_unique" => "{field} tidak valid.",
            //     ],
            // ],
            // "serial_pin" => [
            //     "label" => "Pin Serial",
            //     "rules" => "required|check_serial_pin[{serial_id}]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "check_serial_pin" => "{field} tidak sesuai.",
            //     ],
            // ],
            // "member_account_username" => [
            //     "label" => "Username",
            //     "rules" => "required|is_unique[sys_member_account.member_account_username]|alpha_numeric|max_length[50]|min_length[6]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "is_unique" => "{field} sudah digunakan.",
            //         "alpha_numeric" => "{field} format tidak sesuai.",
            //         "max_length" => "Panjang maksimal {param} karakter.",
            //         "min_length" => "Panjang minimal {param} karakter.",
            //     ],
            // ],
            "member_name" => [
                "label" => "Nama",
                "rules" => "required|max_length[100]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_account_password" => [
                "label" => "Password",
                "rules" => "required|max_length[20]|min_length[6]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                ],
            ],
            "member_identity_no" => [
                "label" => "Nomor identitas",
                "rules" => "required|exact_length[16]|check_identity_no|is_unique[sys_member.member_identity_no]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "exact_length" => "Panjang harus {param} karakter.",
                    "check_identity_no" => "{field} sudah terpakai.",
                    "is_unique" => "{field} sudah terpakai.",
                ],
            ],
            "member_mobilephone" => [
                "label" => "No. HP",
                "rules" => "required|max_length[16]|min_length[9]|numeric|check_mobilephone",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                    "numeric" => "Format {field} tidak sesuai.",
                    "check_mobilephone" => "{field} sudah terpakai.",
                ],
            ],
            "member_province_id" => [
                "label" => "Provinsi",
                "rules" => "required|is_not_unique[ref_province.province_id,province_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "member_city_id" => [
                "label" => "Kota/Kabupaten",
                "rules" => "required|is_not_unique[ref_city.city_id,city_id,{member_city_id}]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "member_subdistrict_id" => [
                "label" => "Kecamatan",
                "rules" => "required|is_not_unique[ref_subdistrict.subdistrict_id,subdistrict_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "member_address" => [
                "label" => "Alamat",
                "rules" => "required|max_length[255]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_bank_account_name" => [
                "label" => "Nama Rekening",
                "rules" => "required|max_length[50]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            // "member_bank_account_no" => [
            //     "label" => "Nomor Rekening",
            //     "rules" => "required|max_length[50]|numeric|is_unique[sys_member.member_bank_account_no]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "max_length" => "Panjang maksimal {param} karakter.",
            //         "numeric" => "Format {field} tidak sesuai.",
            //         "is_unique" => "{field} sudah terpakai.",
            //     ],
            // ],
            // "member_bank_id" => [
            //     "label" => "Bank",
            //     "rules" => "required|is_not_unique[ref_bank.bank_id,bank_id]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "is_not_unique" => "{field} tidak ditemukan.",
            //     ],
            // ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }
        // }

        $date = new \DateTime($params->member_birth_date);
        $now = new \DateTime();
        $interval = $now->diff($date);
        if ($interval->y < 18) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", ['member_birth_date' => 'Minimal usia member 18 tahun']);
        }

        $member_identity_image = $this->request->getFile('member_identity_image');

        if (!$member_identity_image) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", ['member_identity_image' => 'Foto KTP tidak boleh kosong']);
        }

        $this->db->transBegin();
        try {
            $name = $member_identity_image->getRandomName();
            $filename = "identity_{$name}";
            $member_identity_image->move(UPLOAD_PATH . URL_IMG_IDENTITY, $filename);
            if (!$member_identity_image->hasMoved()) {
                throw new \Exception("File tidak bisa disimpan.", 1);
            }

            $params->member_identity_image = $filename;
            $params->member_account_password_raw = $params->member_account_password;
            $params->member_account_password = password_hash($params->member_account_password, PASSWORD_DEFAULT);
            $params->network_sponsor_network_code = $this->common_model->usernameToNetworkCode($params->sponsor_username);
            $params->network_code = $this->network_service->get_network_code();
            $registration = $this->registration_service->execute($params);

            $this->db->transCommit();

            if (WA_NOTIFICATION_IS_ACTIVE) {
                $client_url = URL_PRODUCTION;
                $client_name = COMPANY_NAME;
                $project_name = PROJECT_NAME;
                $client_wa_cs_number = WA_CS_NUMBER;
                $content = "*Registrasi Pra-Mitra Berhasil*

Hai, {$params->member_name}
Pendaftaran pra-mitra berhasil diproses, berikut data Anda:

Username: {$registration['network_code']}
Password: {$params->member_account_password}
Sponsor: {$registration['sponsor_username']} ({$registration['sponsor_member_name']})

Untuk mengelola bisnis Anda, silakan login ke {$client_url}/login dengan menggunakan username dan password diatas.

Segera lakukan Aktivasi dengan Pembelian Produk Awal melalui stokis terdekat minimal 50 BV.

Terima kasih atas kepercayaan Anda bersama {$client_name}.

Jika Anda punya pertanyaan, silakan hubungi customer service kami di:
wa.me/{$client_wa_cs_number} (WA/Telp)

*-- {$project_name} --*
                ";

                $this->notification_lib->send_waone($content, phoneNumberFilter($params->member_mobilephone));
            }
            $this->restLib->responseSuccess("Pendaftaran mitra baru berhasil diproses.", ["results" => $registration]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function save()
    {
        $params = getRequestParamsData($this->request, "POST");
        // if (BASEURL != "http://localhost:8080") {
        $validations = [
            "sponsor_username" => [
                "label" => "Kode Sponsor",
                "rules" => "required|is_not_unique[sys_member_account.member_account_username]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            // "serial_id" => [
            //     "label" => "Serial",
            //     "rules" => "required|is_not_unique[sys_serial.serial_id]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "is_not_unique" => "{field} tidak valid.",
            //     ],
            // ],
            // "serial_pin" => [
            //     "label" => "Pin Serial",
            //     "rules" => "required|check_serial_pin[{serial_id}]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "check_serial_pin" => "{field} tidak sesuai.",
            //     ],
            // ],
            // "member_account_username" => [
            //     "label" => "Username",
            //     "rules" => "required|is_unique[sys_member_account.member_account_username]|alpha_numeric|max_length[50]|min_length[6]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "is_unique" => "{field} sudah digunakan.",
            //         "alpha_numeric" => "{field} format tidak sesuai.",
            //         "max_length" => "Panjang maksimal {param} karakter.",
            //         "min_length" => "Panjang minimal {param} karakter.",
            //     ],
            // ],
            "member_name" => [
                "label" => "Nama",
                "rules" => "required|max_length[100]|alpha_numeric_space",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "alpha_numeric_space" => "Format {param} tidak sesuai (alpabet & spasi).",
                ],
            ],
            "member_account_password" => [
                "label" => "Password",
                "rules" => "required|max_length[20]|min_length[6]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                ],
            ],
            "member_mobilephone" => [
                "label" => "No. HP",
                "rules" => "required|max_length[16]|min_length[9]|numeric|check_mobilephone",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                    "numeric" => "Format {field} tidak sesuai.",
                    "check_mobilephone" => "{field} sudah terpakai.",
                ],
            ],
         
            "member_email" => [
                "label" => "Email",
                "rules" => "required|valid_email|max_length[100]|min_length[1]|is_unique[sys_member.member_email]|check_email",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "valid_email" => "Format {field} tidak sesuai.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                    "is_unique" => "{field} sudah digunakan.",
                    "check_email" => "{field} sudah digunakan.",
                ],
            ],
            "member_province_id" => [
                "label" => "Provinsi",
                "rules" => "required|is_not_unique[ref_province.province_id,province_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "member_city_id" => [
                "label" => "Kota/Kabupaten",
                "rules" => "required|is_not_unique[ref_city.city_id,city_id,{member_city_id}]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "member_subdistrict_id" => [
                "label" => "Kecamatan",
                "rules" => "required|is_not_unique[ref_subdistrict.subdistrict_id,subdistrict_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "member_address" => [
                "label" => "Alamat Sesuai KTP",
                "rules" => "required|max_length[255]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_bank_account_name" => [
                "label" => "Nama Rekening",
                "rules" => "required|max_length[50]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_bank_account_no" => [
                "label" => "Nomor Rekening",
                "rules" => "max_length[50]|check_empty_numeric|is_unique_empty[sys_member.member_bank_account_no]|check_bank_account_no",
                "errors" => [
                    // "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    // "numeric" => "Format {field} tidak sesuai.",
                    "check_empty_numeric" => "Format {field} tidak sesuai.",
                    "is_unique_empty" => "{field} sudah terpakai.",
                    "check_bank_account_no" => "{field} sudah terpakai.",
                ],
            ],
            'member_job' => [
                'label' => 'Pekerjaan',
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'max_length' => 'Panjang maksimal {param} karakter.',
                ],
            ],
            // "member_bank_id" => [
            //     "label" => "Bank",
            //     "rules" => "required|is_not_unique[ref_bank.bank_id,bank_id]",
            //     "errors" => [
            //         "required" => "{field} tidak boleh kosong.",
            //         "is_not_unique" => "{field} tidak ditemukan.",
            //     ],
            // ],
            "type" => [
                "label" => "Tipe",
                "rules" => "required|in_list[warehouse,stockist]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "in_list" => "Format {field} tidak sesuai.",
                ],
            ],
            "network_slug" => [
                "label" => "Username",
                "rules" => "required|max_length[50]|is_unique[sys_network.network_slug]|alpha_numeric|check_slug",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "is_unique" => "{field} sudah digunakan.",
                    "check_slug" => "{field} sudah digunakan.",
                    "alpha_numeric" => "{field} format tidak sesuai.",
                ],
            ],
            "select_product_id" => [
                "label" => "Produk",
                "rules" => "required|check_json",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "check_json" => "{field} tidak boleh kosong.",
                ],
            ],
            'member_identity_type' => [
                'label' => 'Tipe Identitas',
                'rules' => 'required|in_list[KTP,PASPOR]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'in_list' => 'Format {field} tidak sesuai.',
                ],
            ],
        ];

        $selectedIdentityType = $params->member_identity_type ?? 'KTP';
        
        if (isset($selectedIdentityType) && $selectedIdentityType === 'KTP') {
            $validations['member_identity_no'] = [
                "label" => "Nomor KTP / Passport",
                "rules" => "numeric|exact_length[16]|required|is_unique[sys_member.member_identity_no]|check_identity_no",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "exact_length" => "Format {field} tidak sesuai.",
                    "is_unique" => "{field} sudah terpakai.",
                    "check_identity_no" => "{field} sudah terpakai.",
                    "numeric" => "{field} harus menggunakan angka .",
                ],
            ];
        }  else if (isset($selectedIdentityType) && $selectedIdentityType === 'PASPOR') {
            $validations['member_identity_no'] = [
                "label" => "Nomor KTP / Passport",
                "rules" => "required|min_length[6]|max_length[20]|is_unique[sys_member.member_identity_no]|check_identity_no",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "min_length" => "{field} minimal 6 karakter.",
                    "max_length" => "{field} maksimal 20 karakter.",
                    "is_unique" => "{field} sudah terpakai.",
                    "check_identity_no" => "{field} sudah terpakai.",
                ],
            ];
        }
        
        $this->validation->setRules($validations)->run((array)$params);

        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        if (!filter_var($params->member_email, FILTER_VALIDATE_EMAIL)) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", [
                'member_email' => 'Email tidak valid'
            ]);
        }

        if ($params->transaction_delivery_method == "courier") {
            $this->validation->setRules([
                "transaction_courier_code" => [
                    "label" => "Kurir",
                    "rules" => "required",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                    ],
                ],
                "transaction_courier_service" => [
                    "label" => "Layanan",
                    "rules" => "required",
                    "errors" => [
                        "required" => "{field} tidak boleh kosong.",
                    ],
                ],
            ])->run((array) $params);
            if ($this->validation->getErrors()) {
                $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
            }
        }

        // /* cek umur */
        // $date = new \DateTime($params->member_birth_date);
        // $now = new \DateTime();
        // $interval = $now->diff($date);
        // if ($interval->y < 18) {
        //     $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", ['member_birth_date' => 'Minimal usia member 18 tahun']);
        // }

        // $member_identity_image = $this->request->getFile('member_identity_image');

        // if (!$member_identity_image) {
        //     $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", ['member_identity_image' => 'Foto KTP tidak boleh kosong']);
        // }

        $this->db->transBegin();
        try {
            $product_trx = [];
            // $name = $member_identity_image->getRandomName();
            // $filename = "identity_{$name}";
            // $member_identity_image->move(UPLOAD_PATH . URL_IMG_IDENTITY, $filename);
            // if (!$member_identity_image->hasMoved()) {
            //     throw new \Exception("File tidak bisa disimpan.", 1);
            // }

            // $params->member_identity_image = $filename;
            $params->member_account_password_raw = $params->member_account_password;
            $params->member_account_password = password_hash($params->member_account_password, PASSWORD_DEFAULT);
            $params->network_sponsor_network_code = $this->common_model->usernameToNetworkCode($params->sponsor_username);
            // $sponsor_id = $this->common_model->usernameToMemberId($params->sponsor_username);
            // $params->network_code = $this->network_service->get_network_code();
            $params->network_code = "";
            $registration = (object)$this->registration_service->save($params);

            if ($params->type == "warehouse") {
                $transaction_code = $this->transaction_service->generate_transaction_code("warehouse");
            } else {
                $stockist_type = $this->db->query("SELECT * FROM inv_stockist WHERE stockist_member_id = '{$params->stockist_member_id}'")->getRow("stockist_type");
                $transaction_code = $this->transaction_service->generate_transaction_code($stockist_type == "mobile" ? "stockist" : $stockist_type);
            }

            $select_product_id = json_decode($params->select_product_id);
            $params->detail = [];
            foreach ($select_product_id as $product_id => $qty) {
                $params->detail[] = [
                    "product_id" => $product_id,
                    "quantity" => $qty,
                ];
            }

            $arr_product = [];
            $product_price = 0;
            // $params->member_account_username = $this->network_service->get_network_code();
            foreach ($params->detail as $key => $detail) {
                $product = $this->common_model->getDetail("inv_product", "product_id", $detail["product_id"]);
                $item_nett = TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE == "percent" ?
                    $product->product_price - $product->product_price * TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT / 100 :
                    $product->product_price - TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE;
                $arr_product[] = [
                    "product_id" => $product->product_id,
                    "quantity" => $detail["quantity"],
                    "unit_price" => $product->product_price,
                    "discount_type" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE,
                    "discount_percent" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT,
                    "discount_value" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE,
                    "unit_nett_price" => $item_nett,
                ];
                $product_price += $product->product_price * $detail["quantity"];

                array_push($product_trx, [
                    'product_name' => $product->product_name,
                    'quantity' => $detail["quantity"],
                ]);
            }

            $nett = TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE == "percent" ?
                $product_price - $product_price * TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT / 100 :
                $product_price - TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE;
            $nett += $params->transaction_delivery_method == "courier" ? $params->cost : 0;
            $transaction_data = (object) [
                "transaction_code" => $transaction_code,
                "buyer_member_id" => 0,
                "buyer_type" => "member",
                "member_name" => $params->member_name,
                "member_address" => $params->member_address,
                "member_mobilephone" => $params->country_phone_code . str_replace("+62", "", $params->member_mobilephone),
                "member_email" => $params->member_email,
                "total_price" => $product_price,
                "extra_discount_type" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE,
                "extra_discount_percent" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT,
                "extra_discount_value" => TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE,
                "delivery_method" => $params->transaction_delivery_method,
                "delivery_courier_code" => $params->transaction_delivery_method == "courier" ? $params->transaction_courier_code : "",
                "delivery_courier_service" => $params->transaction_delivery_method == "courier" ? $params->transaction_courier_service : "",
                "delivery_cost" => $params->transaction_delivery_method == "courier" ? $params->cost : 0,
                "delivery_receiver_name" => $params->transaction_delivery_method == "courier" ? $params->name : NULL,
                "delivery_receiver_mobilephone" => $params->transaction_delivery_method == "courier" ? $params->mobilephone : NULL,
                "delivery_receiver_address" => $params->transaction_delivery_method == "courier" ? $params->address : NULL,
                "delivery_receiver_province_id" => $params->transaction_delivery_method == "courier" ? $params->province_id : NULL,
                "delivery_receiver_city_id" => $params->transaction_delivery_method == "courier" ? $params->city_id : NULL,
                "delivery_receiver_subdistrict_id" => $params->transaction_delivery_method == "courier" ? $params->subdistrict_id : NULL,
                "total_nett_price" => $nett,
                "status" => "pending",
                "note" => "{$transaction_code}: Pembelian Paket {$product->product_name} untuk Registrasi Mitra oleh {$params->sponsor_username} kepada {$params->member_name}",
                "warehouse_id" => "1",
                "stockist_member_id" => $params->stockist_member_id,
                "arr_product" => $arr_product,
                "type" => "activation",
            ];


            $transaction = (object)$this->transaction_service->execute($transaction_data, $params->type);

            if ($params->type == "warehouse") {
                $transaction_status_data = [
                    "warehouse_transaction_status_warehouse_transaction_id" => $transaction->transaction_id,
                    "warehouse_transaction_status_value" => $transaction_data->status,
                    "warehouse_transaction_status_datetime" => $this->datetime,
                    "warehouse_transaction_status_ref_type" => "member",
                    "warehouse_transaction_status_ref_id" => 0,
                ];
                $this->transaction_service->addStatus($transaction_status_data);
            } else {
                $transaction_status_data = [
                    "stockist_transaction_status_stockist_transaction_id" => $transaction->transaction_id,
                    "stockist_transaction_status_value" => $transaction_data->status,
                    "stockist_transaction_status_datetime" => $this->datetime,
                    "stockist_transaction_status_ref_type" => "member",
                    "stockist_transaction_status_ref_id" => 0,
                ];
                $this->transaction_service->addStatusStockist($transaction_status_data);
            }
            $this->registration_service->update_transaction_id($transaction->transaction_id, $registration->member_registration_id);

            $payment = $this->payment_service->execute(
                $params->type == "warehouse" ? "warehouse" : "stockist",
                $transaction->transaction_id,
                $params->member_email,
                "Pembayaran transaksi kode : {$transaction->transaction_code}",
                $nett
            );

            $this->db->transCommit();

            if (!empty($product_trx) && WA_NOTIFICATION_IS_ACTIVE) {
                $trx = '';
                foreach ($product_trx as $key => $value) {
                    $trx .= "
{$value['product_name']} - {$value['quantity']}";
                }
                $method = $params->transaction_delivery_method == 'courier' ? 'Kirim' : 'Ambil';
                $client_url = URL_PRODUCTION;
                $client_name = COMPANY_NAME;
                $client_wa_cs_number = WA_CS_NUMBER;
                $total_pay = setNumberFormat($nett);
                $content = "*Registrasi Berhasil!*
Bapak/Ibu *{$params->member_name}* Terima kasih telah melakukan pembelanjaan produk perdana di {$client_name}.

Rincian belanja Anda:
{$trx}

Total yang harus dibayarkan senilai *Rp {$total_pay}*
Metode pengiriman *{$method}*

Segera lakukan pembayaran sesuai dengan rincian di atas.

Terima kasih atas kepercayaan anda bersama kami.

Untuk bantuan dan informasi lebih lanjut seilahkan menghubungi Customer Service Kimstella wa.me/{$client_wa_cs_number}

*Kimstella* 
_Semua bisa sukses!_
{$client_url}";

                $this->notification_lib->send_waone($content, $params->country_phone_code . str_replace("+62", "", $params->member_mobilephone));
            }
            $this->restLib->responseSuccess("Pendaftaran mitra baru berhasil diproses.", ["results" => array_merge((array)$registration, (array)$transaction, (array)$payment)]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }
}
