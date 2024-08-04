<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\DriverModel;
use App\Models\MitraModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModelApi;
use App\Models\VerifyModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;
use DateTime;

class UserApi extends ResourceController
{

    use ResponseTrait;
    protected $UserModelApi;
    protected $driverModel;
    protected $applicationModel;
    protected $mitraModel;

    public function __construct()
    {
        $this->UserModelApi = new UserModelApi();
        $this->driverModel = new DriverModel();
        $this->applicationModel = new ApplicationModel();
        $this->mitraModel = new MitraModel();
    }

    public function index()
    {

        $token = $this->request->getVar('token');

        $model = [];
        if ($token != null) {
            $model[] = $this->UserModelApi->getUser($token);
        } else {
            $model[] = $this->UserModelApi->findAll();
        }

        $response = [
            'status'   => 1,
            'message'    => 'success',
            'dataUsers' => $model
        ];

        return $this->respond($response);
    }

    public function check_user()
    {
        $userModelApi = new UserModelApi();
        $phone_number = $this->request->getGet('phone_number');

        $user = $userModelApi->select('saldo_pengguna as balance, nama_pengguna as user_name, gambar_pengguna as user_image')
            ->where('nomor_pengguna', $phone_number)
            ->first();

        if ($user) {
            return $this->response->setJSON([
                'status'   => 200,
                'message' => 'User tersedia',
                'dataTransaction' => $user
            ]);
        } else {
            return $this->response->setJSON([
                'status'   => 404,
                'message' => 'User tidak tersedia'
            ]);
        }
    }

    public function login()
    {
        $userModel = new UserModelApi();
        $verif = new VerifyModel();

        // Ambil email dan password dari permintaan
        $email = $this->request->getPost('user_email');
        $password = $this->request->getPost('user_password');

        // Periksa keberadaan pengguna berdasarkan email
        $user = $userModel->where('email_pengguna', $email)->first();

        // Jika pengguna ditemukan
        if ($user) {
            // cek apakah user sudah active atau belum
            if ($user['is_verify'] != 0) {
                // Ambil ID pengguna dan hash password
                $user_id = $user['id_pengguna'];
                $user_verify = $user['is_verify'];
                $password_hash = $user['password_pengguna'];

                // Verifikasi password
                if (password_verify($password, $password_hash)) {
                    // Jika password cocok, perbarui token FCM jika disediakan
                    $fcm_token = $this->request->getPost('fcm_token');
                    if ($fcm_token) {
                        $userModel->update($user_id, ['fcm_token' => $fcm_token]);
                    }
                    $response = [
                        'status' => 1,
                        'message' => 'User Tersedia',
                        'token' => $user_id,
                        'user_verify' => $user_verify
                    ];
                } else {
                    // Jika password tidak cocok
                    $response = [
                        'status' => 4,
                        'message' => 'Password Anda Salah'
                    ];
                }
            } else {
                // Jika pengguna belum terverifikasi
                $otp_code = rand(100000, 999999);
                $verify = [
                    'email' => $email,
                    'token' => $otp_code,
                    'date_created' => date('Y-m-d H:i:s'),
                    'expired' => date('Y-m-d H:i:s', strtotime('+1 month'))
                ];

                if ($user) {
                    $verif->where('email', $email)->delete();
                }

                $verif->insert($verify);
                $this->_sendOTP($otp_code, 'verify', $user['nomor_pengguna']);

                $response = [
                    'status' => 401,
                    'message' => 'Akun anda belum ter verifikasi'
                ];
            }
        } else {
            // Jika pengguna tidak ditemukan
            $response = [
                'status' => 3,
                'message' => 'Email atau Password Anda Salah'
            ];
        }

        return $this->respond($response);
    }

    public function update($id = null)
    {
        $userModel = new UserModelApi();
        $driverModel = new DriverModel();
        $mitraModel = new MitraModel();

        $user_id = $this->request->getPost('user_id');

        // Periksa keberadaan pengguna
        $user = $userModel->find($user_id);
        if (!$user) {
            return $this->fail('Pengguna tidak ditemukan.', 404);
        }

        // Ambil data yang diperbarui dari request
        $data = [
            'nama_pengguna' => $this->request->getPost('user_name'),
            'email_pengguna' => $this->request->getPost('user_email'),
            'nomor_pengguna' => $this->request->getPost('user_number')
        ];

        // Cek apakah email berubah
        if ($data['email_pengguna'] !== $user['email_pengguna']) {
            $check_email_user = $userModel->where('email_pengguna', $data['email_pengguna'])->countAllResults();
            $check_email_driver = $driverModel->where('email_rider', $data['email_pengguna'])->countAllResults();
            $check_email_mitra = $mitraModel->where('user_email_mitra', $data['email_pengguna'])->countAllResults();

            if ($check_email_user > 0 || $check_email_driver > 0 || $check_email_mitra > 0) {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Email sudah digunakan pengguna lain.'
                ]);
            }
        }

        // Cek apakah nomor HP berubah
        if ($data['nomor_pengguna'] !== $user['nomor_pengguna']) {
            $check_number_user = $userModel->where('nomor_pengguna', $data['nomor_pengguna'])->countAllResults();
            $check_number_driver = $driverModel->where('phone_rider', $data['nomor_pengguna'])->countAllResults();
            $check_number_mitra = $mitraModel->where('user_phone_mitra', $data['nomor_pengguna'])->countAllResults();

            if ($check_number_user > 0 || $check_number_driver > 0 || $check_number_mitra > 0) {
                if ($user['is_verify'] == 1) {
                    $message = 'Nomor HP sudah digunakan pengguna lain.';
                } else {
                    $message = 'Nomor HP telah ada silahkan Login';
                }
                return $this->respond([
                    'status' => 401,
                    'message' => $message,
                ]);
            }
        }

        // Periksa apakah gambar pengguna diperbarui
        $user_image = $this->request->getPost('user_image');
        if ($user_image !== "no" && $user_image !== null) {
            // Simpan gambar baru
            $date = date('Y-m-d-H:i');
            $image_title = "{$data['nama_pengguna']} - $date.jpg";
            $path = "assets/profile_photo/$image_title";

            if (!file_put_contents($path, base64_decode($user_image))) {
                return $this->fail('Gagal menyimpan gambar pengguna.', 500);
            }

            // Tambahkan path gambar ke data yang akan diupdate
            $data['gambar_pengguna'] = $image_title;
        }

        // Lakukan pembaruan informasi pengguna
        $userModel->update($user_id, $data);

        return $this->respond(['status' => 200, 'message' => 'Informasi pengguna berhasil diperbarui.']);
    }

    public function create()
    {
        try {
            $model = new UserModelApi();
            $verif = new VerifyModel();
            $driverModel = new DriverModel();
            $mitraModel = new MitraModel();

            $user_name = $this->request->getPost('user_name');
            $user_email = $this->request->getPost('user_email');
            $nomor_pengguna = $this->request->getPost('user_number');
            $fcm_token = $this->request->getPost('fcm_token');
            $user_image = $this->request->getPost('user_image');
            $user_password = $this->request->getPost('user_password');

            // Generate image title
            $image_title = $user_name . '-' . date('Y-m-d-H:i') . ".jpg";
            $path = FCPATH . "assets/profile_photo/$image_title";

            // Hash password
            $password_hash = password_hash($user_password, PASSWORD_DEFAULT);

            // Prepare data
            $data = [
                'nama_pengguna' => $user_name,
                'email_pengguna' => $user_email,
                'nomor_pengguna' => $nomor_pengguna,
                'password_pengguna' => $password_hash,
                'gambar_pengguna' => $image_title,
                'fcm_token' => $fcm_token,
                'is_verify' => 0
            ];

            // kebutuhan wa gateway
            $nowa = (substr($data['nomor_pengguna'], 0, 2) == '62' || substr($data['nomor_pengguna'], 0, 3) == '+62' || substr($data['nomor_pengguna'], 0, 2) == '08') ? $data['nomor_pengguna'] : '62' . substr($data['nomor_pengguna'], strpos($data['nomor_pengguna'], ' '));
            $otp_code = rand(100000, 999999);

            $verify = [
                'email' => $user_email,
                'token' => $otp_code,
                'date_created' => date('Y-m-d H:i:s'),
                'expired' => date('Y-m-d H:i:s', strtotime('+1 month'))
            ];

            // Check if email or number already exists
            $user = $model->where('nomor_pengguna', $data['nomor_pengguna'])->first();
            $check_email_user = $model->where('email_pengguna', $data['email_pengguna'])->countAllResults();
            $check_email_driver = $driverModel->where('email_rider', $data['email_pengguna'])->countAllResults();
            $check_email_mitra = $mitraModel->where('user_email_mitra', $data['email_pengguna'])->countAllResults();
            if ($check_email_user > 0 || $check_email_driver > 0 || $check_email_mitra > 0) {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Email sudah digunakan pengguna lain.'
                ]);
            }

            $check_number_driver = $driverModel->where('phone_rider', $data['nomor_pengguna'])->countAllResults();
            $check_number_user = $model->where('nomor_pengguna', $data['nomor_pengguna'])->countAllResults();
            $check_number_mitra = $mitraModel->where('user_phone_mitra', $data['nomor_pengguna'])->countAllResults();
            if ($check_number_user > 0 || $check_number_driver > 0 || $check_number_mitra > 0) {
                if ($user['is_verify'] == 1) {
                    $message = 'Nomor HP sudah digunakan pengguna lain.';
                } else {
                    $message = 'Nomor HP telah ada silahkan Login';
                }
                return $this->respond([
                    'status' => 401,
                    'message' => $message,
                ]);
            }

            if (!$user_image) {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Gambar pengguna tidak ditemukan.'
                ]);
            }

            // Decode base64 image
            $decoded = base64_decode($user_image);
            if (!$decoded) {
                return $this->fail('Gagal mendekode gambar pengguna.', 400);
            }

            // Validasi Save image
            if (!file_put_contents($path, $decoded)) {
                return $this->fail('Gagal menyimpan gambar pengguna.', 500);
            }

            // Insert data
            $model->insert($data);
            $verif->insert($verify);
            $this->_sendOTP($otp_code, 'verify', $nowa);

            // Save image
            $decoded = base64_decode($this->request->getPost('user_image'));
            file_put_contents($path, $decoded);

            // Get user ID
            $user = $model->where('email_pengguna', $data['email_pengguna'])->first();
            $user_id = $user['id_pengguna'];

            return $this->respondCreated([
                'status' => 200,
                'token' => $user_id,
                'message' => 'Data berhasil dibuat.',
                'is_verify' => 0
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server'
                // 'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ]);
        }
    }

    public function lupa_password()
    {
        $verif = new VerifyModel();
        $nohp = $this->request->getPost('user_number');

        $users = $this->userModel->where('nomor_pengguna', $nohp)->first();
        if (!$users) {
            return $this->respond([
                'status' => 404,
                'message' => 'Nomor HP tidak ditemukan.'
            ]);
        } else {
            $codeOTP = rand(1, 999999);
            $$verif->insert([
                'email' => $users['email_pengguna'],
                'token' => $codeOTP,
                'date_created' => date('Y-m-d H:i:s'),
                'expired' => date('Y-m-d H:i:s', strtotime('+1 month'))
            ]);

            $this->_sendOTP($codeOTP, 'forgot', $users['nomor_pengguna']);
            return $this->respondCreated([
                'status' => 200,
                'message' => 'OTP terkirim.'
            ]);
        }
    }

    public function reset_password()
    {
        $verif = new VerifyModel();
        $nohp = $this->request->getPost('user_number');
        $otp = $this->request->getPost('otp');
        $new_password = $this->request->getPost('new_password');

        $users = $this->userModel->where('nomor_pengguna', $nohp)->first();
        if (!$users) {
            return $this->respond([
                'status' => 404,
                'message' => 'Nomor HP tidak ditemukan.'
            ]);
        } else {
            $verification = $verif->where('email', $users['email_pengguna'])
                ->where('token', $otp)
                ->first();
            if (!$verification) {
                return $this->respond([
                    'status' => 400,
                    'message' => 'OTP tidak valid atau telah kadaluarsa.'
                ]);
            }

            // Update password user
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $this->userModel->update($users['id_pengguna'], ['password' => $hashedPassword]);

            // Hapus verifikasi token
            $verif->delete($verification['id_verify']);

            return $this->respond([
                'status' => 200,
                'message' => 'Password berhasil direset.'
            ]);
        }
    }

    public function otp_request()
    {
        try {
            $userModel = new UserModelApi();
            $verifyModel = new VerifyModel();

            $email = $this->request->getPost('email');

            // Cari pengguna berdasarkan email
            $user = $userModel->where('email_pengguna', $email)->first();

            // Jika pengguna ditemukan, hapus token verifikasi sebelumnya
            if ($user) {
                $verifyModel->where('email', $email)->delete();
            }

            $otp_code = rand(100000, 999999);
            $verify = [
                'email' => $email,
                'token' => $otp_code,
                'date_created' => date('Y-m-d H:i:s'),
                'expired' => date('Y-m-d H:i:s', strtotime('+1 month'))
            ];

            $verifyModel->insert($verify);

            // Kirim ulang OTP baru ke nomor HP berdasarkan email pengguna
            $nomor = $user['nomor_pengguna'];
            $this->_sendOTP($otp_code, 'verify', $nomor);

            return $this->respondCreated([
                'status' => 200,
                'message' => 'OTP berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ]);
        }
    }

    public function verify()
    {
        $email = $this->request->getVar('user_email');
        $token = $this->request->getVar('token');

        // Inisialisasi model
        $userModel = new UserModelApi();
        $verifModel = new VerifyModel();

        // Cari pengguna berdasarkan email
        $user = $userModel->where('email_pengguna', $email)->first();

        if ($user) {
            // Cari token berdasarkan token yang diberikan
            $users_verify = $verifModel->where('token', $token)->where('email', $email)->first();

            if ($users_verify) {
                // Periksa apakah token belum kedaluwarsa
                $verifModel->delete($users_verify['id_verify']);

                // Update status pengguna menjadi aktif
                $userModel->update($user['id_pengguna'], ['is_verify' => '1']);

                return $this->respondCreated([
                    'status' => 200,
                    'message' => 'OTP valid.'
                ]);
            } else {
                return $this->respond([
                    'status' => 401,
                    'message' => 'OTP tidak valid.'
                ], 401);
            }
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Pengguna tidak ditemukan.'
            ], 404);
        }
    }

    private function _sendOTP($otp, $type, $nomor)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://34.50.89.160:3001/api/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "recipient_type" => "individual",
                "to" => $nomor,
                "type" => "text",
                "text" => [
                    "body" => "#OTP> *$otp* \n\n&#128683 DEMI KEAMANAN JANGAN BERIKAN KODE OTP KEPADA SIAPAPUN (TERMASUK GASJek). \n\n Notes:\n_- Pesan ini dikirim secara otomatis oleh sistem GASJek._\n_- Untuk informasi lebih lanjut silahkan hubungi 0858-9661-7773._"
                ]
            ]),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ukOnFtHEplRfiT7O.FstbrTQ0dNzhsIHaDrgfs3Q4qSQ7rVyw'
            ),
        ));

        // Lakukan request cURL
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function update_password($id = null)
    {
        // Load model User
        $model = new UserModelApi();

        // Pembaruan password
        $user_id = $this->request->getPost('user_id');
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('new_password');

        // Ambil data pengguna berdasarkan ID
        $user = $model->find($user_id);

        // Periksa keberadaan pengguna
        if (!$user) {
            return $this->fail('Pengguna tidak ditemukan.', 404);
        }

        // Periksa kecocokan password lama
        if (!password_verify($old_password, $user['password_pengguna'])) {
            return $this->fail('Password lama tidak cocok.', 400);
        }

        // Hash password baru dan lakukan pembaruan
        $password_new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $model->update($user_id, ['password_pengguna' => $password_new_hash]);

        return $this->respond(['status' => 200, 'message' => 'Password berhasil diperbarui.']);
    }

    public function update_fcm_user($id = null)
    {
        // Load model User
        $model = new UserModelApi();

        $user_id = $this->request->getPost('token');
        $user = $model->find($user_id);

        // Periksa keberadaan pengguna
        if (!$user) {
            return $this->fail('Pengguna tidak ditemukan.', 404);
        }

        $data = [
            'id_pengguna' => $user_id,
            'fcm_token' => $this->request->getPost('fcm_token'),
        ];

        $model->update($user_id, $data);

        $response = ['status' => 200, 'message' => 'Informasi pengguna berhasil diperbarui.'];

        return $this->respond($response);
    }

    public function update_balance_user()
    {
        // Validasi input
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'token' => 'required',
            'balance' => 'required|numeric'
        ]);

        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $token = $this->request->getPost('token');
            $balance = $this->request->getPost('balance');

            $this->UserModelApi->where('id_pengguna', $token)
                ->set(['saldo_pengguna' => $balance])
                ->update();

            $response = [
                'status' => 200,
                'message' => 'Saldo pengguna berhasil diperbarui.'
            ];
        } else {
            $response = [
                'status' => 400,
                'message' => $validation->getErrors()
            ];
        }

        return $this->respond($response);
    }

    public function check_saldo()
    {
        $token = $this->request->getVar('token');
        $harga = $this->request->getVar('harga');

        $user = $this->UserModelApi->where('id_pengguna', $token)->first();

        if ($user) {
            if ($user['saldo_pengguna'] >= $harga) {
                return $this->respond([
                    'status' => 200,
                    'message' => 'Saldo cukup untuk melakukan transaksi ini.'
                ], 200);
            } else {
                return $this->respond([
                    'status' => 400,
                    'message' => 'Saldo tidak mencukupi.'
                ], 400);
            }
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Pengguna tidak ditemukan.'
            ], 404);
        }
    }

    public function check_time()
    {
        // Waktu operasional tutup dan buka
        $app = $this->applicationModel->select('waktu_operasional, status_develop')->first();
        $closeTime = $app['waktu_operasional']; // Tutup jam 22:00 (10 malam)
        $openTime = 5;  // Buka kembali jam 05:00 (5 pagi)
        $status_dev = $app['status_develop']; //true = develop, false = production

        // Dapatkan waktu server saat ini
        $currentTime = Time::now('Asia/Jakarta', 'id');
        $currentHour = (int)$currentTime->format('H');

        // Logika untuk mengecek apakah saat ini dalam jam operasional atau tidak
        if ($status_dev == 'true') {
            // Jika status_develop adalah true, berikan respons dengan status 202
            return $this->respond([
                'status' => 202,
                'message' => 'Aplikasi sedang dalam pengembangan.'
            ]);
        }

        // Jika bukan jam operasional, berikan respons dengan status 200
        if ($currentHour >= $closeTime || $currentHour < $openTime) {
            return $this->respond([
                'status' => 200,
                'message' => 'GASJek akan kembali dibuka pada pukul 05:00 Pagi',
                'time' => $currentTime->toDateTimeString()
            ]);
        }

        // Jika dalam jam operasional, tidak ada respon apa-apa
        return;
    }

    public function logout()
    {
        $model = new UserModelApi();
        $user_id = $this->request->getPost('user_id');
        $user = $model->find($user_id);
    }
}
