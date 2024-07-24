<?php

namespace App\Controllers;

use App\Models\DriverModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\MitraModel;
use App\Models\RestaurantModel;
use App\Models\FoodModel;
use App\Models\UserModelApi;
use CodeIgniter\RESTful\ResourceController;

class RestaurantApi extends ResourceController
{
    use ResponseTrait;

    protected $mitraModel;
    protected $restaurantModel;
    protected $foodModel;
    protected $format = 'json';
    protected $driverModel;
    protected $UserModelApi;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->restaurantModel = new RestaurantModel();
        $this->foodModel = new FoodModel();
        $this->driverModel = new DriverModel();
        $this->UserModelApi = new UserModelApi();
    }

    public function index()
    {
        $id_restaurant = $this->request->getVar('id_restaurant');
        $email_mitra = $this->request->getVar('email_mitra');
        $search = $this->request->getVar('search');
        $filter = $this->request->getVar('filter');
        $type = $this->request->getVar('type');
        $latitude = $this->request->getVar('latitude');
        $longitude = $this->request->getVar('longitude');

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
                $restaurant = $this->restaurantModel->select('user_email_mitra')->where('id_restaurant', $id_restaurant)->first();

                if ($restaurant) {
                    $email_mitra = $restaurant['user_email_mitra'];

                    // get fcm_token mitra berdasarkan email_mitra
                    $mitra = $this->mitraModel->select('fcm_token, user_phone_mitra')->where('user_email_mitra', $email_mitra)->get()->getRowArray();
                    if ($mitra) {
                        $fcm_token = $mitra['fcm_token'];
                        $user_phone_mitra = $mitra['user_phone_mitra'];
                    } else {
                        // Handle jika mitra tidak ditemukan
                        $fcm_token = null; // Atau sesuaikan dengan penanganan yang sesuai
                    }

                    // Ambil data restaurant lengkap dengan fcm_token
                    $result = $this->restaurantModel->getRestaurant($id_restaurant, $fcm_token, $user_phone_mitra);

                    if ($result && $result['is_active'] === 'true') {
                        // Jika data restaurant ditemukan dan is_active true, ambil data makanan terkait
                        $model[] = $this->prepareRestaurantData($result);
                    }
                }
            } else if ($search != null && $filter != null) {
                // Jika terdapat pencarian dan filter, lakukan pencarian dan filter
                $result = $this->restaurantModel->searchAndfilterRestaurant($search, $filter);

                // Tambahkan filter is_active
                foreach ($result as $restaurant) {
                    if ($restaurant['is_active'] === 'true') {
                        $model[] = $this->prepareRestaurantData($restaurant);
                    }
                }
            } else if ($search != null) {
                // Jika terdapat pencarian dan filter, lakukan pencarian dan filter
                $result = $this->restaurantModel->searchRestaurant($search);

                // Tambahkan filter is_active
                foreach ($result as $restaurant) {
                    if ($restaurant['is_active'] === 'true') {
                        $model[] = $this->prepareRestaurantData($restaurant);
                    }
                }
            } else if ($filter != null) {
                // Jika hanya terdapat filter, lakukan filter
                $result = $this->restaurantModel->filterRestaurant(null, $filter);

                // Tambahkan filter is_active
                foreach ($result as $restaurant) {
                    if ($restaurant['is_active'] === 'true') {
                        $model[] = $this->prepareRestaurantData($restaurant);
                    }
                }
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
                foreach ($restaurants as $item) {
                    if ($item['is_active'] === 'true') {
                        $model[] = $this->prepareRestaurantData($item);
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
        $open_time = (int) $result['open_restaurant'];
        $close_time = (int) $result['close_restaurant'];
        $current_day = date('N');
        $id_harikerja = json_decode($result['id_harikerja'], true);

        // check status nya open atau tidak
        if ($result['is_open'] === 'true') {
            // Check if current day is in id_harikerja
            if (in_array($current_day, $id_harikerja)) {
                if ($open_time <= $current_time && $current_time < $close_time) {
                    $is_open = true;
                }
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
            'is_active' => $result['is_active'],
            'user_phone_mitra' => isset($result['user_phone_mitra']) ? $result['user_phone_mitra'] : null,
            'fcm_token' => isset($result['fcm_token']) ? $result['fcm_token'] : null
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
        $restaurantModel = new RestaurantModel();
        $mitraModel = new MitraModel();
        $userModelApi = new UserModelApi();
        $driverModel = new DriverModel();

        // Mengambil data dari request
        $id_mitra = $this->request->getVar('id_mitra');
        $id_restaurant = $this->request->getVar('id_restaurant');
        $email_mitra = $this->request->getVar('email_mitra');
        $phone_mitra = $this->request->getVar('phone_mitra');

        // Ambil data mitra saat ini dari database
        $currentMitra = $mitraModel->find($id_mitra);
        if (!$currentMitra) {
            return $this->respond([
                'status' => 404,
                'message' => 'Mitra tidak ditemukan.'
            ]);
        }

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

        // Memeriksa apakah email sudah digunakan oleh pengguna lain (kecuali dirinya sendiri)
        if ($email_mitra != $currentMitra['user_email_mitra']) {
            $existingEmailUser = $userModelApi->where('email_pengguna', $email_mitra)->first();
            $existingEmailDriver = $driverModel->where('email_rider', $email_mitra)->first();
            $existingEmailMitra = $mitraModel->where('user_email_mitra', $email_mitra)->where('id_mitra !=', $id_mitra)->first();
            if ($existingEmailMitra || $existingEmailUser || $existingEmailDriver) {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Email sudah digunakan pengguna lain.'
                ]);
            }
        }

        // Memeriksa apakah nomor HP sudah digunakan oleh pengguna lain (kecuali dirinya sendiri)
        if ($phone_mitra != $currentMitra['user_phone_mitra']) {
            $existingNumberUser = $userModelApi->where('nomor_pengguna', $phone_mitra)->first();
            $existingNumberDriver = $driverModel->where('phone_rider', $phone_mitra)->first();
            $existingNumberMitra = $mitraModel->where('user_phone_mitra', $phone_mitra)->where('id_mitra !=', $id_mitra)->first();
            if ($existingNumberMitra || $existingNumberUser || $existingNumberDriver) {
                return $this->respond([
                    'status' => 401,
                    'message' => 'Nomor HP sudah digunakan pengguna lain.'
                ]);
            }
        }

        // Periksa apakah gambar pengguna diperbarui
        $user_image = $this->request->getPost('restaurant_image');
        if ($user_image !== "no" && $user_image !== null) {
            // Simpan gambar baru
            $date = date('Y-m-d-H:i');
            $code = random_int(100000, 999999);
            $image_title = "$code - $date.jpg";
            $path = "assets/restaurants/$image_title";

            if (!file_put_contents(
                $path,
                base64_decode($user_image)
            )) {
                return $this->fail('Gagal menyimpan gambar pengguna.', 500);
            }

            // Tambahkan path gambar ke data yang akan diupdate
            $data['restaurant_image'] = $image_title;
        }

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
        $driverModel = new DriverModel();
        $userModelApi = new UserModelApi();

        // Mengambil data dari request
        $mitra = $this->request->getVar('mitra');
        $user_phone_mitra = $this->request->getVar('user_phone_mitra');
        $user_email_mitra = $this->request->getVar('user_email_mitra');
        $user_password_mitra = $this->request->getVar('user_password_mitra');
        $fcm_token = $this->request->getVar('fcm_token');
        $restaurant_name = $this->request->getVar('restaurant_name');
        $restaurant_location = $this->request->getVar('restaurant_location');
        $latitude_restaurant = $this->request->getVar('latitude_restaurant');
        $longitude_restaurant = $this->request->getVar('longitude_restaurant');
        $open_restaurant = $this->request->getVar('open_restaurant');
        $close_restaurant = $this->request->getVar('close_restaurant');
        $workDaysJson = $this->request->getVar('work_days');
        $restaurant_image = $this->request->getVar('restaurant_image');

        // Konversi base64 ke gambar dan simpan
        if ($restaurant_image) {
            $imageName = uniqid() . '.jpg';
            $filePath = 'assets/restaurants/' . $imageName;

            // Decode base64
            $image_data = base64_decode($restaurant_image);
            file_put_contents($filePath, $image_data);

            // Kompresi gambar
            $this->compress_image($filePath);
        } else {
            return $this->respond([
                'status' => 400,
                'message' => 'Gambar tidak valid atau gagal diunggah.'
            ]);
        }

        if ($mitra == "mitra") {
            // Hash password mitra sebelum disimpan ke database
            $password_hash = password_hash($user_password_mitra, PASSWORD_DEFAULT);

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
                'restaurant_image' => $imageName,
                'is_open' => 'false',
                'id_harikerja' => $workDaysJson,
            ];

            // Memeriksa apakah email sudah digunakan oleh mitra lain
            if (
                $userModelApi->where('email_pengguna', $user_email_mitra)->first() ||
                $driverModel->where('email_rider', $user_email_mitra)->first() ||
                $mitraModel->where('user_email_mitra', $user_email_mitra)->first()
            ) {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Email sudah digunakan pengguna lain.'
                ]);
            }

            if (
                $userModelApi->where('nomor_pengguna', $user_phone_mitra)->first() ||
                $driverModel->where('phone_rider', $user_phone_mitra)->first() ||
                $mitraModel->where('user_phone_mitra', $user_phone_mitra)->first()
            ) {
                return $this->respondCreated([
                    'status' => 401,
                    'message' => 'Nomor HP sudah digunakan pengguna lain.'
                ]);
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

    private function compress_image($filePath)
    {
        // Menggunakan library Image Manipulation
        $image = \Config\Services::image()
            ->withFile($filePath)
            ->save($filePath, 10); // Nilai 10 adalah kualitas kompresi, semakin rendah semakin kecil ukuran file

        return $filePath;
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
