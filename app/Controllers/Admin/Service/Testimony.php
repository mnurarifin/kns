<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\TestimonyModel;
use Config\Services;

class Testimony extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->TestimonyModel = new TestimonyModel();
    }

    public function getDataTesty()
    {
        $urlMember = getenv('UPLOAD_URL') . URL_IMG_MEMBER;

        $tableName = 'site_testimony';
        $columns = array(
            'testimony_id',
            'testimony_content',
            'testimony_is_publish',
            'testimony_date',
            'member_name',
            'member_image',
            'network_code',
        );
        $joinTable = 'JOIN sys_member on member_id = testimony_member_id JOIN sys_network on network_member_id = testimony_member_id';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['testimony_content_short'] = wordwrap(addslashes($value['testimony_content']), 90, '<br/>', true);
            $data['results'][$key]['testimony_date'] = $this->functionLib->convertDatetime($value['testimony_date'], 'id', 'text');
            $data['results'][$key]['testimony_member_image'] = $urlMember . (($value['member_image'] != '') ? $value['member_image'] : '_default.jpg');
        }
        $this->createRespon(200, 'Data Testimonial', $data);
    }

    public function updateTesty()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'testyId' => [
                'label' => 'Testimony ID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ],
            ],
            'isiTesti' => [
                'label' => 'Isi Testimoni',
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
            $testyId = $this->request->getPost('testyId');

            $data = array();
            $data['testimony_content'] = $this->request->getPost('isiTesti');
            $res = $this->functionLib->updateData('site_testimony', 'testimony_id', $testyId, $data);

            if ($res != false) {
                $result = array(
                    "message" => "Testimonial berhasil diubah",
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Testimonial gagal diubah",
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function activeTesty()
    {
        $dataTesty = $this->request->getPost('data');

        if (is_array($dataTesty)) {
            $success = $failed = 0;
            foreach ($dataTesty as $key => $testyId) {
                if ($this->TestimonyModel->activeTesty($testyId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed,
            ];

            $message = 'Berhasil Mempublikasikan Testimoni';
            if ($success == 0) {
                $message = 'Gagal Mempublikasikan Testimoni';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan ditampilkan!.');
        }
    }

    public function nonActiveTesty()
    {
        $dataTesty = $this->request->getPost('data');
        if (is_array($dataTesty)) {
            $success = $failed = 0;
            foreach ($dataTesty as $key => $testyId) {
                if ($this->TestimonyModel->nonActiveTesty($testyId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed,
            ];

            $message = 'Berhasil Menyembunyikan Testimoni';
            if ($success == 0) {
                $message = 'Gagal Menyembunyikan Testimoni';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang tidak akan ditampilkan!.');
        }
    }

    public function removeTesty()
    {
        $dataTesty = $this->request->getPost('data');

        if (is_array($dataTesty)) {
            $success = $failed = 0;
            foreach ($dataTesty as $testyId) {
                if ($this->TestimonyModel->removeTesty($testyId)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed,
            ];

            $message = 'Berhasil Hapus Data Testimoni';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Testimoni';
            }

            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }
}
