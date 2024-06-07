<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TopupModel;
use App\Models\UserModelApi;
use App\Models\WalletModel;
use Midtrans\Config;
use Midtrans\Notification;

require_once dirname(__FILE__) . '../../../vendor/midtrans-php-master/Midtrans.php';

class MidtransApi extends ResourceController
{
    protected $topupModel;
    protected $userModelApi;
    protected $walletModel;

    public function __construct()
    {
        $this->topupModel = new TopupModel();
        $this->userModelApi = new UserModelApi();
        $this->walletModel = new WalletModel();

        // Set your Merchant Server Key
        Config::$serverKey = env('SB_MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function callback()
    {
        log_message('info', 'Midtrans callback received.');

        $topupModel = new TopupModel();
        $notification = new Notification();

        try {
            // Signature key validation
            $order_id = $notification->order_id;
            $status_code = $notification->status_code;
            $gross_amount = $notification->gross_amount;
            $signature_key = $notification->signature_key;
            $serverKey = env('SB_MIDTRANS_SERVER_KEY');

            $input = $order_id . $status_code . $gross_amount . $serverKey;
            $hashed = hash('sha512', $input);

            $transaction_status = $notification->transaction_status;
            $fraud_status = $notification->fraud_status;

            if ($hashed == $signature_key) {
                // Access user_id from metadata
                $user_id = $notification->metadata->extra_info->user_id ?? null;

                if ($user_id === null) {
                    log_message('error', 'User ID not found in notification metadata');
                }

                // Split order_id untuk type user
                $parts = explode('-', $order_id);
                $type_user = $parts[2];

                // Handle transaction status
                if ($transaction_status == 'capture') {
                    if ($fraud_status == 'accept') {
                        $this->topupModel->where('order_id', $order_id)
                            ->set(['transaction_status' => $transaction_status, 'settlement_time' => date('Y-m-d H:i:s')])
                            ->update();
                    }
                } else if ($transaction_status == 'settlement') {
                    $this->topupModel->where(
                        'order_id',
                        $order_id
                    )
                        ->set(['transaction_status' => $transaction_status, 'settlement_time' => date('Y-m-d H:i:s')])
                        ->update();

                    $message = "Saldo Rp. " . number_format($gross_amount, 2, ',', '.') . " telah ditambahkan.";
                    $title = "Top Up Anda Berhasil!";
                    $this->updateSaldo($user_id, $gross_amount);
                    $this->send_broadcast($user_id, $title, $message);
                } else if (
                    $transaction_status == 'cancel' ||
                    $transaction_status == 'deny' ||
                    $transaction_status == 'expire'
                ) {
                    $this->topupModel->where(
                        'order_id',
                        $order_id
                    )
                        ->set(['transaction_status' => $transaction_status])
                        ->update();
                    $message = "yahh transaksi " . $transaction_status . " :(, silahkan coba lagi.";
                    $title = "Top Up Anda " . $transaction_status . "!";
                    $this->updateSaldo($user_id, $gross_amount);
                    $this->send_broadcast($user_id, $title, $message);
                } else if ($transaction_status == 'pending') {
                    // Insert data into tb_topup
                    $data = [
                        'order_id' => $order_id,
                        'gross_amount' => $gross_amount,
                        'user_id' => $user_id,
                        'type_user' => $type_user,
                        'transaction_time' => $notification->transaction_time,
                        'payment_type' => $notification->payment_type,
                        'settlement_time' => date('Y-m-d H:i:s'),
                        'fraud_status' => $fraud_status,
                        'transaction_status' => $transaction_status,
                        'signature_key' => $signature_key
                    ];
                    $topupModel->insert($data);
                }
            }
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
        }
    }

    private function updateSaldo($user_id, $nominal)
    {
        $walletModel  = new walletModel();
        $userModelApi = new UserModelApi();

        // Check if the user already has a wallet
        $user = $userModelApi->where('id_pengguna', $user_id)->first();

        $data = [
            'method_payment' => 'Midtrans',
            'status_payment' => 'Success',
            'user_name' => $user['nama_pengguna'],
            'balance' => $nominal,
            'type_payment' => 'Topup',
            'date' => date('Y-m-d H:i:s'),
            'id_user' => $user_id,
            'role' => 'user'
        ];

        $userModelApi->where('id_pengguna', $user_id)->set(['saldo_pengguna' => $user['saldo_pengguna'] + $nominal])->update();
        $walletModel->insert($data);
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
