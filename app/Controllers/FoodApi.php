<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\FoodModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class FoodApi extends ResourceController
{
    use ResponseTrait;

    protected $foodModel;

    public function __construct()
    {
        $this->foodModel = new FoodModel();
    }

    public function index()
    {
        // Mengambil parameter dari request
        $id_restaurant = $this->request->getVar('id_restaurant');
        $id_food = $this->request->getVar('id_food');
        $category = $this->request->getVar('food_category');

        // Inisialisasi variabel model dengan seluruh data makanan
        $foods = [];

        // Memeriksa apakah parameter id_restaurant atau id_food tidak null
        if ($category !== null) {
            if ($category == 'drink') {
                $foods = $this->foodModel->getFoodByCategory($category, $id_restaurant);
            } elseif ($category == 'food') {
                $foods = $this->foodModel->getFoodByCategory($category, $id_restaurant);
            }
        } elseif ($id_restaurant != null) {
            // Jika id_restaurant tidak null, ambil data makanan berdasarkan id_restaurant
            $foods = $this->foodModel->getFood($id_restaurant);
        } elseif ($id_food != null) {
            // Jika id_food tidak null, ambil data makanan berdasarkan id_food
            $foods = [$this->foodModel->getFoodId($id_food)];
        }

        // Menyiapkan respons
        $response = [
            'status' => 200,
            'message' => 'success',
            'dataFood' => $foods
        ];

        // Mengembalikan respons
        return $this->respond($response);
    }

    public function create()
    {
        try {
            // Mendapatkan data dari request
            $id_restaurant = $this->request->getVar('id_restaurant');
            $food_name = $this->request->getVar('food_name');
            $food_price = $this->request->getVar('food_price');
            $food_quantity = $this->request->getVar('food_quantity');
            $food_desc = $this->request->getVar('food_desc');
            $food_category = $this->request->getVar('food_category');
            $restaurant_image_base64 = $this->request->getVar('food_image');

            // Validasi input
            if (empty($id_restaurant) || empty($food_name) || empty($food_price) || empty($food_quantity) || empty($food_desc) || empty($food_image)) {
                $response = [
                    'status' => 400,
                    'message' => 'Semua kolom wajib diisi.'
                ];
            }

            // Konversi base64 ke gambar dan simpan
            if ($restaurant_image_base64) {
                $imageName = uniqid() . '.jpg';
                $filePath = 'assets/foods/' . $imageName;

                // Decode base64
                $image_data = base64_decode($restaurant_image_base64);
                file_put_contents($filePath, $image_data);

                // Kompresi gambar
                $this->compress_image($filePath);
            } else {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Gambar tidak valid atau gagal diunggah.'
                ]);
            }

            // Membuat data untuk dimasukkan ke dalam database
            $data = [
                'id_restaurant' => $id_restaurant,
                'food_name' => $food_name,
                'food_price' => $food_price,
                'food_quantity' => $food_quantity,
                'food_image' => $imageName,
                'food_desc' => $food_desc,
                'food_category' => $food_category
            ];

            // Menyimpan data makanan ke dalam database
            $foodInsert = $this->foodModel->insert($data);
            if ($foodInsert) {
                // Menyiapkan respons
                $response = [
                    'status' => 200,
                    'message' => 'Makanan Berhasil Ditambah.'
                ];
            } else {
                $response = [
                    'status' => 400,
                    'message' => 'Gagal menyimpan data makanan.'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Terjadi kesalahan atau update aplikasi Gasjek Mitra'
            ];
        }

        return $this->respond($response);
    }

    public function edit_food()
    {
        try {
            // Mengambil data dari request
            $food_name = $this->request->getVar('food_name');
            $food_price = $this->request->getVar('food_price');
            $food_quantity = $this->request->getVar('food_quantity');
            $new_image = $this->request->getVar('new_image');
            $old_image = $this->request->getVar('old_image');
            $id_food = $this->request->getVar('id_food');
            $food_desc = $this->request->getVar('food_desc');
            $food_category = $this->request->getVar('food_category');

            // Jika ada gambar baru diunggah
            if ($new_image !== "no") {
                // Konversi base64 ke gambar dan simpan
                if ($new_image) {
                    $imageName = uniqid() . '.jpg';
                    $filePath = 'assets/foods/' . $imageName;

                    // Decode base64
                    $image_data = base64_decode($new_image);
                    file_put_contents($filePath, $image_data);

                    // Kompresi gambar
                    $this->compress_image($filePath);
                } else {
                    return $this->respond([
                        'status' => 400,
                        'message' => 'Gambar tidak valid atau gagal diunggah.'
                    ]);
                }
            } else {
                // Jika tidak ada gambar baru, gunakan gambar lama
                $imageName = $old_image;
            }

            // Menyiapkan data yang akan diupdate
            $data = [
                'food_name' => $food_name,
                'food_price' => $food_price,
                'food_quantity' => $food_quantity,
                'food_image' => $imageName,
                'food_desc' => $food_desc,
                'food_category' => $food_category
            ];

            // Menyimpan data makanan yang telah diubah ke dalam database
            $update = $this->foodModel->update($id_food, $data);

            // Menyiapkan respons
            if ($update) {
                $response = [
                    'status' => 200,
                    'message' => "Makanan Berhasil Diubah"
                ];
            } else {
                $response = [
                    'status' => 400,
                    'message' => "Makanan Gagal Diubah"
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Terjadi kesalahan atau update aplikasi Gasjek Mitra'
            ];
        }

        return $this->respond($response);
    }

    // Fungsi kompresi gambar
    private function compress_image($filePath)
    {
        // Menggunakan library Image Manipulation
        $image = \Config\Services::image()
            ->withFile($filePath)
            ->save($filePath, 10); // Nilai 10 adalah kualitas kompresi, semakin rendah semakin kecil ukuran file

        return $filePath;
    }

    public function detail_food($id_food)
    {
        try {
            $food = $this->foodModel->find($id_food);

            if ($food) {
                $response = [
                    'status' => 200,
                    'message' => "success",
                    'dataDetailFood' => $food
                ];
            } else {
                $response = [
                    'status' => 404,
                    'message' => "Makanan Tidak Ditemukan"
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }

        return $this->respond($response);
    }

    public function delete_food()
    {
        $id_food = $this->request->getVar('id_food');

        try {
            $food = $this->foodModel->find($id_food);
            if ($food) {
                $this->foodModel->where('id_food', $id_food)->delete();
                // Menghapus file gambar di server
                if (file_exists("assets/foods/" . $food['food_image'])) {
                    unlink("assets/foods/" . $food['food_image']);
                }
                $response = [
                    'status' => 200,
                    'message' => 'Makanan Berhasil Dihapus.'
                ];
            } else {
                $response = [
                    'status' => 404,
                    'message' => 'Makanan Tidak Ditemukan.'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }

        return $this->respond($response);
    }
}
