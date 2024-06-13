<?php

namespace App\Controllers;

use App\Models\DriverModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\WalletModel;
use App\Models\TransactionModel;
use Midtrans\Config;
use App\Models\UserModelApi;

require_once dirname(__FILE__) . '../../../vendor/midtrans-php-master/Midtrans.php';

class WalletApi extends ResourceController
{
    protected $modelName = 'App\Models\ReviewModel';
    protected $format = 'json';
    protected $walletModel;
    protected $TransactionModel;
    protected $UserModel;
    protected $DriverModel;

    public function __construct()
    {
        $this->walletModel = new walletModel();
        $this->TransactionModel = new TransactionModel();
        $this->UserModel = new UserModelApi();
        $this->DriverModel = new DriverModel();

        // Set your Merchant Server Key
        Config::$serverKey = env('SB_MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function index()
    {

        $db = \Config\Database::connect();
        $model = $this->model->findAll();

        $user_name = $this->request->getPost('user_name');
        $id_transaction = $this->request->getPost('id_transaction');
        $balance = $this->request->getPost('balance');
        $type_payment = $this->request->getPost('type_payment');
        $date = $this->request->getPost('date');
        $id_user = $this->request->getPost('id_user');
        $method_payment = $this->request->getPost('method_payment');

        if ($type_payment == "Penarikan") {
            $account_number = $this->request->getPost('account_number');
            // $perintah       = "INSERT INTO tb_balance (id_transaction,user_name,balance,type_payment,date,status_payment,id_user,method_payment,account_number) VALUES ('$id_transaction','$user_name','$balance','$type_payment','$date','Pending','$id_user','$method_payment','$account_number')";
        } else {
            $data[] = array(
                'id_transaction' => $id_transaction,
                'user_name' => $user_name,
                'balance' => $balance,
                'type_payment' => $type_payment,
                'date' => $date,
                'status_payment' => "pending",
                'id_user' => $id_user,
                'method_payment' => "Manual"
            );
        }
        $model->insert($data);

        $response = [
            'status'   => 1,
            'message'    => 'success',
        ];
        return $this->respond($response);
    }


    public function top_up()
    {
        // Mendapatkan data dari request
        $id_transaction = $this->request->getPost('id_transaction');
        $user_name = $this->request->getPost('user_name');
        $balance = $this->request->getPost('balance');
        $method_payment = $this->request->getPost('method_payment');
        $date = $this->request->getPost('date');
        $id_user = $this->request->getPost('id_user');
        $type_payment = $this->request->getPost('type_payment');
        $status_payment = $this->request->getPost('status_payment');
        $role = $this->request->getPost('role');

        // Membuat instance model WalletModel
        $model = new WalletModel();

        // Data untuk diinsert ke dalam database
        $data = [
            'id_transaction' => $id_transaction,
            'user_name' => $user_name,
            'balance' => $balance,
            'method_payment' => "Manual",
            'date' => $date,
            'id_user' => $id_user,
            'type_payment' => $type_payment,
            'status_payment' => $status_payment,
            'role' => $role
        ];

        // Memasukkan data ke dalam tabel wallet
        $model->insert($data);

        // Respon berhasil
        $response = [
            'status'   => 200,
            'messages' => 'Top Up Berhasil'
        ];

        return $this->respondCreated($response);
    }

    public function history()
    {
        // Mengambil data dari request
        $user_id = $this->request->getVar('user_id');
        $type_user = $this->request->getVar('type_user');

        // Mengecek apakah user_id ada dalam database berdasarkan type_user
        if ($type_user == 1) {
            $user = $this->UserModel->where('id_pengguna', $user_id)->first();
        } elseif ($type_user == 0) {
            $driver = $this->DriverModel->where('id_driver', $user_id)->first();
        } else {
            return $this->respond([
                'status' => 400,
                'message' => 'Type User tidak valid.'
            ]);
        }

        if (!$user && !$driver) {
            return $this->respond([
                'status' => 404,
                'message' => 'User ID tidak ditemukan.'
            ]);
        }

        // Mengambil data riwayat transaksi dari model berdasarkan type_user
        $selectFields = 'order_id, type_transaction, gross_amount, transaction_time, transaction_date, payment_type, transaction_status';

        // Mengambil data riwayat transaksi dari model berdasarkan type_user
        if ($type_user == 1) {
            $dataHistories = $this->TransactionModel
                ->select($selectFields)
                ->where('user_id', $user['id_pengguna'])
                ->orderBy('transaction_date', 'DESC')
                ->findAll();
        } elseif ($type_user == 0) {
            $dataHistories = $this->TransactionModel
                ->select($selectFields)
                ->where('user_id', $driver['id_driver'])
                ->orderBy('transaction_date', 'DESC')
                ->findAll();
        }

        // Mengecek apakah ada data riwayat transaksi
        if ($dataHistories) {
            $response = [
                'status' => 200,
                'message' => 'success',
                'dataHistories' => $dataHistories
            ];
        } else {
            $response = [
                'status' => 404,
                'message' => 'Data riwayat transaksi tidak ditemukan',
                'dataHistories' => []
            ];
        }

        return $this->respond($response);
    }

    public function transfer_saldo()
    {
        $UserModel = new UserModelApi();
        $walletModel = new WalletModel();
        $transactionModel = new TransactionModel();

        // Mendapatkan data dari request
        $order_id = $this->request->getPost('order_id');
        $user_id = $this->request->getPost('user_id');
        $phone_number = $this->request->getPost('phone_number');
        $gross_amount = $this->request->getPost('gross_amount');

        // split order_id
        $parts = explode('-', $order_id);
        $type_user = $parts[1];

        // Validasi input
        if (!$user_id || !$phone_number || !$gross_amount) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'User id, phone number, and gross amount diperlukan'
            ]);
        }

        // Pastikan jumlah yang dikirimkan adalah angka positif
        if ($gross_amount <= 0) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Jumlah harus berupa angka positif'
            ]);
        }

        // Memeriksa keberadaan penerima
        $recipient = $UserModel->where('nomor_pengguna', $phone_number)->first();
        if (!$recipient) {
            return $this->response->setJSON([
                'status' => 404,
                'message' => 'Penerima tidak ditemukan'
            ]);
        }

        // Memeriksa user pengirim
        $sender = $UserModel->where('id_pengguna', $user_id)->first();
        if (!$sender) {
            return $this->response->setJSON([
                'status' => 404,
                'message' => 'Pengirim tidak ditemukan'
            ]);
        }

        // Validasi saldo pengirim cukup
        if ($sender['saldo_pengguna'] < $gross_amount) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Saldo tidak mencukupi'
            ]);
        }

        // Memulai transaksi
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Kurangi saldo pengirim
            $new_sender_balance = $sender['saldo_pengguna'] - $gross_amount;
            $UserModel->update($user_id, ['saldo_pengguna' => $new_sender_balance]);

            // Tambahkan saldo ke penerima
            $new_recipient_balance = $recipient['saldo_pengguna'] + $gross_amount;
            $UserModel->update($recipient['id_pengguna'], ['saldo_pengguna' => $new_recipient_balance]);

            // Catat transaksi untuk pengirim di WalletModel
            $walletModel->insert([
                'method_payment' => 'transfer',
                'status_payment' => 'success',
                'user_name' => $sender['nama_pengguna'],
                'balance' => -$gross_amount,
                'type_payment' => 'transfer',
                'date' => date('Y-m-d H:i:s'),
                'id_user' => $user_id,
                'role' => 'user'
            ]);

            // Catat transaksi untuk penerima di WalletModel
            $walletModel->insert([
                'method_payment' => 'transfer',
                'status_payment' => 'success',
                'type_transaction' => 'transfer',
                'user_name' => $recipient['nama_pengguna'],
                'balance' => $gross_amount,
                'type_payment' => 'top_up',
                'date' => date('Y-m-d H:i:s'),
                'id_user' => $recipient['id_pengguna'],
                'role' => 'user'
            ]);

            // Catat transaksi untuk pengirim di TransactionModel
            $transactionModel->insert([
                'order_id' => $order_id,
                'gross_amount' => $gross_amount,
                'user_id' => $user_id,
                'type_user' => $type_user,
                'transaction_time' => date('H:i:s'),
                'transaction_date' => date('Y-m-d'),
                'payment_type' => $recipient['nama_pengguna'],
                'type_transaction' => 'transfer',
                'settlement_time' => date('Y-m-d H:i:s'),
                'fraud_status' => 'accept',
                'transaction_status' => 'success',
            ]);

            // Catat transaksi untuk penerima di TransactionModel
            $transactionModel->insert([
                'order_id' => $order_id,
                'gross_amount' => $gross_amount,
                'user_id' => $recipient['id_pengguna'],
                'type_user' => $type_user,
                'transaction_time' => date('H:i:s'),
                'transaction_date' => date('Y-m-d'),
                'type_transaction' => 'top_up',
                'payment_type' => $sender['nama_pengguna'],
                'settlement_time' => date('Y-m-d H:i:s'),
                'fraud_status' => 'accept',
                'transaction_status' => 'success',
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Kirim notifikasi ke pengirim
            $sender_message = "Transfer sebesar Rp. " . number_format($gross_amount, 0, ',', '.') . " berhasil dikirim.";
            $title = "Transfer Berhasil";
            $this->sendNotifikasi($user_id, $title, $sender_message);

            // Kirim notifikasi ke penerima
            $recipient_message = "Anda menerima transfer dari " . $sender['nama_pengguna'] . " sebesar Rp. " . number_format($gross_amount, 0, ',', '.') . ".";
            $title = "Top Up Berhasil!";
            $this->sendNotifikasi($recipient['id_pengguna'], $title, $recipient_message);

            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Transfer successful'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    private function sendNotifikasi($user_id, $title, $message)
    {
        $curl = curl_init();

        $authKey =  "key=AAAAsEFfA94:APA91bEWcdw5T9V5stayg_MZqPPJPhz2VbbuvRujVCU8OJg4t1hauqodHK_k_RgqS_B9dCnDNEX-ZXrS69RCrSr7ipSj5CiF6EZ4jodIVuHKb3B2Ajjr1fNSRv4ejomIHQ6UXF69kmgF";

        $user = $this->UserModel->where('id_pengguna', $user_id)->first();
        $fcm_token = $user['fcm_token'];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
                    "to": "' . $fcm_token . '",
                    "priority": "high",
                    "data" : {
                        "body" : "' . $message . '",
                        "title": "' . $title . '",,
                    }
                }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authKey,
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
    }

    /**
     * Transaction API
     * 
     * @return ResponseInterface
     */
    // public function transaction()
    // {
    //     $input = $this->request->getRawInput();

    //     // Set your Merchant Server Key
    //     Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     Config::$isProduction = false;
    //     // Set sanitization on (default)
    //     Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to true
    //     Config::$is3ds = true;

    //     $params = [
    //         'transaction_details' => [
    //             'order_id' => $input['order_id'] ?? rand(1000, 9999),
    //             'gross_amount' => $input['gross_amount'] ?? 10000,
    //         ],
    //         'customer_details' => [
    //             'first_name' => $input['first_name'] ?? 'budi',
    //             'last_name' => $input['last_name'] ?? 'pratama',
    //             'email' => $input['email'] ?? 'budi.pra@example.com',
    //             'phone' => $input['phone'] ?? '08111222333',
    //         ],
    //     ];

    //     $data = [
    //         'snapToken' => \Midtrans\Snap::getSnapToken($params)
    //     ];

    //     return $this->respond($data, 201);
    // }
}
