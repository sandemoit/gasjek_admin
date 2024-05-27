<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class BannerApi extends ResourceController
{
    protected $modelName = 'App\Models\BannerModel';
    protected $format = 'json';

    public function index()
    {
        $model = $this->model->findAll();
      
        $response = [
            'status'   => 1,
            'message'    => 'success',
            'dataBanners' => $model
        ];

      
        return $this->respond($response);
    }

}

?>