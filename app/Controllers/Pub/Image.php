<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;
use App\Models\Pub\Image_model;

class Image extends BaseController
{
    public function __construct()
    {
        $this->image_model = new Image_model();
    }

    public function index()
    {
        // get all assets ajax controller
        $this->db->transBegin();
        try {
            $params = getRequestParamsData($this->request, "GET");
            $where = $params->category_id;
            $data = $this->image_model->getImage($where, $params->type);

            $this->db->transCommit();
            $this->restLib->responseSuccess("Data Image.", ['results' => $data]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->restLib->responseFailed($e->getMessage(), "process", [], getenv('CI_ENVIRONMENT') == 'development' ? ["line" => $e->getLine(), "file" => $e->getFile(), "trace" => $e->getTrace()] : []);
        }
    }
}
