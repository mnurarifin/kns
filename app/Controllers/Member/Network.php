<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Network_model;
use App\Models\Common_model;
use App\Libraries\Notification;
use App\Controllers\Partner\Callback;

class Network extends BaseController
{
    public function __construct()
    {
        $this->network_model = new Network_model();
        $this->common_model = new Common_model();
        $this->notification_lib = new Notification();
        $this->mlm_service = service("Mlm");
        $this->transaction_service = service("Transaction");
        $this->network_service = service('Network');
        $this->registration_service = service('Registration');
        $this->activation_service = service('Activation');
    }

    public function index()
    {
        $this->genealogy();
    }

    public function genealogy()
    {
        $data = [];

        $this->template->title("Pohon Jaringan");
        $this->template->breadcrumbs(["Jaringan", "Pohon Jaringan"]);
        $this->template->content("Member/Network/networkGenealogyView", $data);
        $this->template->show("Template/Member/main");
    }

    public function sponsor()
    {
        $data = [];
        $this->template->title("Sponsor Pribadi");
        $this->template->breadcrumbs(["Jaringan", "Sponsor Pribadi"]);
        $this->template->content("Member/Network/networkSponsorView", $data);
        $this->template->show("Template/Member/main");
    }

    public function downline()
    {
        $data = [];
        $this->template->title("Downline");
        $this->template->breadcrumbs(["Jaringan", "Downline"]);
        $this->template->content("Member/Network/networkDownlineView", $data);
        $this->template->show("Template/Member/main");
    }

    public function netgrow()
    {
        $data = [];
        $this->template->title("Pertumbuhan Jaringan");
        $this->template->breadcrumbs(["Jaringan", "Pertumbuhan Jaringan"]);
        $this->template->content("Member/Network/networkNetgrowView", $data);
        $this->template->show("Template/Member/main");
    }

    public function registration()
    {
        $data = [];
        $this->template->title("Registrasi");
        $this->template->breadcrumbs(["Jaringan", "Registrasi"]);
        $this->template->content("Member/Network/networkRegistrationView", $data);
        $this->template->show("Template/Member/main");
    }

    public function registration_success()
    {
        $data = [];
        $this->template->title("Registrasi Berhasil");
        $this->template->breadcrumbs(["Jaringan", "Registrasi Berhasil"]);
        $this->template->content("Member/Network/networkRegistrationSuccessView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get_genealogy_old()
    {
        $params = getRequestParamsData($this->request, "GET");
        $params->username = property_exists($params, "username") ? $params->username : session("member")["member_account_username"];

        $this->validation->setRules([
            "top_username" => [
                "label" => "Username",
                "rules" => "is_not_unique[sys_member_account.member_account_username]|check_crossline_unilevel[" . session("member")["member_account_username"] . "]",
                "errors" => [
                    "is_not_unique" => "{field} tidak ditemukan.",
                    "check_crossline_unilevel" => "Upline dan sponsor crossline.",
                ],
            ],
            "username" => [
                "label" => "Username Downline",
                "rules" => "is_not_unique[sys_member_account.member_account_username]|check_crossline_unilevel[" . session("member")["member_account_username"] . "]",
                "errors" => [
                    "is_not_unique" => "{field} tidak ditemukan.",
                    "check_crossline_unilevel" => "Upline dan sponsor crossline.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            if ($params->top_username == session("member")["member_account_username"]) {
                $network_code = $this->common_model->usernameToNetworkCode($params->username);
                $genealogy = $this->mlm_service->get_genealogy_unilevel(session("member")["network_code"], $network_code);
            } else {
                $network_code = $this->common_model->usernameToNetworkCode($params->top_username);
                $genealogy = $this->mlm_service->get_genealogy_unilevel(session("member")["network_code"], $network_code);
            }

            $this->db->transCommit();
            echo json_encode($genealogy);
            die();
            $this->restLib->responseSuccess("Data Genealogy.", ["results" => $genealogy]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function get_genealogy()
    {
        $params = getRequestParamsData($this->request, "GET");

        $this->validation->setRules([
            "network_code" => [
                "label" => "Downline",
                "rules" => "is_not_unique[sys_member_account.member_account_username]|check_crossline_genealogy_unilevel[" . session("member")["member_account_username"] . "]",
                "errors" => [
                    "is_not_unique" => "{field} tidak ditemukan.",
                    "check_crossline_genealogy_unilevel" => "Crossline.",
                ],
            ],
            "type" => [
                "label" => "Tipe",
                "rules" => "required|in_list[upline,downline]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "in_list" => "Format {field} tidak sesuai.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            if ($params->type == "upline") {
                $genealogy = $this->mlm_service->get_genealogy_unilevel("upline", $params->network_code, session("member")["member_account_username"]);
            } else {
                $genealogy = $this->mlm_service->get_genealogy_unilevel("downline", $params->network_code, session("member")["member_account_username"]);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data Genealogy.", ["results" => $genealogy]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function get_sponsor()
    {
        $params = getRequestParamsData($this->request, "GET");

        $username = session("member")["member_account_username"];
        if (property_exists($params, "username")) {
            $this->validation->setRules([
                "username" => [
                    "label" => "Username Downline",
                    "rules" => "is_not_unique[sys_member_account.member_account_username]",
                    "errors" => [
                        "is_not_unique" => "{field} tidak ditemukan.",
                    ],
                ],
            ])->run((array) $params);
            if ($this->validation->getErrors()) {
                $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
            }
            $username = $params->username;
        }

        $this->db->transBegin();
        try {
            $this->mlm_service->init($this->request);
            $network_code = $this->common_model->usernameToNetworkCode($username);
            $data = $this->mlm_service->get_data_table_sponsoring(session("member")["member_id"], $network_code);

            foreach ($data['results'] as $key => $value) {
                // $data['results'][$key]['network_rank_name'] = $value['network_rank_name'] != '' ? $value['network_rank_name'] : '-';
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data sponsor pribadi.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_downline()
    {
        $this->db->transBegin();
        try {
            $this->mlm_service->init($this->request);
            $data = $this->mlm_service->get_data_table_downline(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data downline.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function get_netgrow()
    {
        $this->db->transBegin();
        try {
            $this->mlm_service->init($this->request);
            $data = $this->mlm_service->get_data_table_netgrow(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data pertumbuhan jaringan.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function register()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

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
                "label" => "Nomor KTP",
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
                "rules" => "required|max_length[16]|min_length[9]|numeric", //|check_mobilephone",
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

    public function clone()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "sponsor_username_clone" => [
                "label" => "Username Sponsor",
                "rules" => "required|is_not_unique[sys_member_account.member_account_username]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "upline_username_clone" => [
                "label" => "Username Sponsor",
                "rules" => "required|is_not_unique[sys_member_account.member_account_username]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak ditemukan.",
                ],
            ],
            "network_position_clone" => [
                "label" => "Posisi",
                "rules" => "required|in_list[L,R]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "in_list" => "Format {field} tidak sesuai.",
                ],
            ],
            "serial_id_clone" => [
                "label" => "Serial",
                "rules" => "required|is_not_unique[sys_serial.serial_id]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "is_not_unique" => "{field} tidak valid.",
                ],
            ],
            "serial_pin_clone" => [
                "label" => "Pin Serial",
                "rules" => "required|check_serial_pin[{serial_id_clone}]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "check_serial_pin" => "{field} tidak sesuai.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $params->network_code = $this->network_service->get_network_code();
            $parent_username = $this->network_model->getParentMemberUsername(session("member")["member_id"]);
            $params->sponsor_username = $params->sponsor_username_clone;
            $params->upline_username = $params->upline_username_clone;
            $params->network_position = $params->network_position_clone;
            $member = $this->network_model->getMemberByMemberId(session("member")["member_id"]);
            $params->member_account_username = $parent_username . "_" . $this->network_model->getCloneSuffix(session("member")["member_id"]);
            $params->member_account_password = $member->member_account_password;
            $params->member_name = $member->member_name;
            $params->member_gender = $member->member_gender;
            $params->member_email = $member->member_email;
            $params->member_identity_no = $member->member_identity_no;
            $params->member_mobilephone = $member->member_mobilephone;
            $params->member_birth_place = $member->member_birth_place;
            $params->member_birth_date = $member->member_birth_date;
            $params->member_province_id = $member->member_province_id;
            $params->member_city_id = $member->member_city_id;
            $params->member_subdistrict_id = $member->member_subdistrict_id;
            $params->member_address = $member->member_address;
            $params->member_bank_account_name = $member->member_bank_account_name;
            $params->member_bank_account_no = $member->member_bank_account_no;
            $params->member_bank_id = $member->member_bank_id;
            $params->member_identity_image = $member->member_identity_image;
            $params->parent_member_id = $member->member_parent_member_id;

            $params->is_free = FALSE; // cloning
            $params->serial_id = $params->serial_id_clone;
            $params->network_sponsor_network_code = $this->common_model->usernameToNetworkCode($params->sponsor_username);
            $params->network_upline_network_code = $this->common_model->usernameToNetworkCode($params->upline_username);

            $registration = $this->registration_service->execute($params);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Kloning mitra berhasil diproses.", ["results" => $registration]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function upgrade()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "serial_id_upgrade" => [
                "label" => "Serial",
                "rules" => "required|check_serial_member_stock[" . session("member")["member_id"] . "]|check_serial_member_upgrade[" . session("member")["member_id"] . "]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "check_serial_ro_member_stock" => "{field} tidak tersedia.",
                    "check_serial_member_upgrade" => "Paket harus lebih tinggi dari paket yang sekarang.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $this->upgrade_services = service("upgrade");
            $this->upgrade_services->set_datetime($this->datetime);
            $this->upgrade_services->set_date($this->date);
            $this->upgrade_services->set_yesterday($this->yesterday);

            $data = (object) [
                "member_id" => session("member")["member_id"],
                "serial_id" => $params->serial_id_upgrade,
            ];
            $upgrade = $this->upgrade_services->execute($data);

            $obj_session = session("member");
            $obj_session["can_upgrade"] = $this->network_model->getUpgrade(session("member")["member_id"]);
            $this->session->set(['member' => $obj_session]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Upgrade berhasil diproses.", ["results" => $upgrade]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function activation()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");

        $this->validation->setRules([
            "member_registration_id" => [
                "label" => "Pra Mitra",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
            "network_upline_network_code" => [
                "label" => "Upline",
                "rules" => "required",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $member_registration = $this->common_model->getDetail("sys_member_registration", "member_registration_id", $params->member_registration_id);
            $transaction = $this->common_model->getDetail(
                "inv_{$member_registration->member_registration_transaction_type}_transaction",
                "{$member_registration->member_registration_transaction_type}_transaction_id",
                $member_registration->member_registration_transaction_id
            );
            $this->activation_service->set_placement($params->network_upline_network_code);

            $type = $member_registration->member_registration_transaction_type;
            $data = (object)[
                "member_id" => $member_registration->member_id,
                "member_registration_sponsor_username" => $member_registration->member_registration_sponsor_username,
                "network_code" => $member_registration->member_registration_username,
                "member_network_slug" => $member_registration->member_network_slug,
                "parent_member_id" => $member_registration->member_id,
                "transaction_code" => $transaction->{"{$type}_transaction_code"},
                "member_mobilephone" => $member_registration->member_mobilephone,
                "member_tax_no" => $member_registration->member_tax_no,
            ];

            // $activation = $this->activation_service->execute($data, $transaction->{"{$member_registration->member_registration_transaction_type}_transaction_total_price"});

            $clone = $this->check_clone($type, $transaction->{"{$type}_transaction_id"});
            $activation = $this->activation_service->execute($data, $clone->hu_price[0]);
            for ($i = 0; $i < $clone->count; $i++) {
                $params = $this->db->table("sys_member")->join("sys_member_account", "member_account_member_id = member_id")->join("sys_network", "network_member_id = member_id")->getWhere(["member_id" => $member_registration->member_id])->getRow();
                $member_count = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $member_registration->member_id])->getResult();
                $suffix = count($member_count) + 1;
                $params->member_registration_sponsor_username = $params->network_code;
                $params->network_code = "{$params->network_code}-{$suffix}";
                $params->parent_member_id = $params->member_id;
                $params->datetime = $this->datetime;

                $this->callback = new Callback();
                $registration = $this->callback->registration($params);

                $params->member_id = $registration->member_id;
                $params->member_network_slug = $params->network_slug;
                $params->transaction_code = $transaction->{"{$type}_transaction_code"};

                // $total_price = $transaction->{"{$type}_transaction_total_price"};
                $total_price = $transaction->{"{$type}_transaction_total_price"};
                $this->activation_service->set_placement($params->member_registration_sponsor_username);
                $activation_service = service("Activation");
                // $activation_service->execute($params, $total_price, "repeatorder");
                $activation_service->execute($params, $clone->hu_price[$i + 1], "repeatorder");
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Placement mitra berhasil diproses.");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => getenv('CI_ENVIRONMENT') == 'development' ? $e->getTrace() : []]);
        }
    }

    public function check_clone($type, $transaction_id)
    {
        $transaction_detail = $this->db->table("inv_{$type}_transaction_detail")->getWhere(["{$type}_transaction_detail_{$type}_transaction_id" => $transaction_id])->getResult();
        $clone = 0;
        $hu_price = [];
        foreach ($transaction_detail as $detail) {
            $clone += $detail->{"{$type}_transaction_detail_quantity"};
            for ($i = 0; $i < $detail->{"{$type}_transaction_detail_quantity"}; $i++) {
                $hu_price[] = $detail->{"{$type}_transaction_detail_unit_price"};
            }
        }

        return (object)[
            "count" => $clone - 1, // -1 karena id utama sudah terdaftar
            "hu_price" => $hu_price,
        ];
    }

    public function get_wait_placement()
    {
        $this->db->transBegin();
        try {
            $this->network_model->init($this->request);
            $data = $this->network_model->getWaitPlacement(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data mitra baru.", $data);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
