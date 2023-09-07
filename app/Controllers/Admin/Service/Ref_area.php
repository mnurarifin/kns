<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\RefAreaModel;
use Config\Services;

class Ref_area extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->refAreaModel = new RefAreaModel();
    }

    public function getDataCountry()
    {
        $tableName = 'ref_country';
        $columns = array(
            'country_id',
            'country_iso_code',
            'country_phone_code',
            'country_name',
            'country_flag',
            'country_is_active',
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Negara', $data);
    }

    function removeCountry()
    {
        $field = $this->request->getPost('field');
        $dataCountry = $this->request->getPost('data');

        if (is_array($dataCountry)) {
            $success = $failed = 0;
            foreach ($dataCountry as $country_id) {
                if ($this->refAreaModel->removeCountry($country_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Negara!';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Negara!';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus!.');
        }
    }

    function activeCountry()
    {
        $field = $this->request->getPost('field');
        $dataCountry = $this->request->getPost('data');

        if (is_array($dataCountry)) {
            $success = $failed = 0;
            foreach ($dataCountry as $country_id) {
                if ($this->refAreaModel->activeCountry($country_id, $field)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Aktifkan Data Negara!';
            if ($success == 0) {
                $message = 'Gagal Aktifkan Data Negara!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan diaktifkan!.');
        }
    }

    function nonActiveCountry()
    {
        $field = $this->request->getPost('field');
        $dataCountry = $this->request->getPost('data');

        if (is_array($dataCountry)) {
            $success = $failed = 0;
            foreach ($dataCountry as $country_id) {
                if ($this->refAreaModel->nonActiveCountry($country_id, $field)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActived = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil NonAktifkan Data Negara!';
            if ($success == 0) {
                $message = 'Gagal NonAktifkan Data Negara!';
            }
            $this->createRespon(200, $message, $dataActived);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan NonAktifkan!.');
        }
    }

    public function getDataProvince()
    {
        $tableName = 'ref_province';
        $columns = array(
            'province_id',
            'province_name',
            'province_latitude',
            'province_longitude'
        );
        $joinTable = '';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Area Provinsi', $data);
    }

    public function getDataCity()
    {
        $tableName = 'ref_city';
        $columns = array(
            'city_id',
            'city_province_id',
            'province_name',
            'city_name',
            'city_latitude',
            'city_longitude'
        );
        $joinTable = 'JOIN ref_province on city_province_id = province_id';
        $whereCondition = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Area Kota / Kabupaten', $data);
    }

    public function getDataSubdistrict($cityId = '')
    {
        $whereCondition = '';
        if($cityId != '') {
            $whereCondition .= " AND subdistrict_city_id = $cityId";
        }
        $tableName = 'ref_subdistrict';
        $columns = array(
            'subdistrict_id',
            'subdistrict_city_id',
            'subdistrict_name',
            'subdistrict_latitude',
            'subdistrict_longitude'
        );
        $joinTable = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Area Kecamatan', $data);
    }

    public function getDataVillage($subdistrictId = '')
    {
        $whereCondition = '';
        if($subdistrictId != '') {
            $whereCondition .= " AND village_subdistrict_id = $subdistrictId";
        }
        $tableName = 'ref_village';
        $columns = array(
            'village_id',
            'village_subdistrict_id',
            'village_name',
            'village_latitude',
            'village_longitude'
        );
        $joinTable = '';

        $data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
        $this->createRespon(200, 'Data Area Desa', $data);
    }

    public function actAddProvince()
    {
        sleep(2);
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required'
        ]);
        $autoProvinceId = $this->functionLib->getMax('ref_province', 'province_id');
        $newProvinceId =  $autoProvinceId + 1;

        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $areaLatitude = $this->request->getPost('areaLatitude');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaName = $this->request->getPost('areaName');

            $data = array();
            $data['province_id'] = $newProvinceId;
            $data['province_latitude'] = $areaLatitude;
            $data['province_longitude'] = $areaLongitude;
            $data['province_name'] = $areaName;
            $res = $this->functionLib->insertData('ref_province', $data);


            if ($res >= 0) {
                $result = array(
                    "message" => "Data Province berhasil ditambah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Province gagal ditambah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actAddCity()
    {
        sleep(2);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required'
        ]);
        $provinceId = $this->request->getPost('provinceId');
        $autoCityId = $this->functionLib->getMax('ref_city', 'city_id', 'city_province_id = "' . $provinceId . '"');
        $newCityId = $autoCityId + 1;
        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $areaLatitude = $this->request->getPost('areaLatitude');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaName = $this->request->getPost('areaName');

            $data = array();

            $data['city_id'] = $newCityId;
            $data['city_province_id'] = $provinceId;
            $data['city_latitude'] = $areaLatitude;
            $data['city_longitude'] = $areaLongitude;
            $data['city_name'] = $areaName;
            $res = $this->functionLib->insertData('ref_city', $data);


            if ($res >= 0) {
                $result = array(
                    "message" => "Data Kota / Kabupaten berhasil ditambah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Kota / Kabupaten gagal ditambah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actAddSubdistrict()
    {
        sleep(2);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required'
        ]);
        $cityId = $this->request->getPost('cityId');
        $autoSubdistrictId = $this->functionLib->getMax('ref_subdistrict', 'subdistrict_id', 'subdistrict_city_id = "' . $cityId . '"');
        $newSubdistrictId = $autoSubdistrictId + 1;
        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $areaLatitude = $this->request->getPost('areaLatitude');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaName = $this->request->getPost('areaName');

            $data = array();

            $data['subdistrict_id'] = $newSubdistrictId;
            $data['subdistrict_city_id'] = $cityId;
            $data['subdistrict_latitude'] = $areaLatitude;
            $data['subdistrict_longitude'] = $areaLongitude;
            $data['subdistrict_name'] = $areaName;
            $res = $this->functionLib->insertData('ref_subdistrict', $data);


            if ($res >= 0) {
                $result = array(
                    "message" => "Data Kecamatan berhasil ditambah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Kecamatan gagal ditambah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actAddVillage()
    {
        sleep(2);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required'
        ]);
        $subdistrictId = $this->request->getPost('subdistrictId');
        $autoVillageId = $this->functionLib->getMax('ref_village', 'village_id', 'village_subdistrict_id = "' . $subdistrictId . '"');
        $newVillageId = $autoVillageId + 1;
        if ($validation->run($this->request->getPost()) == FALSE) {

            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $areaLatitude = $this->request->getPost('areaLatitude');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaName = $this->request->getPost('areaName');

            $data = array();

            $data['village_id'] = $newVillageId;
            $data['village_subdistrict_id'] = $subdistrictId;
            $data['village_latitude'] = $areaLatitude;
            $data['village_longitude'] = $areaLongitude;
            $data['village_name'] = $areaName;
            $res = $this->functionLib->insertData('ref_village', $data);


            if ($res >= 0) {
                $result = array(
                    "message" => "Data Desa berhasil ditambah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Desa gagal ditambah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actUpdateProvince()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required',
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $provinceId = $this->request->getPost('provinceId');
            $areaName = $this->request->getPost('areaName');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaLatitude = $this->request->getPost('areaLatitude');

            $data = array();
            $data['province_id'] = $provinceId;
            $data['province_name'] = $areaName;
            $data['province_longitude'] = $areaLongitude;
            $data['province_latitude'] = $areaLatitude;
            $res = $this->functionLib->updateData('ref_province', 'province_id', $provinceId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Provinsi berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Provinsi gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actUpdateCity()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required',
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $cityId = $this->request->getPost('cityId');
            $areaName = $this->request->getPost('areaName');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaLatitude = $this->request->getPost('areaLatitude');

            $data = array();
            $data['city_id'] = $cityId;
            $data['city_name'] = $areaName;
            $data['city_longitude'] = $areaLongitude;
            $data['city_latitude'] = $areaLatitude;
            $res = $this->functionLib->updateData('ref_city', 'city_id', $cityId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Kota / Kabupaten berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Kota / Kabupaten gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actUpdateSubdistrict()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required',
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $subdistrictId = $this->request->getPost('subdistrictId');
            $areaName = $this->request->getPost('areaName');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaLatitude = $this->request->getPost('areaLatitude');

            $data = array();
            $data['subdistrict_id'] = $subdistrictId;
            $data['subdistrict_name'] = $areaName;
            $data['subdistrict_longitude'] = $areaLongitude;
            $data['subdistrict_latitude'] = $areaLatitude;
            $res = $this->functionLib->updateData('ref_subdistrict', 'subdistrict_id', $subdistrictId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Kecamatan berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Kecamatan gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function actUpdateVillage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'areaName' => 'required',
        ]);

        if ($validation->run($this->request->getPost()) == false) {
            $result = array(
                "validationMessage" => $validation->getErrors(),
            );
            $this->createRespon(400, 'validationError', $result);
        } else {
            $villageId = $this->request->getPost('villageId');
            $areaName = $this->request->getPost('areaName');
            $areaLongitude = $this->request->getPost('areaLongitude');
            $areaLatitude = $this->request->getPost('areaLatitude');

            $data = array();
            $data['village_id'] = $villageId;
            $data['village_name'] = $areaName;
            $data['village_longitude'] = $areaLongitude;
            $data['village_latitude'] = $areaLatitude;
            $res = $this->functionLib->updateData('ref_village', 'village_id', $villageId, $data);

            if ($res != FALSE) {
                $result = array(
                    "message" => "Data Desa berhasil diubah"
                );
                $this->createRespon(200, 'OK', $result);
            } else {
                $result = array(
                    "message" => "Data Desa gagal diubah"
                );
                $this->createRespon(400, 'Bad Request', $result);
            }
        }
    }

    public function removeProvince()
    {
        $dataProvince = $this->request->getPost('data');

        if (is_array($dataProvince)) {
            $success = $failed = 0;
            foreach ($dataProvince as $province_id) {
                if ($this->refAreaModel->removeProvince($province_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Provinsi';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Provinsi';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function removeCity()
    {
        $dataCity = $this->request->getPost('data');

        if (is_array($dataCity)) {
            $success = $failed = 0;
            foreach ($dataCity as $city_id) {
                if ($this->refAreaModel->removeCity($city_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Kota / Kabupaten';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Kota / Kabupaten';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function removeSubdistrict()
    {
        $dataSubdistrict = $this->request->getPost('data');

        if (is_array($dataSubdistrict)) {
            $success = $failed = 0;
            foreach ($dataSubdistrict as $subdistrict_id) {
                if ($this->refAreaModel->removeSubdistrict($subdistrict_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Kecamatan';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Kecamatan';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }

    public function removeVillage()
    {
        $dataVillage = $this->request->getPost('data');

        if (is_array($dataVillage)) {
            $success = $failed = 0;
            foreach ($dataVillage as $village_id) {
                if ($this->refAreaModel->removeVillage($village_id)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataDeleted = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil Hapus Data Desa';
            if ($success == 0) {
                $message = 'Gagal Hapus Data Desa';
            }
            $this->createRespon(200, $message, $dataDeleted);
        } else {
            $this->createRespon(400, 'Anda belum memilih data yang akan dihapus');
        }
    }
}
