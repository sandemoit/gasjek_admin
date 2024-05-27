<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DriverModel;
use CodeIgniter\API\ResponseTrait;

class DriverApi extends ResourceController
{
    use ResponseTrait;
    protected $modelName = 'App\Models\DriverModel';
    // protected $format = 'json';

    public function index()
    {
        // Mengambil parameter dari request
        $token = $this->request->getVar('token');
        $police_number = $this->request->getVar('police_number');
        $email_driver = $this->request->getVar('email_driver');
        $is_status = $this->request->getVar('is_status');

        // Melakukan pengecekan parameter untuk menentukan query yang akan dieksekusi
        if ($token != null) {
            // Jika token tidak null, ambil data driver berdasarkan token
            $model[] = $this->model->getDriver($token);
        } elseif ($email_driver != null) {
            // Jika email_driver tidak null, ambil data driver berdasarkan email
            $model[] = $this->model->getDriverEmail($email_driver);
        } elseif ($police_number != null && $police_number != "All") {
            // Jika police_number tidak null dan bukan "All", ambil data driver berdasarkan nomor polisi
            $model[] = $this->model->getPoliceNumber($police_number);
        } elseif ($is_status != null) {
            // Jika is_status tidak null, ambil data driver berdasarkan status
            $model[] = $this->model->getStatus($is_status);
        } else {
            // Jika tidak ada parameter tertentu yang diberikan, ambil semua data driver
            $model[] = $this->model->getDriver();
        }

        // Menyiapkan respons
        $response = [
            'status' => 1,
            'message' => 'success',
            'dataDrivers' => $model
        ];

        return $this->respond($response);
    }

    public function create()
    {
        // Menggunakan model untuk interaksi dengan database
        $model = new DriverModel();

        // Mendapatkan data dari request yang lain
        $type_vehicle = $this->request->getPost('type_vehicle');
        $police_number = $this->request->getPost('police_number');
        $vehicle_name = $this->request->getPost('vehicle_name');
        $fcm_token = $this->request->getPost('fcm_token');
        $location = $this->request->getPost('location');
        $user_image = $this->request->getPost('image_rider');
        $user_name = $this->request->getPost('username_rider');
        $email = $this->request->getPost('email_rider');
        $phone_rider = $this->request->getPost('phone_rider');
        $password_hash = $this->request->getPost('password_rider');

        // Check if email or number already exists
        $check_email = $model->where('email_rider', $email)->countAllResults();
        $check_number = $model->where('phone_rider', $phone_rider)->countAllResults();

        if ($check_email > 0) {
            return $this->respondCreated([
                'status' => 400,
                'message' => 'Email sudah digunakan rider lain.'
            ]);
        }

        if ($check_number > 0) {
            return $this->respondCreated([
                'status' => 401,
                'message' => 'Nomor HP sudah digunakan rider lain.'
            ]);
        }

        // Generate nama file gambar dengan format [nama_user - nomor_acak].[ekstensi]
        $random_number = rand(1, 9999);
        $image_title = "$user_name - $random_number.jpg";
        $path = "assets/drivers/$image_title";

        // Data untuk disimpan ke database
        $data = [
            'username_rider' => $user_name,
            'email_rider' => $email,
            'phone_rider' => $phone_rider,
            'password_rider' => password_hash($password_hash, PASSWORD_DEFAULT),
            'image_rider' => $image_title,
            'type_vehicle' => $type_vehicle,
            'police_number' => $police_number,
            'fcm_token' => $fcm_token,
            'location' => $location,
            'vehicle_name' => $vehicle_name,
            'is_active' => 'false',
            'is_status' => 'waiting'
        ];

        // Simpan data ke database
        $model->insert($data);

        // Simpan gambar ke server
        $decoded = base64_decode($user_image);
        file_put_contents($path, $decoded);

        // Ambil ID driver yang baru saja dibuat
        $driver_id = $model->insertID();

        // Menyiapkan respons
        $response = [
            'status'   => 200,
            'token'    => $driver_id, // Menggunakan ID driver sebagai token
            'message' => 'Data berhasil dibuat.'
        ];

        // Mengembalikan respons HTTP 201 (Created) beserta data yang baru saja dibuat
        return $this->respondCreated($response);
    }

    public function login()
    {
        $db = \Config\Database::connect();

        $email = $this->request->getPost('driver_email');
        $password = $this->request->getPost('driver_password');

        // Validasi input
        if (empty($email) || empty($password)) {
            $response['status'] = 0;
            $response['message'] = "Email dan password harus diisi";
            return $this->respond($response);
        }

        // Query dengan parameterized query
        $query_user = $db->query("SELECT * FROM tb_driver where email_rider = ?", [$email]);
        $result = $query_user->getRow();

        $user = $query_user->getNumRows();

        if ($user > 0) {
            // User ditemukan
            $response['status'] = 1;
            $response['message'] = "User Tersedia";

            // Mengambil ID pengguna dan hash password
            $user_id = $result->id_driver;
            $password_hash = $result->password_rider;

            // Verifikasi password
            if (password_verify($password, $password_hash)) {
                // Update token FCM
                $fcm_token = $this->request->getPost('fcm_token');
                $query_user_update = $db->query("UPDATE tb_driver SET fcm_token = ? where email_rider = ?", [$fcm_token, $email]);

                // Mengembalikan respons dengan token dan status
                $response['token'] = $user_id;
                $response['is_status'] = $result->is_status;
            } else {
                // Password salah
                $response['status'] = 4;
                $response['message'] = "Password Anda Salah";
            }
        } else {
            // User tidak ditemukan
            $response['status'] = 3;
            $response['message'] = "Email dan Password Anda Salah";
        }
        return $this->respond($response);
    }

    public function update_location_driver()
    {
        // Mendapatkan data dari request
        $rider_latitude = $this->request->getPost('rider_latitude');
        $rider_longitude = $this->request->getPost('rider_longitude');
        $token = $this->request->getPost('token');

        // Melakukan pembaruan lokasi driver dalam database
        $affectedRows = $this->model->updateLocation($token, $rider_latitude, $rider_longitude);

        if ($affectedRows > 0) {
            // Jika lokasi berhasil diperbarui, siapkan respons sukses
            $response = [
                'status' => 200,
                'message' => "Data Anda telah diperbarui"
            ];
        } else {
            // Jika tidak ada baris yang terpengaruh, beri respons gagal
            $response = [
                'status' => 400,
                'message' => "Gagal memperbarui data"
            ];
        }
        return $this->respond($response);
    }

    public function update_city_name()
    {
        // Ambil data yang dikirimkan dalam permintaan
        $location = $this->request->getVar('location');
        $token = $this->request->getVar('token');

        // Inisialisasi database
        $db = \Config\Database::connect();

        // Perbarui record di database
        $db->query("UPDATE tb_driver SET location = '$location' WHERE id_driver = '$token'");

        // Periksa apakah perbaruan berhasil
        if ($db->affectedRows() > 0) {
            $response = [
                'status' => 200,
                'message' => 'Data berhasil diperbarui'
            ];
            return $this->respond($response);
        } else {
            return $this->fail('Gagal memperbarui data', 400);
        }
    }


    public function update_status()
    {
        $db = \Config\Database::connect();

        $token = $this->request->getPost('token');
        $is_status = $this->request->getPost('is_status');

        // Menggunakan parameterized query untuk mencegah serangan injeksi SQL
        $query = "UPDATE tb_driver SET is_active = ? WHERE id_driver = ?";
        $affectedRows = $db->query($query, [$is_status, $token]);

        if ($affectedRows > 0) {
            // Jika status berhasil diperbarui, siapkan respons sukses
            $response = [
                'status' => 200,
                'message' => "Data Anda telah diperbarui"
            ];
        } else {
            // Jika tidak ada baris yang terpengaruh, beri respons gagal dengan status 400 Bad Request
            $response = [
                'status' => 400,
                'message' => "Gagal memperbarui data"
            ];
        }
        return $this->respond($response);
    }

    public function update_rating()
    {
        $db = \Config\Database::connect();

        $driver_police_number = $this->request->getPost('driver_police_number');
        $rating_driver = $this->request->getPost('rating_driver');

        // Menggunakan parameterized query untuk mencegah serangan injeksi SQL
        $query = "UPDATE tb_driver SET rating_driver = ? WHERE police_number = ?";
        $affectedRows = $db->query($query, [$rating_driver, $driver_police_number]);

        if ($affectedRows > 0) {
            // Jika peringkat berhasil diperbarui, siapkan respons sukses
            $response = [
                'status' => 200,
                'message' => "Rating berhasil diperbarui"
            ];
        } else {
            // Jika tidak ada baris yang terpengaruh, beri respons gagal dengan status 400 Bad Request
            $response = [
                'status' => 400,
                'message' => "Gagal memperbarui rating"
            ];
        }

        return $this->respond($response);
    }

    public function update_fcm_token()
    {
        $db = \Config\Database::connect();

        $token = $this->request->getPost('token');
        $fcm_token = $this->request->getPost('fcm_token');

        $affectedRows = $db->query("UPDATE tb_driver SET fcm_token = '$fcm_token' WHERE id_driver = '$token'");

        if ($affectedRows > 0) {
            $response['status']     = 1;
            $response['message']    = "Update...";
        } else {
            $response['status']     = 400;
            $response['message']    = "Gagal...";
        }

        return $this->respond($response);
    }


    public function update_balance_driver()
    {
        $db = \Config\Database::connect();

        $token = $this->request->getPost('token');
        $balance = $this->request->getPost('balance');

        $db->query("UPDATE tb_driver SET balance_rider = '$balance' WHERE id_driver = '$token'");

        $response['status']     = 1;
        $response['message']    = "Update...";

        return $this->respond($response);
    }
}