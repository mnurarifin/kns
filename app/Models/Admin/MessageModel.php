<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table      = 'site_message';
    protected $primaryKey = 'message_id';
    
    protected $allowedFields = [
        'message_type',
        'message_sender_type',
        'message_sender_id',
        'message_sender_network_code',
        'message_sender_name',
        'message_receive_type',
        'message_receive_id',
        'message_receive_network_code',
        'message_receive_name',
        'message_content',
        'message_status',
        'message_datetime'
    ];

    public function archiveMessage($id)
    {
    	$query = "
	        UPDATE site_message
	        SET message_status = 2
	        WHERE message_id = ?
        ";
        return $this->query($query, [$id]);
    }

    public function readMessage($id)
    {
    	$query = "
	        UPDATE site_message
	        SET message_status = 1
	        WHERE message_id = ?
        ";
        return $this->query($query, [$id]);
    }


    public function deleteMessage($id)
    {
    	$query = "
	        UPDATE site_message
	        SET message_status = 3
	        WHERE message_id = ?
        ";
        return $this->query($query, [$id]);
    }

    public function removeMenu($id)
    {
        $query = "DELETE FROM site_administrator_menu WHERE administrator_menu_id = ?";
        return $this->query($query, [$id]);
    }
}
