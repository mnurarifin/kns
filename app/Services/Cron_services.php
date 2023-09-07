<?php

namespace App\Services;

// use App\CodeIgniter\Config\BaseService;
use App\Controllers\BaseController;
use App\Models\Common_model;

// class Cron_services extends BaseService
class Cron_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function check_log($cron_name, $date)
    {
        //cek apakah ada data cron log pada tanggal tsb
        $count = $this->db->table('log_cron')->selectCount('cron_id')->where('cron_name', $cron_name)->where('cron_date', $date)->countAllResults();
        if($count > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insert_log($cron_name, $result, $date, $datetime_start, $datetime_end)
    {
        //insert ke tabel cron log
        $arr_data = [];
        $arr_data['cron_name'] = $cron_name;
        $arr_data['cron_result'] = $result;
        $arr_data['cron_date'] = $date;
        $arr_data['cron_datetime_start'] = $datetime_start;
        $arr_data['cron_datetime_end'] = $datetime_end;
        if($this->common_model->insertData('log_cron', $arr_data) == FALSE) {
            throw new \Exception("Gagal menyimpan cron log.", 1);
        }
    }
}
