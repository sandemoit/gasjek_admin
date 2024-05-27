<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RestaurantModel;
use App\Models\ReviewModel;
use App\Models\UserModelApi;

class ReviewApi extends ResourceController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $id_restaurant = $this->request->getVar('id_restaurant');

        // Ambil model Review
        $model = new ReviewModel();

        // Cari ulasan berdasarkan id_restaurant
        $reviews = $model->where('id_restaurant', $id_restaurant)->findAll();

        // Jika ada ulasan
        if (!empty($reviews)) {
            $data = [];

            // Loop melalui setiap ulasan
            foreach ($reviews as $review) {
                // Ambil data pengguna berdasarkan id_user
                $id_pengguna = $review['id_user'];
                $user = $this->getUserData($id_pengguna);

                // Tambahkan detail ulasan beserta data pengguna ke dalam array data
                $data[] = [
                    'id_review' => $review['id_review'],
                    'id_restaurant' => $review['id_restaurant'],
                    'id_user' => $review['id_user'],
                    'user_name' => $user['nama_pengguna'],
                    'rating' => $review['rating'],
                    'review_user' => $review['review_user'],
                    'image_user' => isset($user['gambar_pengguna']) ? $user['gambar_pengguna'] : null
                ];
            }
        } else {
            // Jika tidak ada ulasan
            $data = null;
        }

        // Menyiapkan respons
        $response = [
            'status' => 1,
            'message' => 'success',
            'dataReview' => $data
        ];

        // Mengembalikan respons
        return $this->respond($response);
    }

    // Fungsi untuk mendapatkan data pengguna berdasarkan id_user
    private function getUserData($id_pengguna)
    {
        $model = new UserModelApi();
        $user = $model->find($id_pengguna);

        return $user;
    }

    public function send_review_api()
    {
        $id_restaurant = $this->request->getPost('id_restaurant');
        $user_name = $this->request->getPost('user_name');
        $id_user = $this->request->getPost('id_user');
        $rating = $this->request->getPost('rating');
        $review_user = $this->request->getPost('review_user');
        $total_rating = $this->request->getPost('total_rating');

        // Update rating restoran
        $restaurantModel = new RestaurantModel();
        $restaurantModel->setRating($id_restaurant, $total_rating);

        // Simpan ulasan ke dalam database
        $data = [
            'id_restaurant' => $id_restaurant,
            'user_name' => $user_name,
            'id_user' => $id_user,
            'rating' => $rating,
            'review_user' => $review_user
        ];

        $this->reviewModel->insert($data);

        $response = [
            'status'   => 1,
            'message'  => 'Ulasan Berhasil Dikirim'
        ];

        return $this->respondCreated($response);
    }
}
