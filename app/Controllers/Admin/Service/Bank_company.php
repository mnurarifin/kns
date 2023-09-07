<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\BankCompanyModel;
use Config\Services;

class Bank_company extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bankCompanyModel = new BankCompanyModel();
    }

    public function getDataBankCompany()
    {
        $tableName = 'sys_bank_company';
        $columns = array(
            'bank_company_id',
            'bank_company_bank_id',
            'bank_name',
            'bank_company_bank_acc_name',
            'bank_company_bank_acc_number',
            'IF(bank_company_bank_is_active = 1 , "Aktif","NonAktif") AS bank_company_bank_is_active_formatted',
            'bank_company_bank_is_active',
        );
        $joinTable = 'JOIN ref_bank on bank_id = bank_company_bank_id';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Courier', $data);
    }

    public function actAddBankCompany()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'bank_company_bank_id' => [
                'label' => 'Jenis Bank',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'bank_company_bank_acc_name' => [
                'label' => 'Nama Akun Bank',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'bank_company_bank_acc_number' => [
                'label' => 'Nomor Rekening',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
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
                $arr_insert_bank = [
                    'bank_company_bank_id' => $this->request->getPost('bank_company_bank_id'),
                    'bank_company_bank_acc_name' => $this->request->getPost('bank_company_bank_acc_name'),
                    'bank_company_bank_acc_number' => $this->request->getPost('bank_company_bank_acc_number'),
                    'bank_company_bank_is_active' => 1
                ];

                $this->db->table('sys_bank_company')->insert($arr_insert_bank);
                if ($this->db->affectedRows() <= 0) {
                    throw new \Exception("Gagal menambah data bank company", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil menambah data bank company', $arr_insert_bank);
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, 'Bad Request', $th->getMessage());
            }
        }
    }

    public function detail()
    {
        $bank_company_id = $this->request->getGet('id');

        $data = array();
        $data['results'] = $this->db->table('sys_bank_company')
            ->select('bank_company_id, bank_company_bank_id, bank_company_bank_acc_name, bank_company_bank_acc_number')
            ->getWhere("bank_company_id = {$bank_company_id}")
            ->getRow();

        if ($data['results']) {
            $this->createRespon(200, 'Data Bank Company', $data);
        } else {
            $this->createRespon(400, 'Data tidak ditemukan', ['results' => []]);
        }
    }

    public function actUpdateBankCompany()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'bank_company_bank_id' => [
                'label' => 'Jenis Bank',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong'],
            ],
            'bank_company_bank_acc_name' => [
                'label' => 'Nama Akun Bank',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'bank_company_bank_acc_number' => [
                'label' => 'Nomor Rekening',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
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
                    'bank_company_bank_id' => $this->request->getPost('bank_company_bank_id'),
                    'bank_company_bank_acc_name' => $this->request->getPost('bank_company_bank_acc_name'),
                    'bank_company_bank_acc_number' => $this->request->getPost('bank_company_bank_acc_number'),
                ];

                $this->db->table('sys_bank_company')
                    ->where('bank_company_id', $this->request->getPost('bank_company_id'))
                    ->update($data);

                if ($this->db->affectedRows() < 0) {
                    throw new \Exception("Gagal update data bank company", 1);
                }

                $this->db->transCommit();
                $this->createRespon(200, 'Berhasil update data bank company', $data);
            } catch (\Throwable $th) {
                $this->db->transRollback();

                $this->createRespon(400, 'Bad Request', $th->getMessage());
            }
        }
    }

    public function nonactiveBank()
    {
        $dataBank = $this->request->getPost('data');

        if (is_array($dataBank)) {
            $success = $failed = 0;
            foreach ($dataBank as $bank_company_id) {
                $this->db->table('sys_bank_company')
                    ->where('bank_company_id', $bank_company_id)
                    ->update(['bank_company_bank_is_active' => 0]);

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil NonAktifkan Data Bank!';
            if ($success == 0) {
                $message = 'Gagal NonAktifkan Data Bank!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan NonAktifkan!.');
        }
    }

    public function activeBank()
    {
        $dataBank = $this->request->getPost('data');

        if (is_array($dataBank)) {
            $success = $failed = 0;
            foreach ($dataBank as $bank_company_id) {
                $this->db->table('sys_bank_company')
                    ->where('bank_company_id', $bank_company_id)
                    ->update(['bank_company_bank_is_active' => 1]);

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Aktifkan Data Bank!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Bank!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }

    public function getDataBank()
    {
        $data_bank = $this->db->table('ref_bank')
            ->select('bank_id, bank_name')
            ->get()
            ->getResult();

        if ($data_bank) {
            $this->createRespon(200, 'Data List Bank', $data_bank);
        } else {
            $this->createRespon(400, 'Kesalahan sistem');
        }
    }

    public function removeBankCompany()
    {
        $dataBank = $this->request->getPost('data');

        if (is_array($dataBank)) {
            $success = $failed = 0;
            foreach ($dataBank as $bank_company_id) {
                $this->db->table('sys_bank_company')
                    ->where('bank_company_id', $bank_company_id)
                    ->delete();

                if ($this->db->affectedRows() > 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];

            $message = 'Berhasil Hapus Data Bank Company';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Bank Company';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }
}
