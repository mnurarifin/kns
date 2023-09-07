<?php

namespace App\Cron;

use App\Controllers\BaseController;

class Ewallet extends BaseController
{
    public function __construct()
    {
        $this->cron_service = service('Cron');
    }
}
