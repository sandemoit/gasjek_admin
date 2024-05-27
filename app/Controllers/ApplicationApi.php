<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ApplicationApi extends ResourceController
{
    protected $modelName = 'App\Models\ApplicationModel';
    protected $format = 'json';

    public function index()
    {
        $model = $this->model->findAll();

        $response = [
            'status'   => 1,
            'message'    => 'success',
            'dataApplication' => $model
        ];

        return $this->respond($response);
    }
}
