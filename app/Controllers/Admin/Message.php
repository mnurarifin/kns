<?php

namespace App\Controllers\Admin;

class Message extends BaseController
{

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Pesan' => 'admin/message/show',
            'Kotak Masuk' => 'message/show'
        );

        $this->template->title('Kotak Masuk');
        $this->template->content('Admin/messageView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function start_chat()
    {
        $data['arrBreadcrumbs'] = array(
            'Pesan' => 'admin/message/start_chat',
            'Buat Pesan' => 'message/start'
        );

        $this->template->title('Buat Pesan');
        $this->template->content('Admin/messageStartView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function arsip()
    {
        $data['arrBreadcrumbs'] = array(
            'Pesan' => 'admin/message/arsip',
            'Arsip' => 'message/show'
        );

        $this->template->title('Arsip');
        $this->template->content('Admin/messageArchive', $data);
        $this->template->show('Template/Admin/main');
    }

    public function draft()
    {
        $data['arrBreadcrumbs'] = array(
            'Pesan' => 'admin/message/draft',
            'Draft' => 'message/draft'
        );

        $this->template->title('Draft');
        $this->template->content('Admin/messageDraftView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function send()
    {
        $data['arrBreadcrumbs'] = array(
            'Pesan' => 'admin/message/send',
            'Terkirim' => 'message/send'
        );

        $this->template->title('Terkirim');
        $this->template->content('Admin/messageSend', $data);
        $this->template->show('Template/Admin/main');
    }
}
