<?php

namespace App\Libraries;

use Config\Database;

class FunctionLib
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function setNumberFormat($number, $isInt = true)
    {
        if (is_numeric($number) && floor($number) != $number && $isInt == false) {
            return number_format($number, 2, ',', '.');
        } else {
            return number_format($number, 0, ',', '.');
        }
    }

    public function generateNumber($length)
    {
        $pinStr = "1234567809";
        for ($i = 0; $i < strlen($pinStr); $i++) {
            $pinChars[$i] = $pinStr[$i];
        }
        // randomize the chars
        srand((float) microtime() * 1000000);
        shuffle($pinChars);
        $pin = "";
        for ($i = 0; $i < 20; $i++) {
            $char_num = rand(1, count($pinChars));
            $pin .= $pinChars[$char_num - 1];
        }
        $pin = substr($pin, 0, $length);

        return $pin;
    }

    public function generateAlphaNumber($length)
    {
        $charset = 'ABCDEFGHKLMNPRSTUVWYZ23456789';
        $code = '';

        for ($i = 1, $cslen = strlen($charset); $i <= $length; ++$i) {
            $code .= $charset[rand(0, $cslen - 1)];
        }
        return $code;
    }

    public function multidimensionalArraySort(&$array, $key, $sort = 'asc')
    {
        $sorter = array();
        $ret = array();
        reset($array);

        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }

        if ($sort == 'desc') {
            arsort($sorter);
        } else {
            asort($sorter);
        }

        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }

        return $ret;
    }

    public function getOne($tableName = '', $fieldname = null, $where = null, $fieldsort = null, $sort = 'asc')
    {
        $builder = $this->db->table($tableName);
        $builder->select($fieldname);
        if ($where != null) {
            $builder->where($where);
        }

        if ($fieldsort == null) {
            $fieldsort = $fieldname;
        }
        $builder->orderBy($fieldsort, $sort);
        $builder->limit(1, 0);
        $data = $builder->get()->getRow();
        if (!empty($data)) {
            $result = $data->$fieldname;
        } else {
            $result = '';
        }
        return $result;
    }

    public function getMax($tableName = '', $fieldname = null, $where = null)
    {
        $builder = $this->db->table($tableName);
        $builder->selectMax($fieldname);
        if ($where != null) {
            $builder->where($where);
        }
        $row = $builder->get()->getRow();
        return $row->$fieldname;
    }

    public function getMin($tableName = '', $fieldname = null, $where = null)
    {
        $builder = $this->db->table($tableName);
        $builder->selectMin($fieldname);
        if ($where != null) {
            $builder->where($where);
        }
        $row = $builder->get()->getRow();
        return $row->$fieldname;
    }

    public function getDetailData($tableName, $fieldname, $valueID)
    {
        return $this->db->table($tableName)->where($fieldname, $valueID)->get()->getRow();
    }

    public function lastID()
    {
        $query = $this->db->query('SELECT LAST_INSERT_ID() AS last_insert_id');
        $row = $query->getRow();
        return $row->last_insert_id;
    }

    public function insertData($tableName, $data)
    {
        $builder = $this->db->table($tableName);
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function insertBatchData($tableName, $data)
    {
        $builder = $this->db->table($tableName);
        $builder->insertBatch($data);
        return $this->db->insertID();
    }

    public function updateData($tableName, $fieldname, $valueID, $data)
    {
        $builder = $this->db->table($tableName);
        $builder->where($fieldname, $valueID);

        return $builder->update($data);
    }

    public function deleteData($tableName, $fieldname, $valueID)
    {
        $builder = $this->db->table($tableName);
        $builder->where($fieldname, $valueID);
        $builder->delete();
        return $this->db->affectedRows();
    }

    public function getCityName($id)
    {
        return $this->getOne('ref_city', 'city_name', array('city_id' => $id));
    }

    public function getProvinceName($id)
    {
        return $this->getOne('ref_province', 'province_name', array('province_id' => $id));
    }

    public function getProvinceNameByCityID($id)
    {
        $provinceID = $this->getOne('ref_city', 'city_province_id', array('city_id' => $id));

        return $this->getOne('ref_province', 'province_name', array('province_id' => $provinceID));
    }

    public function getRegionName($id)
    {
        return $this->getOne('ref_region', 'region_name', array('region_id' => $id));
    }

    public function getRegionNameByCityID($id)
    {
        $provinceID = $this->getOne('ref_city', 'city_province_id', array('city_id' => $id));
        $regionID = $this->getOne('ref_province', 'province_region_id', array('province_id' => $provinceID));

        return $this->getOne('ref_region', 'region_name', array('region_id' => $regionID));
    }

    public function getCountryName($id)
    {
        return $this->getOne('ref_country', 'country_name', array('country_id' => $id));
    }

    public function getBankName($id)
    {
        return $this->getOne('ref_bank', 'bank_name', array('bank_id' => $id));
    }

    public function getSuperuserMenu($menuParID = null)
    {
        $whereOption = "";
        if ($menuParID != null) {
            $whereOption = " AND administrator_menu_par_id = '" . $menuParID . "'";
        }
        $sql = "
            SELECT *
            FROM site_administrator_menu
            WHERE administrator_menu_is_active = '1'
            " . $whereOption . "
            ORDER BY administrator_menu_order_by ASC
        ";
        return $this->db->query($sql)->getResult();
    }

    public function getAdministratorMenu($administratorGroupID = 0, $menuParID = null)
    {
        $whereOption = "";
        if ($menuParID != null) {
            $whereOption = " AND administrator_menu_par_id = '" . $menuParID . "'";
        }
        $sql = "
            SELECT site_administrator_menu.*,
            administrator_privilege_action
            FROM site_administrator_menu
            INNER JOIN site_administrator_privilege ON administrator_menu_id = administrator_privilege_administrator_menu_id
            WHERE administrator_menu_is_active = '1'
            AND administrator_privilege_administrator_group_id = '" . $administratorGroupID . "'
            " . $whereOption . "
            ORDER BY administrator_menu_order_by ASC
        ";
        return $this->db->query($sql)->getResult();
    }

    public function getListBank()
    {
        $sql = "SELECT * FROM ref_bank WHERE bank_is_active = '1' ORDER BY bank_name ASC";
        return $this->db->query($sql)->getResult();
    }

    public function getListCountry()
    {
        $sql = "SELECT * FROM ref_country WHERE country_is_active = '1' ORDER BY country_name ASC";
        return $this->db->query($sql)->getResult();
    }

    public function getListRegion()
    {
        $sql = "SELECT * FROM ref_region WHERE region_is_active = '1' ORDER BY region_name ASC";
        return $this->db->query($sql)->getResult();
    }

    public function getListProvince($regionID = '')
    {
        $where = "WHERE province_is_active = '1'";
        if ($regionID != '') {
            $where .= " AND province_region_id = '" . $regionID . "'";
        }
        $sql = "SELECT * FROM ref_province " . $where . " ORDER BY province_id ASC";

        return $this->db->query($sql)->getResult();
    }

    public function getListCity($provinceID = '')
    {
        $where = "WHERE city_is_active = '1'";
        if ($provinceID != '') {
            $where .= " AND city_province_id = '" . $provinceID . "'";
        }
        $sql = "SELECT * FROM ref_city " . $where . " ORDER BY city_name ASC";

        return $this->db->query($sql)->getResult();
    }

    public function getListSubdistrict($cityID = '')
    {
        $where = "WHERE 1";
        if ($cityID != '') {
            $where .= " AND subdistrict_city_id = '" . $cityID . "'";
        }
        $sql = "SELECT * FROM ref_subdistrict " . $where . " ORDER BY subdistrict_name ASC";

        return $this->db->query($sql)->getResult();
    }

    public function getCityProvinceOptions()
    {
        $options = array();
        $rowProvince = self::getListProvince();
        if (!empty($rowProvince)) {
            foreach ($rowProvince as $province) {
                $rowCity = self::getListCity($province->province_id);
                if (!empty($rowCity)) {
                    foreach ($rowCity as $city) {
                        $options[$province->province_name][$city->city_id] = $city->city_name;
                    }
                }
            }
        }
        return $options;
    }

    public function getCityOptions()
    {
        $options = array();
        $rowCity = self::getListCity();
        if (!empty($rowCity)) {
            foreach ($rowCity as $row) {
                $options[$row->city_id] = $row->city_name;
            }
        }
        return $options;
    }

    public function getProvinceOptions()
    {
        $options = array();
        $query = self::getListProvince();
        if (!empty($query)) {
            foreach ($query as $row) {
                $options[$row->province_id] = $row->province_name;
            }
        }
        return $options;
    }

    public function getRegionOptions()
    {
        $options = array();
        $query = self::getListRegion();
        if (!empty($query)) {
            foreach ($query as $row) {
                $options[$row->region_id] = $row->region_name;
            }
        }
        return $options;
    }

    public function getCountryOptions()
    {
        $options = array();
        $options[null] = '=========== Pilih Negara ===========';
        $query = self::getListCountry();
        if (!empty($query)) {
            foreach ($query as $row) {
                $options[$row->country_id] = $row->country_name;
            }
        }
        return $options;
    }

    public function getNetworkCode($networkID)
    {
        $builder = $this->db->table('sys_network');
        $builder->select('network_code');
        $builder->where('network_id', $networkID);
        $data = $builder->get()->getRow();
        return empty($data) ? '' : $data->network_code;
    }

    public function getBankOptions()
    {
        $options = array();
        $options[null] = 'Pilih Bank';
        $query = self::getListBank();
        if (!empty($query)) {
            foreach ($query as $row) {
                $options[$row->bank_id] = $row->bank_name;
            }
        }
        return $options;
    }

    public function getIdentityTypeOptions()
    {
        $options = array();
        $options[null] = 'Jenis Identitas';
        $options['ktp'] = 'KTP';
        $options['sim'] = 'SIM';
        $options['paspor'] = 'Paspor';

        return $options;
    }

    public function getSexOptions()
    {
        $options = array();
        $options['male'] = 'Pria';
        $options['female'] = 'Wanita';

        return $options;
    }

    public function getCityGridOptions()
    {
        $gridOptions = '';
        $query = $this->getListCity();
        if (!empty($query)) {
            foreach ($query as $row) {
                $gridOptions .= $row->city_id . ':' . $row->city_name . '|';
            }
            $gridOptions = rtrim($gridOptions, '|');
        }
        return $gridOptions;
    }

    public function getProvinceGridOptions()
    {
        $gridOptions = '';
        $query = $this->getListProvince();
        if (!empty($query)) {
            foreach ($query as $row) {
                $gridOptions .= $row->province_id . ':' . $row->province_name . '|';
            }
            $gridOptions = rtrim($gridOptions, '|');
        }
        return $gridOptions;
    }

    public function getRegionGridOptions()
    {
        $gridOptions = '';
        $query = $this->getListRegion();
        if (!empty($query)) {
            foreach ($query as $row) {
                $gridOptions .= $row->region_id . ':' . $row->region_name . '|';
            }
            $gridOptions = rtrim($gridOptions, '|');
        }
        return $gridOptions;
    }

    public function getCountryGridOptions()
    {
        $gridOptions = '';
        $query = $this->getListCountry();
        if (!empty($query)) {
            foreach ($query as $row) {
                $gridOptions .= $row->country_id . ':' . $row->country_name . '|';
            }
            $gridOptions = rtrim($gridOptions, '|');
        }
        return $gridOptions;
    }

    public function getBankGridOptions()
    {
        $gridOptions = '';
        $query = $this->getListBank();
        if (!empty($query)) {
            foreach ($query as $row) {
                $gridOptions .= $row->bank_id . ':' . $row->bank_name . '|';
            }
            $gridOptions = rtrim($gridOptions, '|');
        }
        return $gridOptions;
    }

    public function getSiteConfiguration()
    {
        $sql = "SELECT configuration_name, configuration_value FROM site_configuration";
        $query = $this->db->query($sql)->getResult();

        $siteConfiguration = array();
        if (!empty($query)) {
            foreach ($query as $row) {
                $siteConfiguration[$row->configuration_name] = $row->configuration_value;
            }
        }
        return $siteConfiguration;
    }

    public function getSysConfiguration()
    {
        $sql = "SELECT configuration_name, configuration_value FROM sys_configuration";
        $query = $this->db->query($sql)->getResult();

        $sysConfiguration = array();
        if (!empty($query)) {
            foreach ($query as $row) {
                $sysConfiguration[$row->configuration_name] = $row->configuration_value;
            }
        }
        return $sysConfiguration;
    }

    public function getLastRecord($table, $fieldname, $fieldshort)
    {
        $builder = $this->db->table($table);
        $builder->select($fieldname);
        $builder->orderBy($fieldshort, 'desc');
        $builder->limit(1);
        $data = $builder->get()->getRow();
        if (!empty($data)) {
            return $data->$fieldname;
        }
        return 0;
    }

    public function sendNotificationCron($namaCron, $startDate, $endDate)
    {
        $pesan = "Cron *$namaCron*\n``` Mulai : $startDate```\n``` Selesai : $endDate```";
        $this->sendTelegramMessage($pesan);
    }

    public function sendTelegramMessage($messaggio)
    {
        $token = "bot781140062:AAFSlaYuUyQ-OFr9VyozZjhuvaMf_f6VYB8";
        //   $chatIDDef = "365089063"; // Private Chat Om racun
        $chatIDDef = "-1001367456541"; // [Dev] Limau Kasturi
        $ip = getHostByName(getHostName());
        $messaggio = "``` FROM : {$ip} ```\n" . $messaggio;
        $url = "https://api.telegram.org/" . $token . "/sendMessage?chat_id=" . $chatIDDef;
        $url = $url . "&text=" . urlencode($messaggio) . "&parse_mode=Markdown";
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function getRealIP()
    {
        $ip = '';

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function templateMsgBonus($network_code)
    {
        $message = 'Yess! Bonus ' . $network_code . ' telah berhasil di transfer pada rekening yang sudah anda daftarkan. Segera lakukan cek mutasi. MJA SUMBER SEHAT SUMBER UANG.. ';
        return $message;
    }

    public function sendSMS($message, $telepon, $networkID = '', $type = 'single')
    {

        $telepon = preg_replace("/^0/", "62", trim($telepon));
        $userkey = "54827a2658f9"; // userkey lihat di zenziva
        $passkey = "edcc2c0c23e4b359a445ad91"; // set passkey di zenziva
        // $url = "https://reguler.zenziva.net/apps/smsapi.php";
        $url = 'https://console.zenziva.net/reguler/api/sendsms/';

        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $url);

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&to=' . $telepon . '&message=' . urlencode($message));

        curl_setopt($curlHandle, CURLOPT_HEADER, 0);

        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);

        curl_setopt($curlHandle, CURLOPT_POST, 1);

        $response = curl_exec($curlHandle);

        $res = json_decode($response);
        if ($res->text == 'Success') {
            return true;
        } else {
            return false;
        }

        curl_close($curlHandle);
    }

    public function sendWA($message, $telepon, $networkID = '', $type = 'single')
    {

        $telepon = preg_replace("/^0/", "62", trim($telepon));
        $userkey = '8ecfecc65640ca4001ab1b6652de9b7466c7ff063e3f4375';
        $url = 'http://116.203.191.58/api/send_message';

        $data = array(
            "phone_no" => $telepon,
            "key" => $userkey,
            "message" => $message,
        );

        $data_string = json_encode($data, 1);

        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_VERBOSE, 0);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 360);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'Authorization: Basic dXNtYW5ydWJpYW50b3JvcW9kcnFvZHJiZWV3b293YToyNjM3NmVkeXV3OWUwcmkzNDl1ZA==',
        ));

        $response = curl_exec($curlHandle);

        if ($response == 'Success') {
            return true;
        } else {
            return false;
        }

        curl_close($curlHandle);
    }

    public function send_waone($message, $receiver)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => WA_URL."/api/v2/worker/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('content' => $message, 'phonenumber' => $receiver),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.WA_KEY
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($response);
        $log = [
            "notification_type" => "wa",
            "notification_session" => session_id(),
            "notification_content" => $message,
            "notification_sender" => "",
            "notification_receiver" => $receiver,
            "notification_response" => $response,
            "notification_status" => @$res->data->status == "success" ? "Terkirim" : "Gagal",
            "notification_datetime" => @$res->data->updatedAt ? date("Y-m-d H:i:s", strtotime($res->data->updatedAt)) : date("Y-m-d H:i:s"),
        ];
        $this->db->table("log_notification")->insert($log);

        return $response;
    }

    public function insertSMS($message, $telepon, $networkID, $type)
    {
        if (empty($networkID)) {
            $result = $this->db->query("SELECT member_network_id FROM sys_member WHERE member_mobilephone = '{$telepon}' limit 1")->getRow();
            $networkID = 0;
            if (!empty($result)) {
                $networkID = $result->member_network_id;
            }
        }

        $dataInsert = array(
            'sms_gateway_network_id' => $networkID,
            'sms_gateway_mobilephone' => $telepon,
            'sms_gateway_content' => $message,
            'sms_gateway_type' => $type,
            'sms_gateway_datetime' => date("Y-m-d H:i:s"),
        );
        $builder = $this->db->table('site_sms_gateway');
        $builder->insert($dataInsert);
    }

    public function getWeekDateRange($date, $weeklyStartDay = 0)
    {
        $arrDays = array(
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        );
        $strDate = strtotime($date);
        $strStartDate = (date('w', $strDate) == $weeklyStartDay) ? $strDate : strtotime('last ' . $arrDays[$weeklyStartDay], $strDate);
        $startDate = date("Y-m-d", $strStartDate);
        $endDate = date("Y-m-d", mktime(0, 0, 0, date("n", strtotime($startDate)), date("j", strtotime($startDate)) + 6, date("Y", strtotime($startDate))));

        return array($startDate, $endDate);
    }

    public function whereFilter()
    {
        $filters = $_POST['filters'] ?? '';
        $search = $_POST['_search'] ?? false;

        $where = "WHERE 0 = 0 ";

        if (($search == true) && ($filters != "")) {

            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules ?? array();
            $groupOperation = $filters->groupOp ?? '';
            $fieldOperationCompare = '';
            foreach ($rules as $rule) {

                $fieldName = $rule->field;
                $fieldData = $rule->data;
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }
            }
            if (count($whereArray) > 0) {
                $where .= " AND " . join(" " . $groupOperation . " ", $whereArray);
            }
        }

        return $where;
    }

    public function getQueryCondition($params, $count = false)
    {
        $arrCondition = array();

        $arrCondition['parentSelect'] = "*";
        if ($count) {
            $arrCondition['parentSelect'] = "COUNT(*) AS row_count";
        }

        $arrCondition['table'] = "";
        if (isset($params['table'])) {
            $arrCondition['table'] = $params['table'];
        }

        $arrCondition['select'] = "*";
        if (isset($params['select'])) {
            $arrCondition['select'] = $params['select'];
        }

        $arrCondition['join'] = "";
        if (isset($params['join'])) {
            $arrCondition['join'] = $params['join'];
        }

        $arrCondition['whereDetail'] = " WHERE 1 ";
        if (isset($params['whereDetail'])) {
            $arrCondition['whereDetail'] .= "AND " . $params['whereDetail'];
        }

        $arrCondition['groupByDetail'] = "";
        if (isset($params['groupByDetail'])) {
            $arrCondition['groupByDetail'] = "GROUP BY " . $params['groupByDetail'];
        }

        $arrCondition['where'] = " WHERE 1 ";

        if (isset($params['querys']) && $params['querys'] != false && $params['querys'] != '') {
            $querys = json_decode($params['querys']);
            foreach ($querys as $query) {
                if ($query->filterType == 'querysText') {
                    $arrCondition['where'] .= "AND " . $query->filterField . " LIKE '%" . $this->db->escapeString($query->filterValue) . "%' ";
                } else if ($query->filterType == 'querysNumStart') {
                    $arrCondition['where'] .= "AND " . $query->filterField . " >= " . $this->db->escapeString($query->filterValue) . " AND " . $this->db->escapeString($query->filterValue) . " ";
                } else if ($query->filterType == 'querysNumEnd') {
                    $arrCondition['where'] .= "AND " . $query->filterField . " <= " . $this->db->escapeString($query->filterValue) . " AND " . $this->db->escapeString($query->filterValue) . " ";
                } else if ($query->filterType == 'querysOption') {
                    $arrCondition['where'] .= "AND " . $query->filterField . " = '" . $this->db->escapeString($query->filterValue) . "' ";
                } else if ($query->filterType == 'querysDate') {
                    $dates = explode('s/d', $query->filterValue);
                    $startDate = trim($dates[0]);
                    $endDate = trim($dates[1]);
                    $arrCondition['where'] .= "AND DATE(" . $query->filterField . ") BETWEEN '" . $this->db->escapeString($startDate) . "' AND '" . $this->db->escapeString($endDate) . "' ";
                }
            }
        }

        if (isset($params['query']) && $params['query'] != false && $params['query'] != '') {
            $arrCondition['where'] .= "AND " . $params['qtype'] . " LIKE '%" . $this->db->escapeString($params['query']) . "%' ";
        } elseif (isset($params['optionused']) && $params['optionused'] == 'true') {
            $arrCondition['where'] .= "AND " . $params['qtype'] . " = '" . $params['option'] . "' ";
        } elseif ((isset($params['dateStart']) && $params['dateStart'] != false) && (isset($params['dateEnd'])) && $params['dateEnd'] != false) {
            $arrCondition['where'] .= "AND DATE(" . $params['qtype'] . ") BETWEEN '" . $this->db->escapeString($params['dateStart']) . "' AND '" . $this->db->escapeString($params['dateEnd']) . "' ";
        } elseif ((isset($params['numStart']) && $params['numStart'] != false) && (isset($params['numEnd'])) && $params['numEnd'] != false) {
            $arrCondition['where'] .= "AND " . $params['qtype'] . " BETWEEN '" . $this->db->escapeString($params['numStart']) . "' AND '" . $this->db->escapeString($params['numEnd']) . "' ";
        }
        if (isset($params['where'])) {
            $arrCondition['where'] .= "AND " . $params['where'];
        }

        $arrCondition['groupBy'] = "";
        if (isset($params['groupBy'])) {
            $arrCondition['groupBy'] = "GROUP BY " . $params['groupBy'];
        }

        $arrCondition['sort'] = "";
        if (isset($params['sortname']) && isset($params['sortorder']) && $count == false) {
            if ($params['sortname'] != '' && $params['sortorder'] != '') {
                $arrCondition['sort'] = "ORDER BY " . $params['sortname'] . " " . $params['sortorder'];
            }
        }

        $arrCondition['limit'] = "";
        if (isset($params['rp']) && $count == false) {
            $offset = (($params['page'] - 1) * $params['rp']);
            $arrCondition['limit'] = "LIMIT $offset, " . $params['rp'];
        }

        return $arrCondition;
    }

    public function get_query_data($params)
    {
        extract($this->getQueryCondition($params));

        $sql = "
            SELECT SQL_CALC_FOUND_ROWS * FROM (
                SELECT $parentSelect
                FROM
                (
                    SELECT $select
                    FROM $table
                    $join
                    $whereDetail
                    $groupByDetail
                ) result

            )result
            $where
            $groupBy
            $sort
            $limit
        ";

        $query = $this->db->query($sql)->getResult();
        $total = $this->db->query('SELECT FOUND_ROWS() as total')->getRow()->total;
        $output['data'] = $query;
        $output['total'] = $total;

        return $output;
    }

    public function convertDatetime($date, $lang = 'id', $type = 'text', $formatdate = '.', $formattime = ':', $use_time = true)
    {

        if (!empty($date)) {
            if ($type == 'num') {
                $date_converted = str_replace('-', $formatdate, str_replace(':', $formattime, $date));
            } else {
                $year = substr($date, 0, 4);
                $month = substr($date, 5, 2);
                $month = $this->convertMonth($month, $lang);
                $day = substr($date, 8, 2);
                $time = strlen($date) > 10 ? substr($date, 11, 8) : '';
                $time = str_replace(':', $formattime, $time);

                $date_converted = $day . ' ' . $month . ' ' . $year . ' ';
                if ($use_time == true) {
                    $date_converted .= $time;
                }
            }
        } else {
            $date_converted = '-';
        }
        return $date_converted;
    }

    public function convertMonth($month, $lang = 'en')
    {
        $month = (int) $month;
        switch ($lang) {
            case 'id':
                $arr_month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                break;

            default:
                $arr_month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                break;
        }

        if (array_key_exists($month - 1, $arr_month)) {
            $month_converted = $arr_month[$month - 1];
        } else {
            $month_converted = '';
        }

        return $month_converted;
    }

    // Message Common Function
    public function cutText($text, $length, $mode = 2)
    {

        if ($mode != 1) {
            $char = $text[$length - 1];
            switch ($mode) {
                case 2:
                    while ($char != ' ') {
                        $char = $text[--$length];
                    }
                case 3:
                    while ($char != ' ') {
                        $char = $text[++$length];
                    }
            }
        }

        return substr($text, 0, $length);
    }

    public function format_nominal($prefix, $value, $length)
    {
        $num = $prefix . number_format($value, $length, ',', '.');
        return substr($num, 0, -3);
    }

    public function convert_nominal($value)
    {
        if (is_numeric($value)) {
            if (strpos($value, ".") !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function resizeImage($saveToDir, $imagePath, $imageName, $max_x, $max_y)
    {
        preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $imageName, $ext);
        switch (strtolower($ext[2])) {
            case 'jpg':
            case 'jpeg':
                $img = imagecreatefromjpeg($imagePath);
                break;
            case 'gif':
                $img = imagecreatefromgif($imagePath);
                break;
            case 'png':
                $img = imagecreatefrompng($imagePath);
                break;
            default:
                $stop = true;
                break;
        }

        if (!isset($stop)) {
            $x = imagesx($img);
            $y = imagesy($img);

            if (($max_x / $max_y) < ($x / $y)) {
                $save = imagecreatetruecolor($x / ($x / $max_x), $y / ($x / $max_x));
            } else {
                $save = imagecreatetruecolor($x / ($y / $max_y), $y / ($y / $max_y));
            }
            imagecopyresized($save, $img, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);

            switch (strtolower($ext[2])) {
                case 'jpg':
                case 'jpeg':
                    unlink($imagePath);
                    imagejpeg($save, "{$saveToDir}{$ext[1]}.{$ext[2]}");
                    break;
                case 'gif':
                    unlink($imagePath);
                    imagegif($save, "{$saveToDir}{$ext[1]}.{$ext[2]}");
                    break;
                case 'png':
                    unlink($imagePath);
                    imagepng($save, "{$saveToDir}{$ext[1]}.{$ext[2]}");
                    break;
                default:
                    $stop2 = true;
                    break;
            }
            imagedestroy($img);
            imagedestroy($save);

            if (!isset($stop2)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
