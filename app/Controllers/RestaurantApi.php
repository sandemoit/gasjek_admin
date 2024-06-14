<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\MitraModel;
use App\Models\RestaurantModel;
use App\Models\FoodModel;

use CodeIgniter\RESTful\ResourceController;

class RestaurantApi extends ResourceController
{
    use ResponseTrait;

    protected $mitraModel;
    protected $restaurantModel;
    protected $foodModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->restaurantModel = new RestaurantModel();
        $this->foodModel = new FoodModel();
    }

    public function index()
    {
        $id_restaurant = $this->request->getVar('id_restaurant');
        $search = $this->request->getVar('search');
        $filter = $this->request->getVar('filter');
        $type = $this->request->getVar('type');
        $latitude = $this->request->getVar('latitude');
        $longitude = $this->request->getVar('longitude');
        $email_mitra = $this->request->getVar('email_mitra');

        // Inisialisasi variabel model untuk menyimpan data restaurant
        $model = [];

        if ($type == "mitra") {
            // Jika type adalah "mitra", ambil data restaurant berdasarkan email mitra
            $result = $this->restaurantModel->where('user_email_mitra', $email_mitra)->first();

            if ($result) {
                // Jika data restaurant ditemukan, ambil data makanan terkait
                $model[] = $this->prepareRestaurantData($result);
            }
        } else {
            if ($id_restaurant != null) {
                // Jika id_restaurant tidak null, ambil data restaurant berdasarkan id_restaurant
                $result = $this->restaurantModel->find($id_restaurant);

                if ($result && $result['is_active'] === 'true') {
                    // Jika data restaurant ditemukan dan is_active true, ambil data makanan terkait
                    $model[] = $this->prepareRestaurantData($result);
                }
            } else if ($search != null && $filter != null) {
                // Jika terdapat pencarian dan filter, lakukan pencarian dan filter
                $model = $this->restaurantModel->filterRestaurant($search, $filter);
            } else if ($filter != null) {
                // Jika hanya terdapat filter, lakukan filter
                $model = $this->restaurantModel->filterRestaurant(null, $filter);
            } else if ($latitude != null && $longitude != null) {
                // Jika latitude dan longitude diberikan, cari restoran terdekat
                $nearestRestaurants = $this->restaurantModel->getNearestRestaurants($latitude, $longitude);
                foreach ($nearestRestaurants as $restaurant) {
                    // Tambahkan filter is_active
                    if ($restaurant['is_active'] === 'true') {
                        $model[] = $this->prepareRestaurantData($restaurant);
                    }
                }
            } else {
                // Jika tidak ada parameter tertentu yang diberikan, ambil semua data restaurant
                // dan tambahkan filter is_active
                $restaurants = $this->restaurantModel->findAll();
                foreach ($restaurants as $restaurant) {
                    if ($restaurant['is_active'] === 'true') {
                        $model[] = $this->prepareRestaurantData($restaurant);
                    }
                }
            }
        }

        // Menyiapkan respons
        $response = [
            'status' => 200,
            'message' => 'success',
            'dataRestaurant' => $model
        ];

        // Mengembalikan respons
        return $this->respond($response);
    }

    private function prepareRestaurantData($result)
    {
        // Menentukan apakah restoran buka atau tutup berdasarkan is_open, open_restaurant, dan close_restaurant
        $is_open = false;
        $current_time = date('H');

        if ($result['is_open'] === 'true') {
            if ($result['open_restaurant'] <= $current_time && $current_time < $result['close_restaurant']) {
                $is_open = true;
            }
        }

        // Menyiapkan data restoran
        $restaurantData = [
            'id_restaurant' => $result['id_restaurant'],
            'restaurant_name' => $result['restaurant_name'],
            'restaurant_image' => $result['restaurant_image'],
            'restaurant_location' => $result['restaurant_location'],
            'restaurant_rating' => $result['restaurant_rating'],
            'longitude_restaurant' => $result['longitude_restaurant'],
            'latitude_restaurant' => $result['latitude_restaurant'],
            'open_restaurant' => $result['open_restaurant'],
            'close_restaurant' => $result['close_restaurant'],
            'price_min' => $this->foodModel->getMinFoodPrice($result['id_restaurant']),
            'price_max' => $this->foodModel->getMaxFoodPrice($result['id_restaurant']),
            'is_open' => $is_open,
            'is_active' => $result['is_active']
        ];

        return $restaurantData;
    }

    // mitra
    public function login()
    {
        $user_email = $this->request->getVar('user_email');
        $user_password = $this->request->getVar('user_password');
        $fcm_token = $this->request->getVar('fcm_token');

        // Membuat instance model Mitra
        $mitraModel = new MitraModel();

        // Mencari mitra berdasarkan email
        $mitra = $mitraModel->where('user_email_mitra', $user_email)->first();

        if ($mitra) {
            // Memeriksa kecocokan password
            if (password_verify($user_password, $mitra['user_password_mitra'])) {
                // Update fcm_token untuk mitra
                $mitraModel->update($mitra['id_mitra'], ['fcm_token' => $fcm_token]);

                // Menyiapkan respons jika login berhasil
                $response = [
                    'status' => 200,
                    'message' => "Login berhasil",
                    'token' => $mitra['id_mitra']
                ];
            } else {
                // Menyiapkan respons jika password salah
                $response = [
                    'status' => 400,
                    'message' => "Password Anda Salah"
                ];
            }
        } else {
            // Menyiapkan respons jika email tidak ditemukan
            $response = [
                'status' => 401,
                'message' => "Email tidak ditemukan"
            ];
        }

        // Mengembalikan respons
        return $this->respond($response);
    }

    public function fcm_token_mitra()
    {
        $id_mitra = $this->request->getVar('token');
        $fcm_token = $this->request->getVar('fcm_token');

        $mitraUpdate = [
            'id_mitra' => $id_mitra,
            'fcm_token' => $fcm_token
        ];
        $mitraModel = new MitraModel();
        $mitraModel->save($mitraUpdate);

        // Menyiapkan respons
        $response = [
            'status'   => 200,
            'message' => "Update berhasil"
        ];

        return $this->respond($response);
    }

    public function edit_mitra()
    {
        // Menginisialisasi model
        $mitraModel = new MitraModel();
        $restaurantModel = new RestaurantModel();

        // Mengambil data dari request
        $id_mitra = $this->request->getVar('id_mitra');
        $id_restaurant = $this->request->getVar('id_restaurant');
        $email_mitra = $this->request->getVar('email_mitra');
        $phone_mitra = $this->request->getVar('phone_mitra');

        // Data untuk update mitra
        $mitraData = [
            'id_mitra' => $id_mitra,
            'user_email_mitra' => $email_mitra,
            'user_phone_mitra' => $phone_mitra
        ];

        // Data untuk update restaurant
        $restaurantData = [
            'id_restaurant' => $id_restaurant,
            'user_email_mitra' => $email_mitra
        ];

        // Menyimpan data mitra dan restaurant
        $mitraModel->save($mitraData);
        $restaurantModel->save($restaurantData);

        // Menyiapkan respons
        $response = [
            'status'   => 200,
            'message' => "Data Berhasil Diubah"
        ];

        return $this->respond($response);
    }

    public function mitra()
    {
        $mitraModel = new MitraModel();

        $id_mitra = $this->request->getVar('id_mitra');

        if ($id_mitra != null) {
            // Jika ID mitra diberikan, cari mitra berdasarkan ID
            $mitraData = $mitraModel->find($id_mitra);

            if ($mitraData != null) {
                // Jika mitra ditemukan, kembalikan data mitra dalam array
                $response = [
                    'status'   => 200,
                    'message'  => 'success',
                    'dataMitra' => [$mitraData] // Mengubah menjadi array dengan satu elemen
                ];
            } else {
                // Jika mitra tidak ditemukan
                $response = [
                    'status'   => 404,
                    'message'  => 'Mitra not found'
                ];
            }
        } else {
            // Jika tidak ada ID mitra yang diberikan, kembalikan semua data mitra
            $allMitraData = $mitraModel->findAll();
            $response = [
                'status'   => 200,
                'message'  => 'success',
                'dataMitra' => [$allMitraData]
            ];
        }

        return $this->respond($response);
    }

    public function edit_restaurant()
    {
        $restaurantModel = new RestaurantModel();

        // Mengambil data dari request
        $id_restaurant = $this->request->getVar('id_restaurant');
        $restaurant_name = $this->request->getVar('restaurant_name');
        $restaurant_location = $this->request->getVar('restaurant_location');
        $open_restaurant = $this->request->getVar('open_restaurant');
        $close_restaurant = $this->request->getVar('close_restaurant');

        // Data untuk update restaurant
        $dataRestaurant = [
            'id_restaurant' => $id_restaurant,
            'restaurant_name' => $restaurant_name,
            'restaurant_location' => $restaurant_location,
            'open_restaurant' => $open_restaurant,
            'close_restaurant' => $close_restaurant,
        ];

        // Menyimpan data restaurant
        $restaurantModel->save($dataRestaurant);

        // Menyiapkan respons
        $response = [
            'status'   => 200,
            'message' => "Data Berhasil Diubah"
        ];

        return $this->respond($response);
    }

    public function create_mitra()
    {
        // Menggunakan model untuk interaksi dengan database
        $restaurantModel = new RestaurantModel();
        $mitraModel = new MitraModel();

        // Mengambil data dari request
        $mitra = $this->request->getVar('mitra');
        $user_phone_mitra = $this->request->getVar('user_phone_mitra');
        $user_email_mitra = $this->request->getVar('user_email_mitra');
        $user_password_mitra = $this->request->getVar('user_password_mitra');
        $fcm_token = $this->request->getVar('fcm_token');
        $restaurant_name = $this->request->getVar('restaurant_name');
        $restaurant_image = $this->request->getVar('restaurant_image');
        $restaurant_location = $this->request->getVar('restaurant_location');
        $latitude_restaurant = $this->request->getVar('latitude_restaurant');
        $longitude_restaurant = $this->request->getVar('longitude_restaurant');
        $open_restaurant = $this->request->getVar('open_restaurant');
        $close_restaurant = $this->request->getVar('close_restaurant');
        $workDaysJson = $this->request->getVar('work_days');
        // $workDays = json_encode($workDaysJson, true);

        // Generate nama file gambar dengan format [nama_restaurant - nomor_acak].[ekstensi]
        $tanggal = date('Y-m-d-H:i');
        $image_title = "$restaurant_name - $tanggal.jpg";
        $path = "assets/restaurants/$image_title";

        if ($mitra == "mitra") {
            // Hash password mitra sebelum disimpan ke database
            $password_hash  = password_hash($user_password_mitra, PASSWORD_DEFAULT);

            // Data untuk mitra
            $mitra_data = [
                'user_phone_mitra' => $user_phone_mitra,
                'user_email_mitra' => $user_email_mitra,
                'user_password_mitra' => $password_hash,
                'status' => "pending",
                'balance_mitra' => 0,
                'date_register' => date('Y-m-d H:i:s'),
            ];

            // Data untuk restoran
            $restaurant_data = [
                'restaurant_name' => $restaurant_name,
                'restaurant_location' => $restaurant_location,
                'open_restaurant' => $open_restaurant,
                'close_restaurant' => $close_restaurant,
                'latitude_restaurant' => $latitude_restaurant,
                'longitude_restaurant' => $longitude_restaurant,
                'restaurant_rating' => 0,
                'fcm_token' => $fcm_token,
                'user_email_mitra' => $user_email_mitra,
                'restaurant_image' => $image_title,
                'is_open' => 'false',
                'id_harikerja' => $workDaysJson,
            ];

            // Memeriksa apakah email sudah digunakan oleh mitra lain
            $existingEmailMitra = $mitraModel->where('user_email_mitra', $user_email_mitra)->first();
            if ($existingEmailMitra) {
                return $this->fail('Email sudah digunakan oleh mitra lain.', 400);
            }

            // Memeriksa apakah nomor telepon sudah digunakan oleh mitra lain
            $existingPhoneMitra = $mitraModel->where('user_phone_mitra', $user_phone_mitra)->first();
            if ($existingPhoneMitra) {
                return $this->fail('Nomor telepon sudah digunakan oleh mitra lain.', 400);
            }

            // Memasukkan data mitra ke dalam database
            $mitraId = $mitraModel->insert($mitra_data);
            if (!$mitraId) {
                return $this->fail('Gagal membuat akun mitra.', 500);
            }

            // Memasukkan data restoran ke dalam database
            $restaurantId = $restaurantModel->insert($restaurant_data);
            if (!$restaurantId) {
                // Hapus data mitra jika gagal memasukkan data restoran
                $mitraModel->delete($mitraId);
                return $this->fail('Gagal membuat restoran.', 500);
            }

            // Simpan gambar restoran ke server
            $decoded = base64_decode($restaurant_image);
            file_put_contents($path, $decoded);

            // Menyiapkan respons
            $response = [
                'status' => 200,
                'token' => $mitraId,
                'message' => 'Data mitra dan restoran berhasil dibuat.'
            ];

            return $this->respondCreated($response);
        } else {
            $response = [
                'status' => 400,
                'message' => 'Request tidak valid.'
            ];

            return $this->respond($response);
        }
    }

    public function update_password_mitra()
    {
        $mitraModel = new MitraModel();

        // Pembaruan password
        $id_mitra = $this->request->getPost('user_id');
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('new_password');

        // Ambil data pengguna berdasarkan ID
        $mitra = $mitraModel->where('id_mitra', $id_mitra)->first();

        // Periksa keberadaan pengguna
        if (!$mitra) {
            return $this->fail(['status' => 404, 'message' => 'Pengguna tidak ditemukan.']);
        }

        // Periksa kecocokan password lama
        if (!password_verify($old_password, $mitra['user_password_mitra'])) {
            return $this->fail(['status' => 400, 'message' => 'Password lama tidak cocok.']);
        }

        // Hash password baru dan lakukan pembaruan
        $password_new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $mitraModel->update($id_mitra, ['user_password_mitra' => $password_new_hash]);

        return $this->respond(['status' => 200, 'message' => 'Password berhasil diperbarui.']);
    }
}
