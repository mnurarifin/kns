<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\InvestorModel;
use Config\Services;

class Investor extends BaseServiceController
{

    private $investorModel;
    private $payment_service;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->investorModel = new InvestorModel();
        $this->db = \Config\Database::connect();
        $this->payment_service = service("Payment");
    }

    public function getDataInvestor()
    {
        $tableName = 'report_investor';
        $columns = array(
            'investor_id',
            'investor_title',
            'investor_percentage',
            'investor_join_date',
            'investor_join_datetime',
            'investor_update_datetime',
            'investor_is_active',
            'administrator_group_id',
            'administrator_group_title',
        );
        $joinTable = ' JOIN site_administrator_group ON administrator_group_id = investor_administrator_group_id';
        $whereCondition = "";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($value['investor_join_datetime']) {
                    $data['results'][$key]['investor_join_datetime_formatted'] = $this->functionLib->convertDatetime($value['investor_join_datetime']);
                }
                if ($value['investor_join_date']) {
                    $data['results'][$key]['investor_join_date_formatted'] = $this->functionLib->convertDatetime($value['investor_join_date']);
                }
                if ($value['investor_update_datetime']) {
                    $data['results'][$key]['investor_update_datetime_formatted'] = $this->functionLib->convertDatetime($value['investor_update_datetime']);
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getDataLogInvestor()
    {
        $tableName = 'report_investor_log';
        $columns = array(
            "investor_log_id",
            "investor_log_investor_id",
            "investor_log_investor_title",
            "investor_log_investor_percentage",
            "investor_log_datetime",
            "investor_title",
            "investor_percentage",
            "investor_log_investor_administrator_group_id",
            "administrator_group_id",
            "administrator_group_title",
        );
        $joinTable = ' JOIN report_investor ON investor_id = investor_log_investor_id JOIN site_administrator_group ON administrator_group_id = investor_log_investor_administrator_group_id';
        $admin_id = session("admin")["admin_id"];
        $administrator_administrator_group_id = $this->db->query("SELECT administrator_administrator_group_id FROM site_administrator WHERE administrator_id = {$admin_id}")->getRow("administrator_administrator_group_id");
        $whereCondition = " administrator_group_id = {$administrator_administrator_group_id}";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        // get last query

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($value['investor_log_datetime']) {
                    $data['results'][$key]['investor_log_datetime_formatted'] = $this->functionLib->convertDatetime($value['investor_log_datetime']);
                }
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getWithdrawalInvestor()
    {
        $tableName = 'report_investor_withdrawal';
        $columns = array(
            "investor_withdrawal_id",
            "investor_withdrawal_investor_id",
            "investor_administrator_group_id",
            "investor_title",
            "investor_withdrawal_value",
            "investor_withdrawal_investor_acc_before",
            "investor_withdrawal_bank_id",
            "investor_withdrawal_bank_name",
            "investor_withdrawal_account_name",
            "investor_withdrawal_account_no",
            "investor_withdrawal_bank_branch",
            "investor_withdrawal_bank_city",
            "investor_withdrawal_status",
            "investor_withdrawal_datetime"
        );
        $joinTable = "
        JOIN report_investor ON investor_id = investor_withdrawal_investor_id
        JOIN site_administrator_group ON administrator_group_id = investor_administrator_group_id
        ";

        $admin_id = session("admin")["admin_id"];
        $administrator_administrator_group_id = $this->db->query("SELECT administrator_administrator_group_id FROM site_administrator WHERE administrator_id = {$admin_id}")->getRow("administrator_administrator_group_id");
        $whereCondition = " administrator_group_id = {$administrator_administrator_group_id}";


        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        foreach ($data['results'] as $key => $value) {
            if ($value['investor_withdrawal_datetime']) {
                $data['results'][$key]['investor_withdrawal_datetime'] = $this->functionLib->convertDatetime($value['investor_withdrawal_datetime']);
            }
            if ($value['investor_withdrawal_value']) {
                $data['results'][$key]['investor_withdrawal_value'] = $this->functionLib->setNumberFormat($value['investor_withdrawal_value'], 2);
            }
        }


        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getWithdrawalAdmin($status = 'pending')
    {
        $tableName = 'report_investor_withdrawal';
        $columns = array(
            "investor_withdrawal_id",
            "investor_withdrawal_investor_id",
            "investor_administrator_group_id",
            "investor_title",
            "investor_withdrawal_value",
            "investor_withdrawal_investor_acc_before",
            "investor_withdrawal_bank_id",
            "investor_withdrawal_bank_name",
            "investor_withdrawal_account_name",
            "investor_withdrawal_account_no",
            "investor_withdrawal_bank_branch",
            "investor_withdrawal_bank_city",
            "investor_withdrawal_status",
            "investor_withdrawal_datetime"
        );
        $joinTable = "
        JOIN report_investor ON investor_id = investor_withdrawal_investor_id
        JOIN site_administrator_group ON administrator_group_id = investor_administrator_group_id
        ";

        $whereCondition = "";

        if ($status) {
            $whereCondition = " investor_withdrawal_status = 'pending'";
        }

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

        foreach ($data['results'] as $key => $value) {
            if ($value['investor_withdrawal_datetime']) {
                $data['results'][$key]['investor_withdrawal_datetime'] = $this->functionLib->convertDatetime($value['investor_withdrawal_datetime']);
            }
            if ($value['investor_withdrawal_value']) {
                $data['results'][$key]['investor_withdrawal_value'] = $this->functionLib->setNumberFormat($value['investor_withdrawal_value'], 2);
            }
        }


        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function getAllInvestor()
    {
        try {
            $data  = $this->db->table('report_investor')->get()->getResultArray();

            $arr_data = [];
            if (count($data) > 0) {
                $i = 0;
                foreach ($data as $key => $value) {
                    $arr_data[$i]['id'] = $value['investor_id'];
                    $arr_data[$i]['text'] = $value['investor_title'];
                    $i++;
                }
            }

            echo json_encode($arr_data);
        } catch (\Throwable $th) {
            $this->createRespon(400, 'Investor tidak ditemukan', ['results' => []]);
        }
    }

    public function getTotalInvestor($investor_id = '')
    {
        if ($investor_id != '') {
            $investor = $this->db->table('report_investor')->getWhere([
                'investor_id' => $investor_id,
            ])->getRow();
        } else {
            $admin_id = session("admin")["admin_id"];

            $sql = "SELECT * FROM report_investor LEFT JOIN site_administrator ON administrator_administrator_group_id = investor_administrator_group_id WHERE administrator_id = {$admin_id}";
            $investor = $this->db->query($sql)->getRow();
        }

        if ($investor) {
            $investor_percentage = $investor->investor_percentage;

            $row = $this->investorModel->getTotal($investor_percentage);

            $row->paid = $investor->investor_paid;
            $row->balance = $row->total_price_percent - $investor->investor_paid - $investor->investor_pending_payment;
            $row->pending = $investor->investor_pending_payment;
        } else {
            $row = $this->investorModel->getTotal(0);
            $row->total_price_percent = 0;
            $row->paid = 0;
            $row->balance = 0;
            $row->pending = 0;
        }

        // format
        $row->total_price = number_format($row->total_price, 0, ',', '.');
        $row->total_price_percent = number_format($row->total_price_percent, 0, ',', '.');
        $row->count = number_format($row->count, 0, ',', '.');
        $row->paid = number_format($row->paid, 0, ',', '.');
        $row->balance = number_format($row->balance, 0, ',', '.');
        $row->pending = number_format($row->pending, 0, ',', '.');

        $this->createRespon(200, 'Berhasil mengambil data.', $row);
    }

    public function getDataReportInvestor($type, $investor_id = '')
    {
        $investor = null;

        if ($investor_id) {
            $investor = $this->db->table('report_investor')->getWhere([
                'investor_id' => $investor_id,
            ])->getRow();

            if (is_null($investor)) {
                throw new \Exception("Investor tidak ditemukan.", 1);
            }
        }

        if ($type == "monthly") {
            $type = "MONTH";
            if ($investor) {
                $where_date = date("m", strtotime($investor->investor_join_date));
            }
        } else if ($type == "weekly") {
            $type = "WEEK";
            if ($investor) {
                $where_date = date("W", strtotime($investor->investor_join_date));
            }
        } else {
            $type = "DATE";
            if ($investor) {
                $where_date = $investor->investor_join_date;
            }
        }
        if ($investor) {
            $where_year = date("Y", strtotime($investor->investor_join_date));
        }

        $tableName = "sys_member";
        $columns = array(
            "COUNT(*)" => 'count',
            "transaction_total_price" => "total",
            "DATE(member_join_datetime)" => 'date',
            "MONTH(member_join_datetime)" => 'month',
            "WEEK(member_join_datetime)" => 'week',
            "YEAR(member_join_datetime)" => 'year',
        );
        $joinTable = " JOIN (
            SELECT member_join_date, SUM(transaction_total_price) AS transaction_total_price FROM (
                SELECT member_join_date, SUM(transaction_total_price) AS transaction_total_price FROM (
                SELECT warehouse_transaction_total_price AS transaction_total_price, DATE(member_join_datetime) AS member_join_date
                FROM inv_warehouse_transaction
                JOIN inv_warehouse_transaction_payment ON warehouse_transaction_payment_warehouse_transaction_id = warehouse_transaction_id
                JOIN sys_member ON member_join_datetime = warehouse_transaction_payment_paid_datetime
                AND warehouse_transaction_status IN ('paid', 'complete', 'delivered', 'approved')
                AND warehouse_transaction_buyer_type = 'member'
                GROUP BY warehouse_transaction_id
                ) t1 GROUP BY member_join_date
                UNION ALL
                SELECT member_join_date, SUM(transaction_total_price) AS transaction_total_price FROM (
                SELECT stockist_transaction_total_price AS transaction_total_price, DATE(member_join_datetime) AS member_join_date
                FROM inv_stockist_transaction
                JOIN inv_stockist_transaction_payment ON stockist_transaction_payment_stockist_transaction_id = stockist_transaction_id
                JOIN sys_member ON member_join_datetime = stockist_transaction_payment_paid_datetime
                AND stockist_transaction_status IN ('paid', 'complete', 'delivered', 'approved')
                AND stockist_transaction_buyer_type = 'member'
                GROUP BY stockist_transaction_id
                ) t2 GROUP BY member_join_date
            ) t3 GROUP BY member_join_date
        ) t ON DATE(member_join_datetime) = member_join_date";

        if ($investor) {
            $whereCondition = "DATE(member_join_datetime) >= '{$where_date}' AND YEAR(member_join_datetime) >= '{$where_year}'";
        } else {
            $whereCondition = "";
        }

        $groupBy = "GROUP BY {$type}(member_join_datetime)";

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        if (count($data['results']) > 0) {
            foreach ($data['results'] as $key => $value) {
                if ($type == "MONTH") {
                    $month = [1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

                    $data['results'][$key]['total'] = (int)$data['results'][$key]['count'] * 400000;
                    $data['results'][$key]['date'] = $month[$value["month"]] . " " . $value["year"];
                } else if ($type == "WEEK") {
                    $week_start = date("Y-m-d", strtotime($value["date"]));
                    $week_end = date("Y-m-d", strtotime($value["date"] . " +6 days"));

                    $data['results'][$key]['total'] = (int)$data['results'][$key]['count'] * 400000;
                    $data['results'][$key]['date'] = $this->functionLib->convertDatetime($week_start) . " - " . $this->functionLib->convertDatetime($week_end);
                } else {
                    $data['results'][$key]['date'] = $this->functionLib->convertDatetime($value['date']);
                }

                $data['results'][$key]['count'] = (int)$data['results'][$key]['count'];
                if ($investor) {
                    $data['results'][$key]['investor_percentage'] = $investor->investor_percentage;
                } else {
                    $data['results'][$key]['investor_percentage'] = 0;
                }
                $data['results'][$key]['investor_sharing'] =   $data['results'][$key]['total'] * $data['results'][$key]['investor_percentage'] / 100;

                $data['results'][$key]['total'] =  $this->functionLib->format_nominal('', $data['results'][$key]['total'], 2);
                $data['results'][$key]['investor_sharing'] =  $this->functionLib->format_nominal('', $data['results'][$key]['investor_sharing'], 2);
            }
        }

        $this->createRespon(200, 'Berhasil mengambil data.', $data);
    }

    public function administrator_group_option()
    {
        $data = array();
        $sql = "SELECT administrator_group_id, administrator_group_title FROM site_administrator_group ORDER BY administrator_group_id ASC";
        $query = $this->db->query($sql)->getResult();
        if (!empty($query)) {
            $i = 0;
            foreach ($query as $row) {
                $data[$i]['title'] = $row->administrator_group_title;
                $data[$i]['value'] = $row->administrator_group_id;
                $i++;
            }
        }
        echo json_encode($data);
    }

    public function detailInvestor()
    {
        $investor_id = $this->request->getGet('id');

        $data = array();
        $data['results'] = $this->db->table("report_investor")->getWhere(["investor_id" => $investor_id])->getRow();

        if ($data['results']) {
            $data['results']->investor_bank_id = $data['results']->investor_bank_id ? $data['results']->investor_bank_id : '';

            $data['results']->investor_join_datetime_formatted =  $this->functionLib->convertDatetime($data['results']->investor_join_datetime);
            $data['results']->investor_update_datetime_formatted =  $this->functionLib->convertDatetime($data['results']->investor_update_datetime);
        } else {
            $this->createRespon(400, 'Investor tidak ditemukan', ['results' => []]);
        }


        $this->createRespon(200, 'Data Investor', $data);
    }

    public function addInvestor()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'investor_title' => [
                'label' => 'investor_title',
                'rules' => 'required|is_unique[report_investor.investor_title]',
                'errors' => [
                    'required' => 'Nama Investor tidak boleh kosong',
                    'is_unique' => 'Nama Investor sudah dipakai',
                ],
            ],
            'investor_email' => [
                'label' => 'investor_email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email tidak boleh kosong',
                    'valid_email' => 'Email tidak valid',
                ],
            ],
            'investor_percentage' => [
                'label' => 'investor_percentage',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Persentase tidak boleh kosong',
                    'numeric' => 'Persentase member hanya boleh berisi angka',
                ],
            ],
            'investor_administrator_group_id' => [
                'label' => 'investor_administrator_group_id',
                'rules' => 'required|is_not_unique[site_administrator_group.administrator_group_id]',
                'errors' => [
                    'required' => 'Group Administrator tidak boleh kosong',
                    'is_not_unique' => 'Group Administrator tidak ditemukan',
                ],
            ],
            'investor_join_date' => [
                'label' => 'investor_join_date',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Group Administrator tidak boleh kosong',
                ],
            ],
            'investor_bank_id' => [
                'label' => 'investor_bank_id',
                'rules' => 'required|is_not_unique[ref_bank.bank_id]',
                'errors' => [
                    'required' => 'Bank tidak boleh kosong',
                    'is_not_unique' => 'Bank tidak ditemukan',
                ],
            ],
            'investor_bank_account_name' => [
                'label' => 'investor_bank_account_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Pemilik Rekening tidak boleh kosong',
                ],
            ],
            'investor_bank_account_no' => [
                'label' => 'investor_bank_account_no',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor Rekening tidak boleh kosong',
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
                $datetime = date('Y-m-d H:i:s');

                $findDataBank = $this->db->table('ref_bank')->getWhere(['bank_id' => $this->request->getVar('investor_bank_id')])->getRow();

                if (!$findDataBank) {
                    throw new \Exception("Bank tidak ditemukan.", 1);
                }

                $data['investor_title'] = $this->request->getVar('investor_title');
                $data['investor_email'] = $this->request->getVar('investor_email');
                $data['investor_percentage'] = $this->request->getVar('investor_percentage');
                $data['investor_administrator_group_id'] = $this->request->getVar('investor_administrator_group_id');
                $data['investor_join_date'] = $this->request->getVar('investor_join_date');
                $data['investor_join_datetime'] = $datetime;
                $data['investor_update_datetime'] = $datetime;
                $data['investor_bank_id'] = $this->request->getVar('investor_bank_id');
                $data['investor_bank_name'] = $findDataBank->bank_name;
                $data['investor_bank_account_name'] = $this->request->getVar('investor_bank_account_name');
                $data['investor_bank_account_no'] = $this->request->getVar('investor_bank_account_no');
                $data['investor_bank_city'] = $this->request->getVar('investor_bank_city') ? $this->request->getVar('investor_bank_city') : null;
                $data['investor_bank_branch'] = $this->request->getVar('investor_bank_branch') ? $this->request->getVar('investor_bank_branch') : null;
                $data['investor_is_active'] = 1;

                $this->db->table('report_investor')->insert($data);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $id = $this->db->insertID();
                $data_log['investor_log_investor_id'] = $id;
                $data_log['investor_log_investor_title'] = $this->request->getVar('investor_title');
                $data_log['investor_log_investor_percentage'] = $this->request->getVar('investor_percentage');
                $data_log['investor_log_investor_administrator_group_id'] = $this->request->getVar('investor_administrator_group_id');
                $data_log['investor_log_datetime'] = $datetime;

                $this->db->table('report_investor_log')->insert($data_log);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Proses gagal.", 1);
                }

                $message = "Berhasil menambah data";
                $this->db->transCommit();
                $this->createRespon(200, $message, ['results' => $data]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function updateInvestor()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'investor_title' => [
                'label' => 'investor_title',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Investor tidak boleh kosong',
                ],
            ],
            'investor_percentage' => [
                'label' => 'investor_percentage',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Persentase tidak boleh kosong',
                    'numeric' => 'Persentase member hanya boleh berisi angka',
                ],
            ],
            'investor_email' => [
                'label' => 'investor_email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email tidak boleh kosong',
                    'valid_email' => 'Email tidak valid',
                ],
            ],
            'investor_administrator_group_id' => [
                'label' => 'investor_administrator_group_id',
                'rules' => 'required|is_not_unique[site_administrator_group.administrator_group_id]',
                'errors' => [
                    'required' => 'Group Administrator tidak boleh kosong',
                    'is_not_unique' => 'Group Administrator tidak ditemukan',
                ],
            ],
            'investor_join_date' => [
                'label' => 'investor_join_date',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Group Administrator tidak boleh kosong',
                ],
            ],
            'investor_bank_id' => [
                'label' => 'investor_bank_id',
                'rules' => 'required|is_not_unique[ref_bank.bank_id]',
                'errors' => [
                    'required' => 'Bank tidak boleh kosong',
                    'is_not_unique' => 'Bank tidak ditemukan',
                ],
            ],
            'investor_bank_account_name' => [
                'label' => 'investor_bank_account_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Pemilik Rekening tidak boleh kosong',
                ],
            ],
            'investor_bank_account_no' => [
                'label' => 'investor_bank_account_no',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor Rekening tidak boleh kosong',
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
                $datetime = date('Y-m-d H:i:s');

                $investor_id = $this->request->getVar('investor_id');

                $findDataBank = $this->db->table('ref_bank')->getWhere(['bank_id' => $this->request->getVar('investor_bank_id')])->getRow();

                if (!$findDataBank) {
                    throw new \Exception("Bank tidak ditemukan", 1);
                }

                $data['investor_title'] = $this->request->getVar('investor_title');
                $data['investor_email'] = $this->request->getVar('investor_email');
                $data['investor_percentage'] = $this->request->getVar('investor_percentage');
                $data['investor_administrator_group_id'] = $this->request->getVar('investor_administrator_group_id');
                $data['investor_join_date'] = $this->request->getVar('investor_join_date');
                $data['investor_update_datetime'] = $datetime;
                $data['investor_bank_id'] = $this->request->getVar('investor_bank_id');
                $data['investor_bank_name'] = $findDataBank->bank_name;
                $data['investor_bank_account_name'] = $this->request->getVar('investor_bank_account_name');
                $data['investor_bank_account_no'] = $this->request->getVar('investor_bank_account_no');
                $data['investor_bank_city'] = $this->request->getVar('investor_bank_city');
                $data['investor_bank_branch'] = $this->request->getVar('investor_bank_branch');
                $data['investor_is_active'] = 1;

                $this->db->table('report_investor')->update($data, ['investor_administrator_group_id' => $this->request->getVar('investor_administrator_group_id')]);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data investor", 1);
                }

                $data_log['investor_log_investor_id'] = $investor_id;
                $data_log['investor_log_investor_title'] = $this->request->getVar('investor_title');
                $data_log['investor_log_investor_percentage'] = $this->request->getVar('investor_percentage');
                $data_log['investor_log_investor_administrator_group_id'] = $this->request->getVar('investor_administrator_group_id');
                $data_log['investor_log_datetime'] = $datetime;

                $this->db->table('report_investor_log')->insert($data_log);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal update data riwayat investor.", 1);
                }

                $message = "Berhasil mengubah data";
                $this->db->transCommit();
                $this->createRespon(200, $message, ['results' => $data]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function activeInvestor()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('report_investor')->update(['investor_is_active' => 1], ['investor_id' => $id]);

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
            $message = 'Berhasil mengaktifkan data investor!';
            if ($success == 0) {
                $message = 'Gagal mengaktifkan data investor';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data investor!');
        }
    }

    public function nonActiveInvestor()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                $this->db->table('report_investor')->update(['investor_is_active' => 0], ['investor_id' => $id]);

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
            $message = 'Berhasil menonaktifkan data investor!';
            if ($success == 0) {
                $message = 'Gagal menonaktifkan data investor';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data investor!');
        }
    }

    public function deleteInvestor()
    {
        $data = (array) $this->request->getVar('data');

        if (count($data) > 0) {
            $success = $failed = 0;
            foreach ($data as $key => $id) {
                // get investor data
                $investor = $this->db->table('report_investor')->getWhere(['investor_id' => $id])->getRow();


                if (!$investor) {
                    $this->createRespon(400, 'Investor tidak dapat ditemukan!');
                }

                $this->db->table('site_administrator')->where(['administrator_administrator_group_id' => $investor->investor_administrator_group_id])->delete();
                $this->db->table('site_administrator_group')->where(['administrator_group_id' => $investor->investor_administrator_group_id])->delete();


                $this->db->table('report_investor')->where(['investor_id' => $id])->delete();

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
            $message = 'Berhasil menghapus data investor!';
            if ($success == 0) {
                $message = 'Gagal menghapus data investor';
            }
            $this->createRespon(200, $message, $data);
        } else {
            $this->createRespon(400, 'Anda belum memilih data investor!');
        }
    }

    public function withdraw()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'amount' => [
                'label' => 'Jumlah Penarikan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
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
                $this->db->transBegin();

                $amount = $this->request->getVar('amount');

                $findAdministrator = $this->db->table('site_administrator')->getWhere(['administrator_id' => session("admin")["admin_id"]])->getRow();

                if (!$findAdministrator) {
                    throw new \Exception("Administrator tidak ditemukan", 1);
                }

                // if amount less than 60000 then throw error
                if ($amount < 60000) {
                    throw new \Exception("Jumlah penarikan minimal Rp. 60.000", 1);
                }

                $findInvestor = $this->db->table('report_investor')->getWhere(['investor_administrator_group_id' => $findAdministrator->administrator_administrator_group_id])->getRow();

                if (!$findInvestor) {
                    throw new \Exception("Investor tidak ditemukan", 1);
                }

                $findPercentage = $this->investorModel->getTotal(
                    $findInvestor->investor_percentage
                );

                if (!$findPercentage) {
                    throw new \Exception("Persentase tidak ditemukan", 1);
                }


                $findPercentage->paid = $findInvestor->investor_paid;
                $findPercentage->balance = $findPercentage->total_price_percent - $findInvestor->investor_paid - $findInvestor->investor_pending_payment;
                $findInvestor->pending = $findInvestor->investor_pending_payment;

                if ($amount > $findPercentage->balance) {
                    throw new \Exception("Jumlah penarikan melebihi saldo", 1);
                }

                if (!$findInvestor->investor_bank_id) {
                    throw new \Exception("Bank investor belum diisi, silahkan hubungi admin", 1);
                }

                $data_withdrawal = array();
                $data_withdrawal['investor_withdrawal_investor_id'] = $findInvestor->investor_id;
                $data_withdrawal['investor_withdrawal_value'] = $amount;
                $data_withdrawal['investor_withdrawal_investor_acc_before'] = $findPercentage->total_price_percent;
                $data_withdrawal['investor_withdrawal_bank_id'] = $findInvestor->investor_bank_id;
                $data_withdrawal['investor_withdrawal_bank_name'] = $findInvestor->investor_bank_name;
                $data_withdrawal['investor_withdrawal_bank_city'] = $findInvestor->investor_bank_city;
                $data_withdrawal['investor_withdrawal_bank_branch'] = $findInvestor->investor_bank_branch;
                $data_withdrawal['investor_withdrawal_account_name'] = $findInvestor->investor_bank_account_name;
                $data_withdrawal['investor_withdrawal_account_no'] = $findInvestor->investor_bank_account_no;
                $data_withdrawal['investor_withdrawal_datetime'] = date('Y-m-d H:i:s');

                $this->db->table('report_investor_withdrawal')->insert($data_withdrawal);

                $data['withdrawal_id'] = $this->db->insertID();

                if (!$data['withdrawal_id']) {
                    throw new \Exception("Gagal menambahkan data penarikan", 1);
                }

                $this->db->table('report_investor')
                    ->set("investor_pending_payment", "investor_pending_payment+" . $amount, FALSE)
                    ->where(['investor_id' => $findInvestor->investor_id])
                    ->update();

                if ($this->db->affectedRows() == 0) {
                    throw new \Exception("Gagal mengubah data investor", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil mengubah data', ['results' => $data]);
            } catch (\Throwable $th) {
                $this->db->transRollback();
                $this->createRespon(400, $th->getMessage(), ['results' => []]);
            }
        }
    }

    public function approveWithdrawalInvestor()
    {
        try {
            $this->db->transBegin();

            $data =  (array) $this->request->getVar('data') ?: [];
            $success = $failed = 0;

            $this->db->table("payment_disbursement")->insert([
                "disbursement_type" => "batch",
                "disbursement_type_disbursement" => "investor",
                "disbursement_datetime" => date("Y-m-d H:i:s")
            ]);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambah data disbursement.", 1);
            }

            $disbursement_id = $this->db->insertID();

            $disbursement_uploaded_count = $disbursement_uploaded_amount = 0;

            $disburments_xendit = [];

            foreach ($data as $withdrawal_id) {
                $findTransferInvestorWithdrawal = $this->db
                    ->table('report_investor_withdrawal')
                    ->getWhere(['investor_withdrawal_id' => $withdrawal_id])
                    ->getRow();

                if (!$findTransferInvestorWithdrawal) {
                    throw new \Exception("Penarikan tidak ditemukan", 1);
                }

                // set investor_pending_payment

                $investor_withdrawal = [
                    'investor_withdrawal_status' => 'verification',
                ];

                $this->db
                    ->table('report_investor_withdrawal')
                    ->where(['investor_withdrawal_id' => $withdrawal_id])
                    ->update($investor_withdrawal);

                if ($this->db->affectedRows() == 0) {
                    throw new \Exception("Gagal mengubah data penarikan", 1);
                }

                $disbursement_detail = [
                    'disbursement_detail_external_id' => '',
                    'disbursement_detail_disbursement_id' => $disbursement_id,
                    'disbursement_detail_internal_id' => $withdrawal_id,
                    'disbursement_detail_amount' => $findTransferInvestorWithdrawal->investor_withdrawal_value,
                    'disbursement_detail_bank_code' => $findTransferInvestorWithdrawal->investor_withdrawal_bank_name,
                    'disbursement_detail_bank_account_name' =>  $findTransferInvestorWithdrawal->investor_withdrawal_account_name,
                    'disbursement_detail_bank_account_number' => $findTransferInvestorWithdrawal->investor_withdrawal_account_no,
                    'disbursement_detail_email_to' => 'fadhillahramadhan01@gmail.com',
                    'disbursement_detail_description' => 'Penarikan Investor Kimstella',
                    'disbursement_detail_created' => date("Y-m-d H:i:s"),
                    'disbursement_detail_updated' => date("Y-m-d H:i:s"),
                    "disbursement_detail_status" => ""
                ];

                $this->db->table("payment_disbursement_detail")->insert($disbursement_detail);

                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah detail disbursement", 1);
                }

                $disbursement_detail_id = $this->db->insertID();

                $findBankCode = $this->db
                    ->table('ref_bank')
                    ->getWhere(['bank_id' => $findTransferInvestorWithdrawal->investor_withdrawal_bank_id])
                    ->getRow('bank_code');

                if (!$findBankCode) {
                    throw new \Exception("Bank tidak ditemukan", 1);
                }

                $findEmailInvestor = $this->db
                    ->table('report_investor')
                    ->getWhere(['investor_id' => $findTransferInvestorWithdrawal->investor_withdrawal_investor_id])
                    ->getRow('investor_email');

                $disburments_xendit[] = [
                    'amount' => (int) $findTransferInvestorWithdrawal->investor_withdrawal_value,
                    'bank_code' => $findBankCode,
                    'bank_account_name' => $findTransferInvestorWithdrawal->investor_withdrawal_account_name,
                    'bank_account_number' => $findTransferInvestorWithdrawal->investor_withdrawal_account_no,
                    'description' => 'Penarikan Investor Kimstella',
                    'external_id' => (string) $disbursement_detail_id,
                    'email_to' => $findEmailInvestor ? [$findEmailInvestor] : [],
                ];

                $disbursement_uploaded_amount +=  (int) $findTransferInvestorWithdrawal->investor_withdrawal_value;
                $disbursement_uploaded_count++;
                $success++;
            }

            $dataArchive = [
                'success' => $success,
                'failed' => $failed,
            ];

            $message = 'Berhasil transfer bonus!';

            if ($success == 0) {
                $message = 'Gagal transfer bonus';
            }

            $disbursements_params = [
                'reference' => (string) $disbursement_id,
                'disbursements' => $disburments_xendit
            ];

            $disbursement = $this->payment_service->createDisbursementBatch($disbursements_params);

            $this->db->table("payment_disbursement")->where('disbursement_id', $disbursement_id)->update([
                'disbursement_total_uploaded_count' => $disbursement_uploaded_count,
                'disbursement_total_uploaded_amount' => $disbursement_uploaded_amount,
                'disbursement_external_id' => $disbursement['id'],
                'disbursement_created' => $disbursement['created'],
                'disbursement_status' => $disbursement['status'],
                'disbursement_request_json' => json_encode($disbursements_params),
            ]);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah disbursement", 1);
            }

            $this->db->transCommit();
            $this->createRespon(200, $message, $dataArchive);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->createRespon(400, $th->getMessage(), ['trace' => $th->getTrace()]);
        }
    }

    public function getTotalWithdrawalPending()
    {
        try {
            $data['total']  = $this->investorModel->getTotalPending();

            $this->createRespon(200, 'Sukses mengambil total withdrawal', ['results' => $data]);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage(), ['results' => []]);
        }
    }
}
