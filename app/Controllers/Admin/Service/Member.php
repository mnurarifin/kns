<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\MemberModel;
use CodeIgniter\CLI\Console;
use Config\Services;
use Firebase\JWT\JWT;

class Member extends BaseServiceController
{
    protected $MemberModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->MemberModel = new MemberModel();
    }

    public function getDataMember($pra = 'mitra')
    {
        $whereCondition = "member_is_network =1";

        $tableName = 'sys_member';
        $columns = array(
            'member_id',
            'member_name',
            'member_email',
            'member_mobilephone',
            'member_gender',
            'member_birth_place',
            'member_birth_date',
            'member_address',
            'member_city_id',
            'member_province_id',
            'member_country_id',
            'member_bank_id',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_bank_city',
            'member_bank_branch',
            'member_identity_type',
            'member_identity_no',
            'member_identity_image',
            'member_join_datetime',
            'member_activation_datetime',
            'member_expired_datetime',
            'member_is_network',
            'member_is_expired',
            'member_status',
            'member_image',
            'member_tax_no',
            'member_mother_name',
            'member_devisor_name',
            'member_devisor_relation',
            'member_devisor_mobilephone',
            'member_job',
            'member_parent_member_id',
            'main.member_account_username' => 'member_account_username',
            'network_code',
            'network_slug',
            'network_product_package_id',
            'network_product_package_name',
            'network_upline_member_id',
            'network_upline_network_code',
            'network_upline_leg_position',
            'network_point',
            'upline.member_account_username' => 'upline_member_account_username',
            'network_sponsor_member_id',
            'network_sponsor_network_code',
            'network_sponsor_leg_position',
            'sponsor.member_account_username' => 'sponsor_member_account_username',
            'network_activation_datetime',
            'network_is_active',
            'network_is_suspended',
            'network_is_free'
        );
        $joinTable = '
            LEFT JOIN sys_network ON network_member_id = member_id
            LEFT JOIN sys_member_account main ON member_account_member_id = member_id
            LEFT JOIN sys_member_account upline ON upline.member_account_member_id = network_upline_member_id
            LEFT JOIN sys_member_account sponsor ON sponsor.member_account_member_id = network_sponsor_member_id
        ';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        foreach ($data['results'] as $key => $row) {
            $data['results'][$key]['member_birth_date'] = convertDate(date("Y-m-d", strtotime($row['member_birth_date'])), 'id');
            if ($row['member_image'] && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $row["member_image"])) {
                $data['results'][$key]['member_image_url'] = UPLOAD_URL . URL_IMG_MEMBER . $row['member_image'];
            } else {
                $data['results'][$key]['member_identity_image_url'] = BASEURL . "/no-image.png";
            }

            if ($row['member_identity_image'] && file_exists(UPLOAD_PATH . URL_IMG_IDENTITY . $row["member_identity_image"])) {
                $data['results'][$key]['member_identity_image_url'] = UPLOAD_URL . URL_IMG_IDENTITY . $row['member_identity_image'];
            } else {
                $data['results'][$key]['member_identity_image_url'] = BASEURL . "/no-image.png";
            }

            if ($row['network_activation_datetime']) {
                $data['results'][$key]['network_activation_datetime'] = $this->functionLib->convertDatetime($row['network_activation_datetime'], 'id');
            }

            if ($row['member_join_datetime']) {
                $data['results'][$key]['member_join_datetime'] = $this->functionLib->convertDatetime(date("Y-m-d", strtotime($row['member_join_datetime'])), 'id');
            }

            if ($row['network_sponsor_member_id']) {
                $data['results'][$key]['network_sponsor_member_name'] = $this->db->query("SELECT member_name FROM sys_member WHERE member_id = {$row['network_sponsor_member_id']}")->getRow("member_name");
            }

            if ($row['network_upline_member_id']) {
                $data['results'][$key]['network_upline_member_name'] = $this->db->query("SELECT member_name FROM sys_member WHERE member_id = {$row['network_upline_member_id']}")->getRow("member_name");
            }

            if ($row["member_parent_member_id"] == $row["member_id"]) {
                $member = $this->db->query("SELECT
                sys_member_registration.member_registration_transaction_type,
                sys_member_registration.member_registration_transaction_id,
                sys_member.member_parent_member_id,
                sys_member.member_id
                FROM sys_member_registration JOIN sys_member ON sys_member.member_id = sys_member_registration.member_id WHERE sys_member.member_id = {$row["member_id"]}")->getRow();
                if (isset($member->member_registration_transaction_type) && $member->member_registration_transaction_type == "warehouse") {
                    $data['results'][$key]["seller"] = "Perusahaan";
                } else if (isset($member->member_registration_transaction_type) && $member->member_registration_transaction_type == "stockist") {
                    $transaction = $this->db->query("SELECT * FROM inv_stockist_transaction JOIN inv_stockist ON stockist_member_id = stockist_transaction_stockist_member_id WHERE stockist_transaction_id = {$member->member_registration_transaction_id}")->getRow();
                    if ($transaction) {
                        $data['results'][$key]["seller"] = $transaction->stockist_name;
                    } else {
                        $data['results'][$key]["seller"] = "-";
                    }
                } else {
                    $data['results'][$key]["seller"] = "-";
                }
            }

            // $data['results'][$key]['network_total_point_left_b'] = $this->db->table('sys_b_reward_achievement')->selectSum('reward_achievement_point_left')->getWhere(['reward_achievement_member_id' => $row['member_id']])->getRow('reward_achievement_point_left');
            // $data['results'][$key]['network_total_point_right_b'] = $this->db->table('sys_b_reward_achievement')->selectSum('reward_achievement_point_right')->getWhere(['reward_achievement_member_id' => $row['member_id']])->getRow('reward_achievement_point_right');

            $data['results'][$key]['member_country'] = 'Indonesia';
            $data['results'][$key]['city_name'] = $this->functionLib->getCityName($row['member_city_id']);
        }

        $this->createRespon(200, 'Data Member', $data);
    }

    public function getDataPramitra()
    {
        $whereCondition = "member_is_network = 0";

        $tableName = 'sys_member_registration';
        $columns = array(
            'member_id',
            'member_name',
            'member_email',
            'member_mobilephone',
            'member_gender',
            'member_birth_place',
            'member_birth_date',
            'member_address',
            'member_city_id',
            'member_province_id',
            'member_country_id',
            'member_bank_id',
            'member_bank_name',
            'member_bank_account_name',
            'member_bank_account_no',
            'member_bank_city',
            'member_bank_branch',
            'member_identity_type',
            'member_identity_no',
            'member_identity_image',
            'member_registration_datetime',
            'member_mother_name',
            'member_devisor_name',
            'member_devisor_relation',
            'member_devisor_mobilephone',
            'member_registration_sponsor_username',
            'member_registration_username',
            'member_is_network',
            'member_registration_transaction_type',
            'member_registration_transaction_id',
        );
        $joinTable = '';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        foreach ($data['results'] as $key => $value) {
            if ($value["member_registration_transaction_type"] == "warehouse") {
                $data['results'][$key]['invoice_url'] = $this->db->table("inv_warehouse_transaction")->join("inv_warehouse_transaction_payment", "warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id")->getWhere(["warehouse_transaction_id" => $value["member_registration_transaction_id"]])->getRow("warehouse_transaction_payment_invoice_url");
            } else if ($value["member_registration_transaction_type"] == "stockist") {
                $data['results'][$key]['invoice_url'] = $this->db->table("inv_stockist_transaction")->join("inv_stockist_transaction_payment", "stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id")->getWhere(["stockist_transaction_id" => $value["member_registration_transaction_id"]])->getRow("stockist_transaction_payment_invoice_url");
            }
            $data['results'][$key]['member_registration_datetime_formatted'] = $this->functionLib->convertDatetime($value['member_registration_datetime'], 'id');
            if ($data['results'][$key]['member_birth_date']) {
                $data['results'][$key]['member_birth_date'] = convertDate(date("Y-m-d", strtotime($value['member_birth_date'])), 'id');
            } else {
                $data['results'][$key]['member_birth_date'] = '-';
            }

            if ($value['member_identity_image'] && file_exists(UPLOAD_PATH . URL_IMG_IDENTITY . $value["member_identity_image"])) {
                $data['results'][$key]['member_identity_image_url'] = UPLOAD_URL . URL_IMG_IDENTITY . $value['member_identity_image'];
            } else {
                $data['results'][$key]['member_identity_image_url'] = BASEURL . "/no-image.png";
            }

            $data['results'][$key]['city_name'] = $this->functionLib->getCityName($value['member_city_id']);
        }

        $this->createRespon(200, 'Data Pramitra', $data);
    }

    public function getDataBonus($pra = 'mitra')
    {
        $whereCondition = "
        member_id = member_parent_member_id
        ";

        $tableName = 'sys_member l1';
        $columns = array(
            '(SELECT 
            bonus_log_date
            FROM sys_bonus_log
            WHERE bonus_log_member_id = member_id
            ORDER BY bonus_log_datetime DESC
            LIMIT 1
            )' => 'update_datetime',
            'network_code',
            'member_name',
            '(SELECT 
                SUM(bonus_sponsor_acc + bonus_gen_node_acc + bonus_power_leg_acc + bonus_matching_leg_acc + bonus_cash_reward_acc) as total_bonus
            FROM sys_bonus b1
            LEFT JOIN sys_member l2 ON l2.member_id = b1.bonus_member_id
            WHERE l2.member_parent_member_id = l1.member_id
            )' => 'total_bonus',
            "IFNULL((SELECT reward_qualified_reward_title
            FROM sys_reward_qualified
            WHERE reward_qualified_member_id = member_id
            ORDER BY reward_qualified_condition_point DESC
            LIMIT 1),'Mitra')
            " => "network_rank",
            "city_name",
        );
        $joinTable = '
            JOIN sys_network ON network_member_id = member_id
            JOIN ref_city ON member_city_id = city_id 
        ';
        $groupBy = 'group by network_code';
        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($data['results'] as $key => $row) {
            $data['results'][$key]['update_datetime'] = convertDate(date("Y-m-d", strtotime($row['update_datetime'])), 'id');
            $data['results'][$key]['total_bonus'] = 'Rp ' . number_format($row['total_bonus'], 0, ',', '.');
        }

        $this->createRespon(200, 'Data Member', $data);
    }

    public function getDataSponsor($pra = 'mitra')
    {
        $whereCondition = "t1.netgrow_sponsor_member_id > 0 AND t3.member_id = t3.member_parent_member_id";
        $whereConditionSub = "";
        $_get = $this->request->getGet();
        if (array_key_exists("filter", $_get)) {
            $filters = $_get["filter"];
            foreach ($filters as $key => $filter) {
                if ($filter["field"] == "update_datetime") {
                    $date_start = explode("::", $filter["value"])[0];
                    $date_end = explode("::", $filter["value"])[1];
                    $whereCondition .= " AND t1.netgrow_sponsor_date BETWEEN '{$date_start}' AND '{$date_end}'";
                    $whereConditionSub .= " AND t2.netgrow_sponsor_date BETWEEN '{$date_start}' AND '{$date_end}'";
                    unset($filters[$key]);
                }
            }
            $_get["filter"] = $filters;
            if (!empty($_get["filter"])) {
                $this->request->setGlobal("get", array_merge($this->request->getGet(), ["filter" => $filters]));
            } else {
                unset($_get["filter"]);
                $this->request->setGlobal("get", $_get);
            }
        }

        $tableName = 'sys_netgrow_sponsor t1';
        $columns = array(
            "COUNT(*)" => "count",
            "network_code" => "network_code",
            "(SELECT netgrow_sponsor_date FROM sys_netgrow_sponsor t2
            WHERE t2.netgrow_sponsor_member_id = t1.netgrow_sponsor_member_id {$whereConditionSub} ORDER BY netgrow_sponsor_date DESC LIMIT 1)" => "update_datetime",
            "t4.member_name" => "member_name",
            "t5.city_name" => "city_name",
        );
        $joinTable = " JOIN sys_network ON network_member_id = t1.netgrow_sponsor_member_id JOIN sys_member t3 ON t3.member_id = netgrow_sponsor_downline_member_id 
        JOIN sys_member t4 ON t4.member_id = netgrow_sponsor_member_id JOIN ref_city t5 ON t4.member_city_id = t5.city_id";
        $groupBy = " GROUP BY netgrow_sponsor_member_id";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);
        foreach ($data['results'] as $key => $row) {
            $data['results'][$key]['update_datetime'] = convertDate(date("Y-m-d", strtotime($row['update_datetime'])), 'id');
        }

        $this->createRespon(200, 'Data Member', $data);
    }

    public function getDataSponsorPeriode($pra = 'mitra')
    {
        $arr_data = [];
        $member_registration = $this->db->query("SELECT * FROM sys_member_registration JOIN sys_network ON network_code = member_registration_sponsor_username JOIN sys_member ON sys_member.member_id = network_member_id
        WHERE DATE(member_registration_status_datetime) BETWEEN '2023-05-28' AND '2023-07-28' AND member_registration_status = 'approved' ORDER BY member_registration_status_datetime ASC")->getResult();

        foreach ($member_registration as $key => $value) {
            if (array_key_exists($value->member_registration_sponsor_username, $arr_data) && $arr_data[$value->member_registration_sponsor_username]["count"] < 10) {
                $arr_data[$value->member_registration_sponsor_username]["count"] = $arr_data[$value->member_registration_sponsor_username]["count"] + 1;
                $arr_data[$value->member_registration_sponsor_username]["last_datetime"] = $value->member_registration_status_datetime;
            } else {
                $arr_data[$value->member_registration_sponsor_username] = [
                    "member_id" => $value->network_member_id,
                    "network_code" => $value->network_code,
                    "member_name" => $value->member_name,
                    "count" => 1,
                    "last_datetime" => $value->member_registration_status_datetime,
                ];
            }
        }

        if (count($arr_data)) {
            usort($arr_data, function ($a, $b) {
                if ($b['count'] == $a["count"]) {
                    return $a['last_datetime'] <=> $b['last_datetime'];
                }
                return $b['count'] <=> $a['count'];
            });
        }

        $arr_data = array_slice($arr_data, 0, 50);

        $data['results'] = $arr_data;
        $data['pagination'] = [
            "current" => "1",
            "detail" => [1],
            "end" => count($arr_data),
            "first_page" => false,
            "last_page" => false,
            "next" => 0,
            "prev" => 0,
            "start" => 1,
            "total_data" => count($arr_data),
            "total_display" => count($arr_data),
            "total_page" => 1,
        ];

        // foreach ($data['results'] as $key => $row) {
        //     $data['results'][$key]['update_datetime'] = convertDate(date("Y-m-d", strtotime($row['update_datetime'])), 'id');
        //     $data['results'][$key]['total_bonus'] = 'Rp ' . number_format($row['total_bonus'], 0, ',', '.');
        // }

        $this->createRespon(200, 'Data Member', $data);
    }

    // public function getOptionMember()
    // {
    //     $data = array();
    //     $data['results'] = $this->MemberModel->getOptionMember();

    //     $this->createRespon(200, 'Data Member', $data);
    // }

    // public function getDataMemberAudit()
    // {
    //     $tableName = 'sys_member_audit_trail';
    //     $columns = array(
    //         'member_audit_trail_id',
    //         'member_audit_trail_network_id',
    //         'member_audit_trail_network_code',
    //         'member_audit_trail_type',
    //         'member_audit_trail_page',
    //         'member_audit_trail_username',
    //         'member_audit_trail_name',
    //         'member_audit_trail_change',
    //         'member_audit_trail_note',
    //         'member_audit_trail_datetime',
    //     );
    //     $joinTable = '';
    //     $whereCondition = '';
    //     $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
    //     foreach ($data['results'] as $key => $row) {
    //         $data['results'][$key]['member_audit_trail_change'] = json_decode($row['member_audit_trail_change'], true);
    //     }

    //     $this->createRespon(200, 'Data Member', $data);
    // }

    public function getTokenLogin()
    {
        $member_id = $this->request->getPost('member_id');

        if (empty($member_id)) {
            $this->createRespon(400, 'Bad Request', ["message" => "ID Mitra kosong, login gagal"]);
        }

        // $payload = $this->MemberModel->getTokenLogin($member_id, $this->request->getIpAddress(), $this->session->administrator_id);

        // if (!$payload['status']) {
        //     $this->createRespon(400, $payload['msg']);
        // }
        $payload = $this->db->query("SELECT * FROM sys_member_account WHERE member_account_member_id = {$member_id}")->getRow();

        $token = JWT::encode($payload,  JWT_KEY);
        $result = getenv('app.voURL') . 'login/sso/' . $token;

        $this->createRespon(200, 'OK', $result);
    }

    // public function getDetailNetwork()
    // {
    //     $member_id = $this->request->getGet('member_id');

    //     if (empty($member_id)) {
    //         $result = array(
    //             "message" => "ID Mitra kosong, login gagal"
    //         );
    //         $this->createRespon(400, 'Bad Request', $result);
    //     }

    //     $result = $this->MemberModel->getDetailNetwork($member_id);
    //     if (!$result) {
    //         $this->createRespon(400, 'Gagal mendapatkan data detail jaraingan member');
    //     }

    //     $this->createRespon(200, 'OK', $result);
    // }

    public function actEditProfil()
    {
        $validation = \Config\Services::validation();
        $member_parent_member_id = $this->db->query("SELECT member_parent_member_id FROM sys_member WHERE member_id = {$this->request->getPost('id')}")->getRow("member_parent_member_id");
        $validation->setRules([
            "member_name" => [
                "label" => "Nama",
                "rules" => "required|max_length[100]",
                "errors" => [
                    "required" => "{field} Tidak boleh kosong.",
                    "max_length" => "{field} Panjang maksimal {param} karakter.",
                ],
            ],
            "member_gender" => [
                "label" => "Jenis Kelamin",
                "rules" => "required|in_list[Laki-laki,Perempuan]",
                "errors" => [
                    "required" => "{field} Tidak boleh kosong.",
                    "in_list" => "Format {field} tidak sesuai.",
                ],
            ],
            "member_mobilephone" => [
                "label" => "No. HP",
                "rules" => "required|max_length[16]|min_length[9]|numeric",
                "errors" => [
                    "required" => "{field} Tidak boleh kosong.",
                    "max_length" => "{field} Panjang maksimal {param} karakter.",
                    "min_length" => "{field} Panjang minimal {param} karakter.",
                    "numeric" => "Format {field} tidak sesuai.",
                    "check_mobilephone" => "{field} Sudah terpakai.",
                ],
            ],
            "member_email" => [
                "label" => "Email",
                "rules" => "required|max_length[100]|min_length[1]|is_unique[sys_member.member_email,member_parent_member_id,{$member_parent_member_id}]",
                "errors" => [
                    "required" => "{field} Tidak boleh kosong.",
                    "max_length" => "{field} Panjang maksimal {param} karakter.",
                    "valid_email" => "Format {field} tidak sesuai.",
                    "is_unique" => "{field} sudah digunakan.",
                ],
            ],
            'member_identity_type' => [
                'label' => 'Jenis Identitas',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            "member_identity_no" => [
                "label" => "Nomor KTP",
                "rules" => "required|exact_length[16]|is_unique[sys_member.member_identity_no,member_parent_member_id,{$member_parent_member_id}]",
                "errors" => [
                    "required" => "{field} tidak boleh kosong.",
                    "exact_length" => "{field} Panjang harus {param} karakter.",
                    "is_unique" => "{field} sudah terpakai.",
                ],
            ],
            "member_bank_account_no" => [
                "label" => "Nomor Rekening",
                "rules" => "max_length[50]|check_empty_numeric|is_unique_empty[sys_member.member_bank_account_no,member_parent_member_id,{$member_parent_member_id}]",
                "errors" => [
                    "max_length" => "{field} Panjang maksimal {param} karakter.",
                    "check_empty_numeric" => "Format {field} tidak sesuai.",
                    "is_unique_empty" => "{field} sudah terpakai.",
                ],
            ],
            "network_slug" => [
                "label" => "Nama",
                "rules" => "required|alpha_numeric|is_unique_empty[(SELECT * FROM sys_network JOIN sys_member ON member_id = network_member_id) t.network_slug,member_parent_member_id,{$member_parent_member_id}]",
                "errors" => [
                    "required" => "{field} Tidak boleh kosong.",
                    "alpha_numeric" => "{field} Hanya boleh huruf dan angka",
                    "is_unique_empty" => "{field} sudah terpakai.",
                ],
            ],
        ]);

        $check_slug = $this->db->table("sys_network")->select("network_slug")->join('sys_member', 'member_id = network_member_id')->where("network_slug", $this->request->getPost('network_slug'))->where("member_parent_member_id <> '{$member_parent_member_id}'")->get()->getRow();

        if ($check_slug) {
            $result = array(
                "validationMessage" => [
                    "network_slug" => "Username telah digunakan"
                ],
            );
            $this->createRespon(400, 'validationError', $result);
        }

        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                $member_id = $this->request->getPost('id');
                if (empty($member_id)) {
                    throw new \Exception("Data Member tidak bisa diubah", 1);
                }

                $member = $this->MemberModel->getMember($member_id);
                $network = $this->MemberModel->getNetwork($member_id);

                $member_bank_name = '';
                if ($this->request->getPost('member_bank_id')) {
                    $member_bank_name = $this->MemberModel->getBankName($this->request->getPost('member_bank_id'));
                }

                $member_image = $member->member_image;
                if (!is_null($this->request->getFile('member_image'))) {
                    $path = UPLOAD_PATH . URL_IMG_MEMBER;
                    $memberImage = $this->request->getFile('member_image');
                    if ($memberImage->isValid() && file_exists($memberImage)) {
                        if ($memberImage->getName() != '') {
                            $extension = pathinfo($memberImage->getName(), PATHINFO_EXTENSION);
                            $fileName = $member_id . '-' . date("YmdHis") . '.' . $extension;
                            $memberImage->move($path, $fileName);
                            $member_image = $fileName;
                            if ($member->member_image) {
                                @unlink($path . $member->member_image);
                            }
                        }
                    }
                }

                $arr_update_member = [
                    'member_name' => addslashes($this->request->getPost('member_name')),
                    'member_gender' => $this->request->getPost('member_gender'),
                    'member_birth_place' => addslashes($this->request->getPost('member_birth_place')),
                    'member_birth_date' => $this->request->getPost('member_birth_date'),
                    'member_mobilephone' => phoneNumberFilter($this->request->getPost('member_mobilephone')),
                    'member_email' => $this->request->getPost('member_email'),
                    'member_city_id' => $this->request->getPost('member_city_id'),
                    'member_province_id' => $this->request->getPost('member_province_id'),
                    'member_address' => $this->request->getPost('member_address'),
                    'member_identity_type' => $this->request->getPost('member_identity_type'),
                    'member_identity_no' => $this->request->getPost('member_identity_no'),
                    'member_image' => $member_image,
                    'member_bank_id' => $this->request->getPost('member_bank_id'),
                    'member_bank_name' => $member_bank_name,
                    'member_bank_city' => addslashes($this->request->getPost('member_bank_city')),
                    'member_bank_branch' => addslashes($this->request->getPost('member_bank_branch')),
                    'member_bank_account_name' => addslashes($this->request->getPost('member_bank_account_name')),
                    'member_bank_account_no' => addslashes($this->request->getPost('member_bank_account_no')),
                    'member_mother_name' => addslashes($this->request->getPost('member_mother_name')),
                    'member_devisor_name' => addslashes($this->request->getPost('member_devisor_name')),
                    'member_devisor_relation' => addslashes($this->request->getPost('member_devisor_relation')),
                    'member_devisor_mobilephone' => $this->request->getPost('member_devisor_mobilephone'),
                    'member_job' => $this->request->getPost('member_job'),
                ];
                $member_parent_member_id = $this->db->table('sys_member')->getWhere(['member_id' => $member_id])->getRow("member_parent_member_id");
                $this->db->table('sys_member')->update($arr_update_member, ['member_parent_member_id' => $member_parent_member_id]);
                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal mengubah data member", 1);
                }

                $allnetwork = $this->db->table("sys_member")->select("member_id")->getWhere(["member_parent_member_id" => $member_parent_member_id])->getResult();
                foreach ($allnetwork as $key => $value) {
                    $this->db->table("sys_network")->where("network_member_id", $value->member_id)->update(["network_slug" => $this->request->getPost('network_slug')]);

                    if ($this->db->affectedRows() < 0) {
                        throw new \Exception("Gagal mengubah data member", 1);
                    }
                }

                $arr_label_name = [
                    'member_name'                   => 'Nama member',
                    'member_gender'                 => 'Jenis Kelamin',
                    'member_birth_place'            => 'Tempat Lahir',
                    'member_birth_date'             => 'Tanggal Lahir',
                    'member_mobilephone'            => 'No. Handphone',
                    'member_email'                  => 'Email',
                    'member_city_id'                => 'Kota',
                    'member_province_id'            => 'Provinsi',
                    'member_address'                => 'Alamat member',
                    'member_identity_type'          => 'Jenis Identitas',
                    'member_identity_no'            => 'No Identitas',
                    'member_image'                  => 'Foto Member',
                    'member_bank_id'                => 'Bank',
                    'member_bank_name'              => 'Nama Bank',
                    'member_bank_city'              => 'Kota Bank',
                    'member_bank_branch'            => 'Cabang Bank',
                    'member_bank_account_name'      => 'Nama akun bank',
                    'member_bank_account_no'        => 'No akun bank',
                    // 'member_identity_image'         => 'Foto identitas member',
                    'member_mother_name'            => 'Nama Ibu Kandung',
                    'member_devisor_name'           => 'Nama Ahli Waris',
                    'member_devisor_relation'       => 'Hubungan Ahli Waris',
                    'member_devisor_mobilephone'    => 'No. Handphone Ahli Waris'
                ];
                $arr_data = array();
                foreach ($member as $key => $value) {
                    if (array_key_exists($key, $arr_label_name)) {
                        array_push($arr_data, array(
                            'audit_field' => $key,
                            'audit_field_name' =>  $arr_label_name[$key],
                            'audit_before' => $value,
                            'audit_after' => $arr_update_member[$key]
                        ));
                    }
                }

                // $data_audit_member = [
                //     'member_audit_trail_network_id' => $network->network_id,
                //     'member_audit_trail_network_code' => $network->network_code,
                //     'member_audit_trail_page' => 'Halaman admin',
                //     'member_audit_trail_type' => 'Admin',
                //     'member_audit_trail_datetime' => date('Y-m-d H:i:s'),
                //     'member_audit_trail_username' => $this->session->administrator_username,
                //     'member_audit_trail_name' => $this->session->administrator_name,
                //     'member_audit_trail_change' => json_encode($arr_data),
                //     'member_audit_trail_note' => 'Mengubah data profil member ' . $network->network_code,
                //     'member_audit_trail_query' => addslashes($this->db->getLastQuery()),
                // ];
                // $this->db->table('sys_member_audit_trail')->insert($data_audit_member);
                // if ($this->db->affectedRows() <= 0) {
                //     throw new \Exception("Gagal menyimpan data audit", 1);
                // }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', ["message" => "Data member berhasil diubah"]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ["line" => $th->getLine(), "file" => $th->getFile()]);
            }
        }
    }

    public function actEditPassword()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'password_new' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'min_length' => '{field} harus lebih dari {param} karakter'
                ],
            ],
            'password_conf' => [
                'label' => 'Ulangi Password Baru',
                'rules' => 'required|matches[password_new]',
                'errors' => [
                    'required' => 'Ulangi Password tidak boleh kosong!',
                    'matches' => 'Password yang anda masukan tidak sama!'
                ]
            ],
        ]);
        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array("validationMessage" => $validation->getErrors());
            $this->createRespon(400, 'validationError', $result);
        } else {
            $this->db->transBegin();
            try {
                if (!$this->request->getPost('member_id')) {
                    throw new \Exception("ID member tidak ditemukan", 1);
                }

                $children = $this->db->table("sys_member")->getWhere(["member_parent_member_id" => $this->request->getPost('member_id')])->getResult();
                foreach ($children as $key => $child) {
                    $this->db->table('sys_member_account')->update(
                        ['member_account_password' => password_hash($this->request->getPost('password_new'), PASSWORD_DEFAULT)],
                        ['member_account_member_id' => $child->member_id]
                    );
                    if ($this->db->affectedRows() <= 0) {
                        throw new \Exception("Password member gagal diubah", 1);
                    }
                }

                $this->db->transCommit();
                $this->createRespon(200, 'OK', ["message" => "Password member berhasil diubah"]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ["line" => $th->getLine(), "file" => $th->getFile()]);
            }
        }
    }

    public function resetPinMember()
    {
        $data['results'] = array();

        try {
            if (!$this->request->getPost('member_id')) {
                throw new \Exception("ID member tidak ditemukan", 1);
            }

            $this->db->table('sys_member_account')->update(['member_account_pin' => ''], ['member_account_member_id' => $this->request->getPost('member_id')]);
            if ($this->db->affectedRows() < 0) {
                throw new \Exception("Gagal reset pin", 1);
            }

            $this->db->transCommit();
            $this->createRespon(200, 'OK', ["message" => "Pin member berhasil direset"]);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), ["line" => $th->getLine(), "file" => $th->getFile()]);
        }
    }

    public function activeMember()
    {
        $member_id = $this->request->getPost('data');

        if (is_array($member_id)) {
            $success = $failed = 0;
            foreach ($member_id as $value) {
                if ($this->MemberModel->activeMember($value)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil mengaktifkan Data Member!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan Data Member';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diaktifkan!');
        }
    }

    public function deactiveMember()
    {
        $member_id = $this->request->getPost('data');

        if (is_array($member_id)) {
            $success = $failed = 0;
            foreach ($member_id as $value) {
                if ($this->MemberModel->deactiveMember($value)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil nonaktifkan Data Member!';
            if ($success == 0) {
                $message = 'Gagal nonaktifkan Data Member';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang dinonaktifkan!');
        }
    }

    public function suspendMember()
    {
        $member_id = $this->request->getPost('data');

        if (is_array($member_id)) {
            $success = $failed = 0;
            foreach ($member_id as $value) {
                if ($this->MemberModel->suspendMember($value)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil memblokir Data Member!';
            if ($success == 0) {
                $message = 'Gagal memblokir Data Member';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diblokir!');
        }
    }

    public function unsuspendMember()
    {
        $member_id = $this->request->getPost('data');

        if (is_array($member_id)) {
            $success = $failed = 0;
            foreach ($member_id as $value) {
                if ($this->MemberModel->unsuspendMember($value)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil memblokir Data Member!';
            if ($success == 0) {
                $message = 'Gagal memblokir Data Member';
            }
            $this->createRespon(200, $message, $dataActive);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang diblokir!');
        }
    }

    public function deleteMember()
    {
        // $member_id = $this->request->getPost('data');

        // if (is_array($member_id)) {
        //     $success = $failed = 0;
        //     foreach ($member_id as $value) {
        //         if ($this->MemberModel->deleteMember($value)) {
        //             $success++;
        //         } else {
        //             $failed++;
        //         }
        //     }
        //     $dataActive = [
        //         'success' => $success,
        //         'failed' => $failed
        //     ];
        //     $message = 'Berhasil menghapus Data Member!';
        //     if ($success == 0) {
        //         $message = 'Gagal menghapus Data Member';
        //     }
        //     $this->createRespon(200, $message, $dataActive);
        // } else {
        //     $this->createRespon(400, 'Anda belum memilih data yang dihapus!');
        // }
    }

    public function getDataForm()
    {
        $data = array();
        $data['identity_type_options'] = identityTypeOption();
        $data['bank_options'] = $this->MemberModel->getBankOptions();

        $this->createRespon(200, 'Data Form', $data);
    }

    public function getProvince()
    {
        $data = array();
        $data['results'] = $this->MemberModel->getProvince();

        $this->createRespon(200, 'Data Form', $data);
    }

    public function getCityByProvince($province_id)
    {
        $data = array();
        $data['results'] = $this->MemberModel->getCityByProvince($province_id);

        $this->createRespon(200, 'Data Form', $data);
    }

    public function getMemberOptions()
    {
        $data = array();
        $data = $this->MemberModel->getMemberOptions($this->request->getVar('search'));

        $this->createRespon(200, 'Data Member', $data);
    }

    public function getStockistOptions()
    {
        $data = array();
        $data = $this->MemberModel->getStockistOptions($this->request->getVar('search'));

        echo json_encode($data);
    }

    public function getStockistMobileOptions()
    {
        $data = array();
        $data = $this->MemberModel->getStockistMobileOptions($this->request->getVar('search'));

        echo json_encode($data);
    }

    public function getDataStock()
    {
        $tableName = 'inv_member_product_stock';
        $columns = array(
            'member_product_stock_id',
            'member_product_stock_member_id',
            'member_product_stock_product_id',
            'member_product_stock_balance',
            'product_name',
            'member_name',
            'member_account_username' => 'member_username',
        );
        $joinTable = ' JOIN inv_product ON product_id = member_product_stock_product_id JOIN sys_member ON member_id = member_product_stock_member_id
        JOIN sys_member_account ON member_account_member_id = member_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getDataStockLog()
    {
        $tableName = 'inv_member_product_stock_log';
        $columns = array(
            'member_product_stock_log_id',
            'member_product_stock_log_member_id',
            'member_product_stock_log_product_id',
            'member_product_stock_log_type',
            'member_product_stock_log_quantity',
            'member_product_stock_log_unit_price',
            'member_product_stock_log_balance',
            'member_product_stock_log_note',
            'member_product_stock_log_datetime',
            'product_name',
            'member_name',
            'member_account_username' => 'member_username',
        );
        $joinTable = ' JOIN inv_product ON product_id = member_product_stock_log_product_id JOIN sys_member ON member_id = member_product_stock_log_member_id
        JOIN sys_member_account ON member_account_member_id = member_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $row) {
                if ($row['member_product_stock_log_datetime']) {
                    $data['results'][$key]['member_product_stock_log_datetime_formatted'] = $this->functionLib->convertDatetime($row['member_product_stock_log_datetime']);
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }
}
