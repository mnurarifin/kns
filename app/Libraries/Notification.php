<?php

namespace App\Libraries;


class Notification
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function send_waone($message, $receiver)
    {
        $token = $this->db->table('sys_config')
            ->where("config_code = 'WAONE_URL' OR config_code = 'WAONE_TOKEN'")
            ->orderBy('config_code', 'desc')
            ->get()
            ->getResult();

        $waone_url = $token[0]->config_value;
        $waone_token = $token[1]->config_value;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $waone_url . "/api/v2/worker/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('content' => $message, 'phonenumber' => $receiver),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $waone_token
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

    public function send_woowa($message, $receiver)
    {
        $receiver = preg_replace("/^0/", "62", trim($receiver));
        $userkey = getEnv('WA_KEY');
        $url = getEnv('WA_URL');

        $data = array(
            "phone_no" => $receiver,
            "key"     => $userkey,
            "message" => $message
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
            'Authorization: Basic dXNtYW5ydWJpYW50b3JvcW9kcnFvZHJiZWV3b293YToyNjM3NmVkeXV3OWUwcmkzNDl1ZA=='
        ));

        $response = curl_exec($curlHandle);
        curl_close($curlHandle);

        if ($response == 'Success') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function send_sms_viro($message, $receiver)
    {
        $receiver = preg_replace("/^0/", "62", trim($receiver));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.smsviro.com/restapi/sms/1/text/single',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "from" : "",
                "to" : "' . $receiver . '",
                "text" : "' . $message . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: App xxx',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response);
        if ($res->messages[0]->status->groupName == "REJECTED") {
            return false;
        } else {
            return true;
        }
    }

    public function send_sms_zenziva($message, $receiver)
    {
        $telepon = preg_replace("/^0/", "62", trim($receiver));
        $userkey = ""; // userkey lihat di zenziva
        $passkey = ""; // set passkey di zenziva
        $url = 'https://console.zenziva.net/reguler/api/sendsms/';

        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&to=' . $telepon . '&message=' . urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);

        $response = curl_exec($curlHandle);

        curl_close($curlHandle);

        $res = json_decode($response);
        if ($res->text == 'Success') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function send_email_php($to, $subject, $message, $title = '', $email = '', $attacment_link = null)
    {
        $config = [
            'mailType' => 'html',
            'charset' => 'utf-8',
            'protocol' => getEnv('SMTP_PROTOCOL'),
            'SMTPHost' =>  getEnv('SMTP_HOST'),
            'SMTPUser' =>  getEnv('SMTP_USER'),
            'SMTPPass' =>  getEnv('SMTP_PASSWORD'),
            'SMTPCrypto' =>  getEnv('SMTP_CRYPTO'),
            'SMTPPort' =>  getEnv('SMTP_PORT'),
            'CRLF' => "\r\n",
            'newline' => "\r\n"
        ];

        $email = \Config\Services::email();
        $email->initialize($config);

        $email->setFrom('balmedbeauty.official@gmail.com', $title);
        $email->setTo($to);

        $email->setSubject($subject);
        $email->setMessage($message);

        if (!$email->send()) {
            return false;
        }

        return true;
    }
}
