<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class FeatureApi extends ResourceController
{
    protected $modelName = 'App\Models\FeatureModel';
    protected $format = 'json';

    public function index()
    {

        $model = $this->model->findAll();
      
        $response = [
            'status'   => 1,
            'message'    => 'success',
            'dataFeature' => $model
        ];

        return $this->respond($response);
    }

    

}

?>