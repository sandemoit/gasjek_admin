<?php

namespace App\Controllers;

use App\Models\DriverModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TransactionModel;
use App\Models\UserModelApi;
use App\Models\WalletModel;
use DateTime;
use Midtrans\Config;
use Midtrans\Notification;

require_once dirname(__FILE__) . '../../../vendor/midtrans-php-master/Midtrans.php';

class MidtransApi extends ResourceController
{
    protected $TransactionModel;
    protected $userModelApi;
    protected $walletModel;
    protected $DriverModel;

    public function __construct()
    {
        $this->TransactionModel = new TransactionModel();
        $this->userModelApi = new UserModelApi();
        $this->walletModel = new WalletModel();
        $this->DriverModel = new DriverModel();

        // Set your Merchant Server Key
        if (strpos(uri_string(), 'api/midtrans-sandbox') !== false) {
            Config::$serverKey = env('SB_MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
        } else {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = true;
        }

        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function callback()
    {
        $TransactionModel = new TransactionModel();
        $notification = new Notification();

        try {
            // Validasi signature key
            $order_id = $notification->order_id;
            $status_code = $notification->status_code;
            $gross_amount = $notification->gross_amount;
            if (strpos(uri_string(), 'api/midtrans-sandbox') !== false) {
                $serverKey = env('SB_MIDTRANS_SERVER_KEY');
            } else {
                $serverKey = env('MIDTRANS_SERVER_KEY');
            }

            $input = $order_id . $status_code . $gross_amount . $serverKey;
            $hashed = hash('sha512', $input);

            $transaction_status = $notification->transaction_status;
            $fraud_status = $notification->fraud_status;

            $signature_key = $notification->signature_key;
            if ($hashed !== $signature_key) {
                log_message('error', 'Invalid signature key');
                return;
            }

            // Mengakses user_id dari metadata
            $user_id = $notification->metadata->extra_info->user_id ?? null;

            if ($user_id === null) {
                log_message('error', 'ID pengguna tidak ditemukan dalam metadata notifikasi');
                return;
            }

            // Calculate saldo
            $saldo = $gross_amount - 2500;

            // Split order_id untuk type user
            $parts = explode('-', $order_id);
            $type_user = $parts[1];

            // Menangani status transaksi
            switch ($transaction_status) {
                case 'capture':
                    if ($fraud_status === 'accept') {
                        $this->updateTransactionStatus($TransactionModel, $order_id, $transaction_status);
                    }
                    break;

                case 'settlement':
                    $this->updateTransactionStatus($TransactionModel, $order_id, $transaction_status);
                    $message = "Saldo Rp. " . number_format($saldo, 0, ',', '.') . " telah ditambahkan.";
                    $title = "Top Up Anda Berhasil!";
                    $this->updateSaldo($user_id, $saldo, 'success');
                    $this->send_broadcast($user_id, $title, $message);
                    break;

                case 'cancel':
                case 'deny':
                case 'expire':
                    $this->updateTransactionStatus($TransactionModel, $order_id, $transaction_status);
                    $message = "Yahh transaksi " . $transaction_status . " :( Silahkan coba lagi.";
                    $title = "Top Up Anda Gagal!";
                    // $this->deleteTransaction($user_id, $transaction_status);
                    $this->send_broadcast($user_id, $title, $message);
                    break;

                case 'pending':
                    $this->handlePendingTransaction($TransactionModel, $notification, $user_id, $type_user);
                    $this->insertSaldo($user_id, $notification);
                    break;
            }
        } catch (\Exception $e) {
            log_message('error', 'Ado error coy: ' . $e->getMessage());
        }
    }

    private function handlePendingTransaction($TransactionModel, $notification, $user_id, $type_user)
    {
        // Split transaction_time into date and time
        $datetime = new DateTime($notification->transaction_time);
        $transaction_date = $datetime->format('Y-m-d');
        $transaction_time = $datetime->format('H:i:s');

        // Insert data into tb_transaction
        $data = [
            'order_id' => str_replace('--', '-', $notification->order_id),
            'gross_amount' => $notification->gross_amount,
            'user_id' => $user_id,
            'type_user' => $type_user,
            'transaction_time' => $transaction_date,
            'transaction_date' => $transaction_time,
            'payment_type' => $notification->payment_type,
            'settlement_time' => date('Y-m-d H:i:s'),
            'fraud_status' => $notification->fraud_status,
            'transaction_status' => $notification->transaction_status,
            'signature_key' => $notification->signature_key
        ];

        // Insert data into tb_transaction
        $TransactionModel->insert($data);
    }

    private function insertSaldo($user_id, $notification)
    {
        $walletModel = new WalletModel();
        $userModelApi = new UserModelApi();
        $driverModel = new DriverModel();

        // Ambil data pengguna
        $user = $userModelApi->where('id_pengguna', $user_id)->first();
        $driver = $driverModel->where('id_driver', $user_id)->first();

        if ($user) {
            $this->insertUserWallet($walletModel, $user, $notification, $user_id);
        } elseif ($driver) {
            $this->insertDriverWallet($walletModel, $driver, $notification, $user_id);
        }

        return true;
    }

    private function insertUserWallet($walletModel, $user, $notification, $user_id)
    {
        $data = [
            'method_payment' => $notification->payment_type,
            'status_payment' => $notification->transaction_status,
            'user_name' => $user['nama_pengguna'],
            'balance' => $notification->gross_amount,
            'type_payment' => 'top_up',
            'date' => $notification->transaction_time,
            'id_user' => $user_id,
            'role' => 'user'
        ];

        $walletModel->insert($data);
    }

    private function insertDriverWallet($walletModel, $driver, $notification, $user_id)
    {
        $data = [
            'method_payment' => $notification->payment_type,
            'status_payment' => $notification->transaction_status,
            'user_name' => $driver['username_rider'],
            'balance' => $notification->gross_amount,
            'type_payment' => 'top_up',
            'date' => $notification->transaction_time,
            'id_user' => $user_id,
            'role' => 'driver'
        ];

        $walletModel->insert($data);
    }

    private function updateTransactionStatus($transactionModel, $order_id, $transaction_status)
    {
        $transactionModel->where('order_id', $order_id)
            ->set(['transaction_status' => $transaction_status, 'settlement_time' => date('Y-m-d H:i:s')])
            ->update();
    }

    private function updateSaldo($user_id, $saldo, $status)
    {
        $walletModel = new WalletModel();
        $userModelApi = new UserModelApi();
        $driverModel = new DriverModel();
        $TransactionModel = new TransactionModel();

        // Ambil user dari walletModel
        $user = $walletModel->where('id_user', $user_id)->first();
        $role = $user['role'];

        // Ambil user dari transactionModel untuk tipe user
        $transaction = $TransactionModel->where('user_id', $user_id)->first();
        $type_user = $transaction['type_user'];

        if ($type_user == 1 || $role == "user") {
            $this->updateUserSaldo($userModelApi, $user_id, $saldo, $status, $walletModel);
        } elseif ($type_user == 0 || $role == "driver") {
            $this->updateDriverSaldo($driverModel, $user_id, $saldo, $status, $walletModel);
        }

        return true;
    }

    private function updateUserSaldo($userModelApi, $user_id, $saldo, $status, $walletModel)
    {
        // Ambil data pengguna
        $user = $userModelApi->where('id_pengguna', $user_id)->first();

        // Hitung saldo baru
        $new_saldo = $user['saldo_pengguna'] + $saldo;

        // Perbarui saldo pengguna
        $userModelApi->where('id_pengguna', $user_id)
            ->set(['saldo_pengguna' => $new_saldo])
            ->update();

        // Perbarui status pembayaran di wallet
        $walletModel->where('id_user', $user_id)
            ->set(['status_payment' => $status])
            ->update();
    }

    private function updateDriverSaldo($driverModel, $user_id, $saldo, $status, $walletModel)
    {
        // Ambil data driver
        $driver = $driverModel->where('id_driver', $user_id)->first();

        // Hitung saldo baru
        $new_saldo = $driver['balance_rider'] + $saldo;

        // Perbarui saldo driver
        $driverModel->where('id_driver', $user_id)
            ->set(['balance_rider' => $new_saldo])
            ->update();

        // Perbarui status pembayaran di wallet
        $walletModel->where('id_user', $user_id)
            ->set(['status_payment' => $status])
            ->update();
    }

    private function deleteTransaction($user_id, $status)
    {
        $walletModel = new WalletModel();
        $transactionModel = new TransactionModel();

        // Ambil user dari walletModel
        $user = $walletModel->where('id_user', $user_id)->first();
        $role = $user['role'];

        // Ambil user dari transactionModel untuk tipe user
        $transaction = $transactionModel->where('user_id', $user_id)->first();
        $type_user = $transaction['type_user'];

        // Hapus transaksi berdasarkan role atau type_user
        if (($role == "user" || $type_user == 1) || ($role == "driver" || $type_user == 0)) {
            $walletModel->where('id_user', $user_id)
                ->where('status_payment', $status)
                ->delete();

            $transactionModel->where('user_id', $user_id)
                ->where('transaction_status', $status)
                ->delete();
        }

        return true;
    }

    private function send_broadcast($user_id, $title, $message)
    {
        $curl = curl_init();

        $authKey =  "key=AAAAsEFfA94:APA91bEWcdw5T9V5stayg_MZqPPJPhz2VbbuvRujVCU8OJg4t1hauqodHK_k_RgqS_B9dCnDNEX-ZXrS69RCrSr7ipSj5CiF6EZ4jodIVuHKb3B2Ajjr1fNSRv4ejomIHQ6UXF69kmgF";

        $user = $this->userModelApi->where('id_pengguna', $user_id)->first();
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
}
