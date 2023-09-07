<?php

namespace App\Models\Member;

use CodeIgniter\Model;
use App\Libraries\DataTable;

class Message_model extends Model
{
    public function init($request = null)
    {
        $this->request = $request;
    }

    public function getMessage($network_code, $status, $type)
    {
        $whereStatus = " AND message_status <> 3";
        $whereType = "
        message_receiver_ref_type = 'member'
        AND message_receiver_ref_code = '{$network_code}'";

        if ($status == 'message') {
            $whereStatus = " AND message_status IN (0,1)";
        } else if ($status == 'archive') {
            $whereStatus = " AND message_status = 2";
        }

        if ($type == 'sender') {
            $whereType = "message_sender_ref_code = '{$network_code}'";
        } else if ($type == 'all') {
            $whereType = "1
            AND(message_sender_ref_code = '{$network_code}'
            OR message_receiver_ref_code = '{$network_code}')
            ";
        }

        $tableName = "site_message";
        $columns = [
            "message_id",
            "message_type",
            "message_sender_ref_type",
            "message_sender_ref_id",
            "message_sender_ref_code",
            "message_sender_ref_name",
            "message_receiver_ref_type",
            "message_receiver_ref_id",
            "message_receiver_ref_code",
            "message_receiver_ref_name",
            "message_content",
            "message_status",
            "message_datetime",
        ];
        $joinTable = "";
        $whereCondition = "
        {$whereType}
        {$whereStatus}";
        $groupBy = "";

        $this->dataTable = new DataTable();
        $arr_data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($arr_data["results"] as $key => $value) {
            if ($value["message_datetime"]) {
                $arr_data["results"][$key]["message_datetime"] = convertDatetime($value["message_datetime"], "id");
            }
        }

        return $arr_data;
    }

    public function getDetail($message_id)
    {
        return $this->db->table('site_message')->getWhere(['message_id' => $message_id])->getRow();
    }

    public function archive($message_id)
    {
        $this->db->table('site_message')
            ->where('message_id', $message_id)
            ->update(['message_status' => 2]);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal arsip pesan", 1);
        }

        return true;
    }

    public function unarchive($message_id)
    {
        $this->db->table('site_message')
            ->where('message_id', $message_id)
            ->update(['message_status' => 1]);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal buka arsip pesan", 1);
        }

        return true;
    }

    public function remove($message_id)
    {
        $this->db->table('site_message')
            ->where('message_id', $message_id)
            ->update(['message_status' => 3]);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal hapus pesan", 1);
        }

        return true;
    }

    public function add($data)
    {
        $this->db->table("site_message")->insert($data);

        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal kirim pesan", 1);
        }

        return true;
    }
}
