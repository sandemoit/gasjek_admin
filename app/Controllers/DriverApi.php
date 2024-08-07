<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DriverModel;
use App\Models\MitraModel;
use App\Models\UserModelApi;
use CodeIgniter\API\ResponseTrait;
use Exception;

class DriverApi extends ResourceController
{
    use ResponseTrait;
    protected $driverModel;
    protected $userModelApil;
    protected $mitraModel;
    // protected $format = 'json';

    public function __construct()
    {
        $this->driverModel = new DriverModel();
        $this->mitraModel = new MitraModel();
        $this->userModelApil = new UserModelApi();
    }

    public function index()
    {
        // Mengambil parameter dari request
        $token = $this->request->getVar('token');
        $police_number = $this->request->getVar('police_number');
        $email_driver = $this->request->getVar('email_driver');
        $is_status = $this->request->getVar('is_status');
        $is_limited = $this->request->getVar('is_limited');

        // Melakukan pengecekan parameter untuk menentukan query yang akan dieksekusi
        if ($token != null) {
            // Jika token tidak null, ambil data driver berdasarkan token
            $model[] = $this->driverModel->getDriver($token);
        } elseif ($email_driver != null) {
            // Jika email_driver tidak null, ambil data driver berdasarkan email
            $model[] = $this->driverModel->getDriverEmail($email_driver);
        } elseif ($police_number != null && $police_number != "All") {
            // Jika police_number tidak null dan bukan "All", ambil data driver berdasarkan nomor polisi
            $model[] = $this->driverModel->getPoliceNumber($police_number);
        } elseif ($is_status != null) {
            // Ambil data driver berdasarkan status jika is_status diberikan
            $model[] = $this->driverModel->getStatus($is_status);
        } elseif ($is_limited != null) {
            // Jika is_limited diberikan, ambil data driver berdasarkan is_limited
            $model[] = $this->driverModel->checkLimited($is_limited);
        } else {
            // Jika tidak ada parameter tertentu yang diberikan, ambil semua data driver
            $model[] = $this->driverModel->getDriver();
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
        $userModelApi = new UserModelApi();
        $mitraModel = new MitraModel();

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
        $check_email_user = $userModelApi->where('email_pengguna', $email)->countAllResults();
        $check_email_mitra = $mitraModel->where('user_email_mitra', $email)->countAllResults();
        $check_email_driver = $model->where('email_rider', $email)->countAllResults();
        if ($check_email_user > 0 || $check_email_driver > 0 || $check_email_mitra > 0) {
            return $this->respondCreated([
                'status' => 400,
                'message' => 'Email sudah digunakan pengguna lain.'
            ]);
        }

        $check_number_driver = $model->where('phone_rider', $phone_rider)->countAllResults();
        $check_number_user = $userModelApi->where('nomor_pengguna', $phone_rider)->countAllResults();
        $check_number_mitra = $mitraModel->where('user_phone_mitra', $phone_rider)->countAllResults();
        if ($check_number_user > 0 || $check_number_driver > 0 || $check_number_mitra > 0) {
            return $this->respondCreated([
                'status' => 401,
                'message' => 'Nomor HP sudah digunakan pengguna lain.'
            ]);
        }

        // Generate nama file gambar dengan format [nama_user - nomor_acak].[ekstensi]
        $nama_file = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($user_name));
        $random_number = rand(1000, 9999);
        $image_title = "$nama_file - $random_number.jpg";
        $path = "assets/drivers/$image_title";

        // Data untuk disimpan ke database
        $data = [
            'username_rider' => $user_name,
            'email_rider' => $email,
            'date_register' => date('Y-m-d H:i:s'),
            'balance_rider' => 0,
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
        $email = $this->request->getPost('driver_email');
        $password = $this->request->getPost('driver_password');

        // Validasi input
        if (empty($email) || empty($password)) {
            return $this->respond([
                'status' => 0,
                'message' => "Email dan password harus diisi"
            ]);
        }

        // Mengambil data driver berdasarkan email
        $driver = $this->driverModel->where('email_rider', $email)->first();

        if ($driver) {
            // Verifikasi password
            if (password_verify($password, $driver['password_rider'])) {
                // Update token FCM
                $fcm_token = $this->request->getPost('fcm_token');
                $this->driverModel->update($driver['id_driver'], ['fcm_token' => $fcm_token]);

                // Mengembalikan respons dengan token dan status
                return $this->respond([
                    'status' => 1,
                    'message' => "User Tersedia",
                    'token' => $driver['id_driver'],
                    'is_status' => $driver['is_status']
                ]);
            } else {
                return $this->respond([
                    'status' => 4,
                    'message' => "Password Anda Salah"
                ]);
            }
        } else {
            return $this->respond([
                'status' => 3,
                'message' => "Email dan Password Anda Salah"
            ]);
        }
    }

    public function update_vehicle()
    {
        try {
            $driverModel = new DriverModel();

            $id_driver = $this->request->getVar('id_user');
            $police_number = $this->request->getVar('police_number');
            $vehicle_name = $this->request->getVar('vehicle_name');

            // Validasi input
            if (empty($police_number) || empty($vehicle_name)) {
                return $this->respond([
                    'status' => 400,
                    'message' => "Police Number dan Vehicle Name harus diisi"
                ]);
            }

            $curentDriver = $this->driverModel->find($id_driver);
            if (!$curentDriver) {
                return $this->respond([
                    'status' => 404,
                    'message'  => 'Driver Tidak Ditemukan'
                ]);
            }

            $driverData = [
                'police_number' => $police_number,
                'vehicle_name' => $vehicle_name,
            ];

            $update = $driverModel->update($id_driver, $driverData);
            if ($update) {
                return $this->respond([
                    'status' => 200,
                    'message' => 'Informasi Kendaraan Berhasil Diubah'
                ]);
            } else {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Informasi Kendaraan Gagal Diubah'
                ]);
            }
        } catch (Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Terjadi Kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_driver()
    {
        try {
            $mitraModel = new MitraModel();
            $userModelApi = new UserModelApi();
            $driverModel = new DriverModel();

            $type = $this->request->getVar('type_update');
            $id_driver = $this->request->getVar('user_id');
            $username_rider = $this->request->getVar('user_name');
            $email_rider = $this->request->getVar('user_email');
            $phone_rider = $this->request->getVar('user_number');

            if ($type == 'common') {
                // Ambil data driver saat ini dari database
                $currentDriver = $driverModel->find($id_driver);
                if (!$currentDriver) {
                    return $this->respond([
                        'status' => 404,
                        'message' => 'Driver tidak ditemukan.'
                    ]);
                }

                // Data untuk update driver
                $driverData = [
                    'username_rider' => $username_rider,
                    'email_rider' => $email_rider,
                    'phone_rider' => $phone_rider
                ];

                // Memeriksa apakah email sudah digunakan oleh pengguna lain (kecuali dirinya sendiri)
                if ($email_rider != $currentDriver['email_rider']) {
                    $existingEmailUser = $userModelApi->where('email_pengguna', $email_rider)->first();
                    $existingEmailDriver = $driverModel->where('email_rider', $email_rider)->where('id_driver !=', $id_driver)->first();
                    $existingEmailMitra = $mitraModel->where('user_email_mitra', $email_rider)->first();
                    if ($existingEmailMitra || $existingEmailUser || $existingEmailDriver) {
                        return $this->respond([
                            'status' => 400,
                            'message' => 'Email sudah digunakan pengguna lain.'
                        ]);
                    }
                }

                // Memeriksa apakah nomor HP sudah digunakan oleh pengguna lain (kecuali dirinya sendiri)
                if ($phone_rider != $currentDriver['phone_rider']) {
                    $existingNumberUser = $userModelApi->where('nomor_pengguna', $phone_rider)->first();
                    $existingNumberDriver = $driverModel->where('phone_rider', $phone_rider)->where('id_driver !=', $id_driver)->first();
                    $existingNumberMitra = $mitraModel->where('user_phone_mitra', $phone_rider)->first();
                    if ($existingNumberMitra || $existingNumberUser || $existingNumberDriver) {
                        return $this->respond([
                            'status' => 401,
                            'message' => 'Nomor HP sudah digunakan pengguna lain.'
                        ]);
                    }
                }

                // Periksa apakah gambar pengguna diperbarui
                $old_image = $this->request->getVar('old_image');
                $new_image = $this->request->getVar('user_image');
                if ($new_image !== "no" && $new_image !== null) {
                    // Simpan gambar baru
                    $date = date('Y-m-d-H:i');
                    $image_title = "$username_rider-$date.jpg";
                    $path = "assets/drivers/$image_title";

                    if (!file_put_contents($path, base64_decode($new_image))) {
                        return $this->fail('Gagal menyimpan gambar pengguna.', 500);
                    }

                    // Hapus gambar lama jika ada
                    // if ($old_image !== null) {
                    //     unlink("assets/drivers/$old_image");
                    // }

                    // Tambahkan path gambar ke data yang akan diupdate
                    $driverData['image_rider'] = $image_title;
                }

                // Memperbarui data driver
                $update = $driverModel->update($id_driver, $driverData);

                if ($update) {
                    $response = [
                        'status' => 200,
                        'message' => "Informasi Pengguna Berhasil Diubah"
                    ];
                } else {
                    $response = [
                        'status' => 400,
                        'message' => "Informasi Pengguna Gagal Diubah"
                    ];
                }
                return $this->respond($response);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 500,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ];
            return $this->respond($response);
        }
    }

    public function update_location_driver()
    {
        // Mendapatkan data dari request
        $rider_latitude = $this->request->getPost('rider_latitude');
        $rider_longitude = $this->request->getPost('rider_longitude');
        $token = $this->request->getPost('token');

        // Melakukan pembaruan lokasi driver dalam database
        $affectedRows = $this->driverModel->updateLocation($token, $rider_latitude, $rider_longitude);

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
        try {
            $driverModel = new DriverModel();

            // Ambil data yang dikirimkan dalam permintaan
            $location = $this->request->getVar('location');
            $token = $this->request->getVar('token');

            // Validasi data yang diterima
            if (!$location || !$token) {
                return $this->fail('Data tidak lengkap', 400);
            }

            // Perbarui data driver di database
            $update = $driverModel->update($token, ['location' => $location]);

            if ($update) {
                $response = [
                    'status' => 200,
                    'message' => 'Data berhasil diperbarui'
                ];
            } else {
                $response = [
                    'status' => 400,
                    'message' => 'Gagal memperbarui data'
                ];
            }

            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update_status()
    {
        try {
            $driverModel = new DriverModel();

            $id_driver = $this->request->getPost('token');
            $is_status = $this->request->getPost('is_status');

            // Menggunakan parameterized query untuk mencegah serangan injeksi SQL
            $update = $driverModel->update($id_driver, ['is_active' => $is_status, 'is_limited' => 'true']);

            if ($update) {
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
        } catch (\Exception $e) {
            $response = [
                'status' => 500,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ];
        }

        return $this->respond($response);
    }

    public function update_rating()
    {
        try {
            $driverModel = new DriverModel();

            $driver_police_number = $this->request->getPost('driver_police_number');
            $rating_driver = $this->request->getPost('rating_driver');

            // Menggunakan parameterized query untuk mencegah serangan injeksi SQL
            $update = $driverModel->update($driver_police_number, ['rating_driver' => $rating_driver]);

            if ($update) {
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
        } catch (\Exception $e) {
            $response = [
                'status' => 500,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
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
        $driverModel = new DriverModel();

        $token = $this->request->getPost('token');
        $balance = $this->request->getPost('balance');

        $driverModel->where('id_driver', $token)
            ->set(['balance_rider' => $balance])
            ->update();

        $response['status']     = 1;
        $response['message']    = "Saldo di Update";

        return $this->respond($response);
    }
}
