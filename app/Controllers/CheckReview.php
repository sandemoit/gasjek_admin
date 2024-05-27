<?php

namespace App\Controllers;

use App\Models\ReviewModel;
use CodeIgniter\RESTful\ResourceController;

class CheckReview extends ResourceController
{
    protected $modelName = 'App\Models\CheckReviewModel';
    protected $format = 'json';
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $id_restaurant = $this->request->getVar('id_restaurant');
        $id_user = $this->request->getVar('id_user');

        $model = $this->reviewModel;

        // Memeriksa apakah ada ulasan yang sudah dibuat
        $reviewCount = $model->where('id_restaurant', $id_restaurant)
            ->where('id_user', $id_user)
            ->countAllResults();

        // Persiapan respons
        $response = [
            'status' => 1,
            'message' => 'success',
        ];

        // Memeriksa apakah ada ulasan yang sudah dibuat
        if ($reviewCount > 0) {
            $response['status'] = 1; // Status 1 menunjukkan bahwa ulasan telah dibuat
            $response['message'] = "Telah Memberi Ulasan Dibuat";
        } else {
            $response['status'] = 2; // Status 2 menunjukkan bahwa ulasan belum dibuat
            $response['message'] = "Belum Memberi Ulasan";
        }

        return $this->respond($response);
    }
}
