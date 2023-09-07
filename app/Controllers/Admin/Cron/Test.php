<?php

namespace App\Controllers\Admin\Cron;

use App\Controllers\BaseController;
use App\Libraries\Notification;

class Test extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->notification_lib = new Notification();
    }

    public function syloer()
    {
        $_GET['test syloer'];
    }

    public function waone()
    {
        $text = $this->request->getVar('message');
        $number = $this->request->getVar('phone');

        if (!$text) {
            echo 'message tidak boleh kosong';
            die;
        }
        if (!$number) {
            echo 'no tidak boleh kosong';
        }

        $response = json_decode($this->notification_lib->send_waone($text, $number));
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        if ($response) {
            if ($response->data->status == 'failed') {
                $this->tele(json_encode($response));
            }
        } else {
            $this->tele("tidak dapat response dari waone");
        }
    }

    function tele($res)
    {
        $url = "https://api.telegram.org/bot" . TM_SENDER_TOKEN . "/sendMessage?chat_id=" . TM_BUGS_CENTER;
        $hostname = (isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] != '') ? $_SERVER["HTTP_HOST"] : gethostname();
        $content = array(
            'text' => $hostname . "
" . $res,
            'parse_mode' => 'HTML'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
