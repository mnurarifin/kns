<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\Member\Profile_model;

class Profile extends BaseController
{
    public function __construct()
    {
        $this->profile_model = new Profile_model();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Profil");
        $this->template->breadcrumbs(["Profil", "Profil"]);
        $this->template->content("Member/Profile/profileView", $data);
        $this->template->show("Template/Member/main");
    }

    public function password()
    {
        $data = [];

        $this->template->title("Ubah Password");
        $this->template->breadcrumbs(["Profil", "Ubah Password"]);
        $this->template->content("Member/Profile/profilePasswordView", $data);
        $this->template->show("Template/Member/main");
    }

    public function get()
    {
        $this->db->transBegin();
        try {
            $data = $this->profile_model->get(session("member")["member_id"]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data profile.", ["results" => $data]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function update()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");
        $this->validation->setRules([
            "member_name" => [
                "label" => "Nama",
                "rules" => "required|max_length[100]|alpha_numeric_space",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "alpha_numeric_space" => "Format {param} tidak sesuai (alpabet & spasi).",
                ],
            ],
            "member_email" => [
                "label" => "Email",
                "rules" => "required|valid_email|max_length[100]|min_length[1]|is_unique[sys_member.member_email,member_parent_member_id," . session("member")["member_parent_member_id"] . "]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "valid_email" => "Format {field} tidak sesuai.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "valid_email" => "Format tidak sesuai.",
                    "is_unique" => "{field} sudah digunakan.",
                ],
            ],
            "member_mobilephone" => [
                "label" => "No. HP",
                "rules" => "required|max_length[16]|min_length[9]|numeric|check_mobilephone[" . session("member")["member_parent_member_id"] . "]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                    "numeric" => "Format tidak sesuai.",
                    "check_mobilephone" => "Sudah terpakai.",
                ],
            ],
            "member_gender" => [
                "label" => "Jenis Kelamin",
                "rules" => "required|in_list[Laki-laki,Perempuan]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "in_list" => "Format tidak sesuai.",
                ],
            ],
            "member_birth_place" => [
                "label" => "Tempat Lahir",
                "rules" => "required|max_length[30]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_birth_date" => [
                "label" => "Tanggal Lahir",
                "rules" => "required|valid_date[Y-m-d]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "valid_date" => "Format tidak sesuai.",
                ],
            ],
            "member_province_id" => [
                "label" => "Provinsi",
                "rules" => "required|is_not_unique[ref_province.province_id,province_id,{member_province_id}]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "is_not_unique" => "Tidak ditemukan.",
                ],
            ],
            "member_city_id" => [
                "label" => "Kota/Kabupaten",
                "rules" => "required|is_not_unique[ref_city.city_id,city_id,{member_city_id}]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "is_not_unique" => "Tidak ditemukan.",
                ],
            ],
            "member_subdistrict_id" => [
                "label" => "Kecamatan",
                "rules" => "required|is_not_unique[ref_subdistrict.subdistrict_id,subdistrict_id,{member_subdistrict_id}]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "is_not_unique" => "Tidak ditemukan.",
                ],
            ],
            "member_address" => [
                "label" => "Alamat",
                "rules" => "required|max_length[255]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_identity_type" => [
                "label" => "Jenis Identitas",
                "rules" => "required|in_list[KTP,PASPOR]",
                "errors" => [
                    "required" => "tidak boleh kosong.",
                    "in_list" => "Format tidak sesuai.",
                ],
            ],
            "member_identity_no" => [
                "label" => "Nomor KTP",
                "rules" => "required|exact_length[16]|is_unique[sys_member.member_identity_no,member_parent_member_id," . session("member")["member_parent_member_id"] . "]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "exact_length" => "Panjang harus {param} karakter.",
                    "is_unique" => "{field} sudah terpakai.",
                ],
            ],
            "member_bank_account_name" => [
                "label" => "Nama Rekening",
                "rules" => "required|max_length[50]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                ],
            ],
            "member_bank_account_no" => [
                "label" => "Nomor Rekening",
                "rules" => "max_length[50]|check_empty_numeric|is_unique_empty[sys_member.member_bank_account_no,member_parent_member_id," . session("member")["member_parent_member_id"] . "]",
                "errors" => [
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "check_empty_numeric" => "Format {field} tidak sesuai.",
                    "is_unique_empty" => "{field} sudah terpakai.",
                ],
            ],
            // "member_bank_id" => [
            //     "label" => "Bank",
            //     "rules" => "required|is_not_unique[ref_bank.bank_id,bank_id,{member_bank_id}]",
            //     "errors" => [
            //         "required" => "Tidak boleh kosong.",
            //         "is_not_unique" => "Tidak ditemukan.",
            //     ],
            // ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        if (!filter_var($params->member_email, FILTER_VALIDATE_EMAIL)) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", [
                'member_email' => 'Email tidak valid'
            ]);
        }

        $this->db->transBegin();
        try {
            $update = [
                "member_name" => $params->member_name,
                "member_email" => $params->member_email,
                "member_mobilephone" => $params->member_mobilephone,
                "member_job" => $params->member_job,
                "member_gender" => $params->member_gender,
                "member_birth_place" => $params->member_birth_place,
                "member_birth_date" => $params->member_birth_date,
                "member_province_id" => $params->member_province_id,
                "member_city_id" => $params->member_city_id,
                "member_subdistrict_id" => $params->member_subdistrict_id,
                "member_address" => $params->member_address,
                "member_identity_type" => $params->member_identity_type,
                "member_identity_no" => $params->member_identity_no,
                "member_bank_account_name" => $params->member_bank_account_name,
                "member_bank_account_no" => $params->member_bank_account_no,
                "member_bank_id" => $params->member_bank_id,
                "member_bank_branch" => $params->member_bank_branch,
                "member_devisor_name" => $params->member_devisor_name,
                "member_devisor_relation" => $params->member_devisor_relation,
            ];
            $parent_member_id = $this->profile_model->getParent(session("member")["member_id"]);
            $where = ["member_parent_member_id" => $parent_member_id];
            $member = $this->profile_model->edit($update, $where);

            $obj_session = session("member");
            $obj_session["member_name"] = $member->member_name;
            $this->session->set(['member' => $obj_session]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Perubahan data profil berhasil diproses.", ["results" => $member]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function update_image()
    {
        $params = getRequestParamsFile($this->request, "member_image");
        $this->validation->setRules([
            "member_image" => [
                "label" => "Foto Profil",
                "rules" => "uploaded[member_image]|max_size[member_image,2048]|ext_in[member_image,png,jpg]|is_image[member_image]",
                "errors" => [
                    "uploaded" => "Tidak boleh kosong.",
                    "max_size" => "Ukuran maksimal 5MB.",
                    "ext_in" => "Format tidak sesuai (ext_in).",
                    "is_image" => "Format tidak sesuai (image).",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            // rename, simpan dan resize file upload
            $name = $params->getRandomName();
            $filename = "profile_{$name}";
            $params->move(UPLOAD_PATH . URL_IMG_MEMBER, $filename);
            if (!$params->hasMoved()) {
                throw new \Exception("File tidak bisa disimpan.", 1);
            }
            if (!resizeImage(UPLOAD_PATH . URL_IMG_MEMBER, UPLOAD_PATH . URL_IMG_MEMBER . $filename, $filename, 500, 500)) {
                throw new \Exception("File tidak bisa diresize.", 1);
            }

            $update = [
                "member_image" => $filename,
            ];
            $parent_member_id = $this->profile_model->getParent(session("member")["member_id"]);
            $where = ["member_parent_member_id" => $parent_member_id];
            $member = $this->profile_model->edit($update, $where);

            $obj_session = session("member");
            $obj_session["member_image"] = $member->member_image;
            $this->session->set(['member' => $obj_session]);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Perubahan gambar profil berhasil diproses.", ["url" => UPLOAD_URL . URL_IMG_MEMBER . $filename]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }

    public function update_password()
    {
        if (!(session()->has("otp") && session("otp") == TRUE)) {
            $this->restLib->responseFailed("Belum melakukan verifikasi OTP", "process");
        }

        $params = getRequestParamsData($this->request, "POST");
        $this->validation->setRules([
            "password_old" => [
                "label" => "Nama Rekening",
                "rules" => "required|max_length[20]|min_length[6]|check_old_password[{password_old}," . session("member")["member_id"] . "]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                    "check_old_password" => "Password tidak sesuai.",
                ],
            ],
            "password_new" => [
                "label" => "Nama Rekening",
                "rules" => "required|max_length[20]|min_length[6]",
                "errors" => [
                    "required" => "Tidak boleh kosong.",
                    "max_length" => "Panjang maksimal {param} karakter.",
                    "min_length" => "Panjang minimal {param} karakter.",
                ],
            ],
        ])->run((array) $params);
        if ($this->validation->getErrors()) {
            $this->restLib->responseFailed("Silahkan periksa kembali inputan Anda.", "validation", $this->validation->getErrors());
        }

        $this->db->transBegin();
        try {
            $update = [
                "member_account_password" => password_hash($params->password_new, PASSWORD_DEFAULT),
            ];
            $children = $this->profile_model->getChildren(session("member")["member_id"]);
            foreach ($children as $key => $child) {
                $where = ["member_account_member_id" => $child->member_id];
                $member = $this->profile_model->editPassword($update, $where);
            }

            $this->db->transCommit();
            $this->restLib->responseSuccess("Perubahan data bank berhasil diproses.", ["results" => $member]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
