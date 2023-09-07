<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\RefBankModel;
use Config\Services;

class Ref_bank extends BaseServiceController
{

    private $refBankModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->refBankModel = new RefBankModel();

        $this->pathUpload = UPLOAD_PATH . URL_IMG_BANK;
    }

    public function getDataBank()
    {
        $tableName = 'ref_bank';
        $columns = array(
            'bank_id',
            'bank_name',
            'bank_logo',
            'IF(bank_is_active = 1 , "Aktif","NonAktif") AS bank_is_active_formatted',
            'bank_is_active',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Bank', $data);
    }

    function removeBank()
    {
        $dataBank = $this->request->getPost('data');

        if (is_array($dataBank)) {
            $success = $failed = 0;
            foreach ($dataBank as $bank_id) {
                $img = $this->functionLib->getOne('ref_bank', 'bank_logo', ['bank_id' => $bank_id]);
                if ($this->refBankModel->removeBank($bank_id)) {
                    @unlink($this->pathUpload . $img);
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];

            $message = 'Berhasil Hapus Data Bank!';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Bank!';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }

    function activeBank()
    {
        $field = $this->request->getPost('field');
        $dataBank = $this->request->getPost('data');

        if (is_array($dataBank)) {
            $success = $failed = 0;
            foreach ($dataBank as $bank_id) {
                if ($this->refBankModel->activeBank($bank_id, $field)) {
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

    function nonActiveBank()
    {
        $field = $this->request->getPost('field');
        $dataBank = $this->request->getPost('data');

        if (is_array($dataBank)) {
            $success = $failed = 0;
            foreach ($dataBank as $bank_id) {
                if ($this->refBankModel->nonActiveBank($bank_id, $field)) {
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

    function addBank()
    {
        // sleep(3);
        $validation = Services::validation();
        $validation->setRules([
            'bankName' => [
                'label' => 'bankName',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama bank tidak boleh kosong',
                ],
            ],

        ]);
        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $bankName = $this->request->getPost('bankName');
            $bankImage = $this->request->getFile('bankImage');

            $data = array();
            $data['bank_name'] = $bankName;
            $data['bank_is_active'] = 1;

            $path = $this->pathUpload;

            if ($bankImage->getName() != '') {
                $fileName = $bankImage->getName();
                $bankImage->move($path, $fileName);
                $data['bank_logo'] = $fileName;
            } else {
                $data['bank_logo'] = '_default.jpg';
            }

            $res = $this->functionLib->insertData('ref_bank', $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Bank berhasil ditambahkan"
                );
            } else {
                $result = array(
                    "message" => "Data Bank gagal ditambahkan"
                );
            }
            $this->createRespon(200, 'OK', $result);
        }
    }

    function updateBank()
    {
        $validation = Services::validation();
        $validation->setRules([
            'bankName' => [
                'label' => 'bankName',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama bank tidak boleh kosong',
                ],
            ],
        ]);
        if ($validation->run($this->request->getPost()) == FALSE) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $bankId = $this->request->getPost('bankId');
            $bankName = $this->request->getPost('bankName');
            $bankImage = $this->request->getFile('bankImage');
            $bankOldImage = $this->request->getPost('oldLogo');

            $path = $this->pathUpload;

            $data = array();
            $data['bank_name'] = $bankName;

            if ($this->request->getFile('bankImage')) {
                if ($bankImage->getName() != '') {
                    $fileName = $bankImage->getName();
                    $bankImage->move($path, $fileName);
                    $data['bank_logo'] = $fileName;
                    if ($bankOldImage) {
                        @unlink($path . $bankOldImage);
                    }
                }
                // $fileName = $this->request->getFile('bankImage')->getName();
                // $this->request->getFile('bankImage')->move(WRITEPATH . 'uploads' . $fileName);
                // $data['bank_logo'] = $fileName;
            }



            $res = $this->functionLib->updateData('ref_bank', 'bank_id', $bankId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Bank berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Bank gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }
}
