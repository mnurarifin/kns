<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use Config\Services;

class Ref_courier extends BaseServiceController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->pathUpload = UPLOAD_PATH . URL_IMG_COURIER;
    }

    public function getDataCourier()
    {
        $tableName = 'ref_courier';
        $columns = array(
            'courier_id',
            'courier_code',
            'courier_name',
            'courier_image',
            'IF(courier_is_active = 1 , "Aktif","NonAktif") AS courier_is_active_formatted',
            'courier_is_active',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Courier', $data);
    }

    public function activeCourier()
    {
        $arr_id = $this->request->getPost('data');

        if (is_array($arr_id)) {
            $success = $failed = 0;
            foreach ($arr_id as $id) {
                $this->db->table("ref_courier")->where("courier_id", $id)->update(["courier_is_active" => 1]);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Aktifkan Data Kurir!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Kurir!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }

    public function nonactiveCourier()
    {
        $arr_id = $this->request->getPost('data');

        if (is_array($arr_id)) {
            $success = $failed = 0;
            foreach ($arr_id as $id) {
                $this->db->table("ref_courier")->where("courier_id", $id)->update(["courier_is_active" => 0]);
                if ($this->db->affectedRows() >= 0) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Nonaktifkan Data Kurir!';
            if ($success == 0) {
                $message = 'Gagal Nonaktifkan Data Kurir!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }
}
