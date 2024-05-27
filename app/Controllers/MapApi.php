<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class MapApi extends ResourceController
{
    protected $modelName = 'App\Models\MapModel';
    protected $format = 'json';

    public function index()
    {

        $model = $this->model->findAll();
      
        $response = [
            'status'   => 1,
            'message'    => 'success',
            'dataDistance' => $model
        ];

        return $this->respond($response);
    }

    

}

?>