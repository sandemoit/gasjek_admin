<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModelApi;
use App\Models\VerifyModel;
use CodeIgniter\API\ResponseTrait;

class UserApi extends ResourceController
{

    use ResponseTrait;
    protected $UserModelApi;

    public function __construct()
    {
        $this->UserModelApi = new \App\Models\UserModelApi();
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

    public function create()
    {
        $model = new UserModelApi();
        $verif = new VerifyModel();

        // Generate image title
        $user_name = $this->request->getPost('user_name');
        $image_title = $user_name . '-' . date('Y-m-d-H:i') . ".jpg";
        $path = FCPATH . "assets/profile_photo/" . $image_title;

        // Hash password
        $password_hash = password_hash($this->request->getPost('user_password'), PASSWORD_DEFAULT);

        // Prepare data
        $data = [
            'nama_pengguna' => $user_name,
            'email_pengguna' => $this->request->getPost('user_email'),
            'nomor_pengguna' => $this->request->getPost('user_number'),
            'password_pengguna' => $password_hash,
            'gambar_pengguna' => $image_title,
            'fcm_token' => $this->request->getPost('fcm_token'),
            'is_active' => 0
        ];

        $otp_code = rand(100000, 999999);
        $verify = [
            'email' => $this->request->getPost('user_email'),
            'token' => $otp_code,
            'date_created' => date('Y-m-d')
        ];

        // Check if email or number already exists
        $check_email = $model->where('email_pengguna', $data['email_pengguna'])->countAllResults();
        $check_number = $model->where('nomor_pengguna', $data['nomor_pengguna'])->countAllResults();

        if ($check_email > 0) {
            return $this->respondCreated([
                'status' => 400,
                'message' => 'Email sudah digunakan pengguna lain.'
            ]);
        }

        if ($check_number > 0) {
            return $this->respondCreated([
                'status' => 401,
                'message' => 'Nomor HP sudah digunakan pengguna lain.'
            ]);
        }

        $user_image = $this->request->getPost('user_image');
        if (!$user_image) {
            return $this->fail('Gambar pengguna tidak ditemukan dalam permintaan.', 400);
        }

        // Decode base64 image
        $decoded = base64_decode($user_image);
        if (!$decoded) {
            return $this->fail('Gagal mendekode gambar pengguna.', 400);
        }

        // Save image
        if (!file_put_contents($path, $decoded)) {
            return $this->fail('Gagal menyimpan gambar pengguna.', 500);
        }

        // Insert data
        $model->insert($data);
        $verif->insert($verify);
        $this->_sendOTP($otp_code, 'verify');

        // Save image
        $decoded = base64_decode($this->request->getPost('user_image'));
        file_put_contents($path, $decoded);

        // Get user ID
        $user = $model->where('email_pengguna', $data['email_pengguna'])->first();
        $user_id = $user['id_pengguna'];

        return $this->respondCreated([
            'status' => 200,
            'token' => $user_id,
            'message' => 'Data berhasil dibuat.'
        ]);
    }

    private function _sendOTP($otp, $type)
    {
        $db = \Config\Database::connect();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => '085380945896',
                'message' => 'DEMI KEAMANAN JANGAN KASIH KODE INI KE SIAPA PUN (TERMASUK GASJek). Berikut KODE OTP untuk DAFTAR akun GASJek Anda: *' . $otp . '*',
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: 99i#D35ah9avRTNJhwir'
            ),
        ));

        // Lakukan request cURL
        $response = curl_exec($curl);

        if ($response === false) {
            // Handle cURL error
            return $this->respondCreated([
                'status' => 500,
                'message' => 'Error during cURL request: ' . curl_error($curl)
            ]);
        } else {
            // Decode respons JSON
            $api_response = json_decode($response, true);

            if ($api_response['status'] == true) {
                return $this->respondCreated([
                    'status' => 200,
                    'message' => $api_response['detail']
                ]);
            } else {
                return $this->respondCreated([
                    'status' => 401,
                    'message' => 'Kirim gagal: ' . $api_response['reason']
                ]);
            }
        }

        curl_close($curl);
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
                // Periksa apakah token belum kedaluwarsa (misalnya 1 hari)
                $dateCreated = new \DateTime($users_verify['date_created']);
                $now = new \DateTime();
                $interval = $now->diff($dateCreated);

                if ($interval->days < 1) {
                    // Hapus token verifikasi
                    $verifModel->delete($users_verify['id_verify']);

                    // Update status pengguna menjadi aktif
                    $userModel->update($user['id_pengguna'], ['is_active' => '1']);

                    return $this->respondCreated([
                        'status' => 200,
                        'message' => 'OTP valid.'
                    ]);
                } else {
                    // Token kedaluwarsa, hapus token dan pengguna
                    $userModel->delete($user['id_pengguna']);
                    $verifModel->delete($users_verify['id_verify']);

                    return $this->respond([
                        'status' => 401,
                        'message' => 'OTP expired.'
                    ], 401);
                }
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


    public function login()
    {
        $db = \Config\Database::connect();

        // Ambil email dan password dari permintaan
        $email = $this->request->getPost('user_email');
        $password = $this->request->getPost('user_password');

        // Periksa keberadaan pengguna berdasarkan email
        $query_user = $db->query("SELECT * FROM tb_pengguna WHERE email_pengguna = ?", [$email]);
        $user = $query_user->getRow();

        // Jika pengguna ditemukan
        if ($user) {
            $response['status'] = 1;
            $response['message'] = "User Tersedia";

            // Ambil ID pengguna dan hash password
            $user_id = $user->id_pengguna;
            $password_hash = $user->password_pengguna;

            // Verifikasi password
            if (password_verify($password, $password_hash)) {
                // Jika password cocok, perbarui token FCM jika disediakan
                $fcm_token = $this->request->getPost('fcm_token');
                if ($fcm_token) {
                    $db->query("UPDATE tb_pengguna SET fcm_token = ? WHERE email_pengguna = ?", [$fcm_token, $email]);
                }
                $response['token'] = $user_id;
            } else {
                // Jika password tidak cocok
                $response['status'] = 4;
                $response['message'] = "Password Anda Salah";
            }
        } else {
            // Jika pengguna tidak ditemukan
            $response['status'] = 3;
            $response['message'] = "Email dan Password Anda Salah";
        }

        return $this->respond($response);
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

    public function update($id = null)
    {
        // Load model User
        $model = new UserModelApi();

        $user_id = $this->request->getPost('user_id');
        $user = $model->find($user_id);

        // Periksa keberadaan pengguna
        if (!$user) {
            return $this->fail('Pengguna tidak ditemukan.', 404);
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('user_name'),
            'email_pengguna' => $this->request->getPost('user_email'),
            'nomor_pengguna' => $this->request->getPost('user_number'),
            'gambar_pengguna' => $this->request->getPost('user_image'),
        ];

        // Periksa apakah email dan nomor HP sudah digunakan oleh pengguna lain
        $check_email = $model->where('email_pengguna', $data['email_pengguna'])->where('id_pengguna !=', $user_id)->countAllResults();
        $check_number = $model->where('nomor_pengguna', $data['nomor_pengguna'])->where('id_pengguna !=', $user_id)->countAllResults();

        if ($check_number > 0) {
            return $this->fail('No HP sudah digunakan oleh pengguna lain.', 401);
        }
        if ($check_email > 0) {
            return $this->fail('Email sudah digunakan oleh pengguna lain.', 400);
        }

        // Jika gambar pengguna diperbarui
        $user_image = $this->request->getPost('user_image');
        if ($user_image !== "no") {
            // Simpan gambar baru
            $date = date('Y-m-d-H:i');
            $image_title = "{$data['nama_pengguna']} - $date.jpg";
            $path = "assets/profile_photo/$image_title";

            if (!file_put_contents($path, base64_decode($user_image))) {
                return $this->fail('Gagal menyimpan gambar pengguna.', 500);
            }

            $data['gambar_pengguna'] = $image_title;
        }

        // Lakukan pembaruan informasi pengguna
        $model->update($user_id, $data);

        return $this->respond(['status' => 200, 'message' => 'Informasi pengguna berhasil diperbarui.']);
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
}