<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TopupModel;
use Midtrans\Config;
use Midtrans\Notification;

require_once dirname(__FILE__) . '../../../vendor/midtrans-php-master/Midtrans.php';

class MidtransApi extends ResourceController
{
    protected $topupModel;

    public function __construct()
    {
        $this->topupModel = new TopupModel();

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

        // Signature key validation
        $order_id = $notification->order_id;
        $status_code = $notification->status_code;
        $gross_amount = $notification->gross_amount;
        $signature_key = $notification->signature_key;
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $input = $order_id . $status_code . $gross_amount . $serverKey;
        $hashed = hash('sha512', $input);

        $transaction_status = $notification->transaction_status;
        $fraud_status = $notification->fraud_status;

        if ($hashed == $signature_key) {
            // Handle transaction status
            if ($transaction_status == 'capture') {
                if ($fraud_status == 'accept') {
                    $this->topupModel->where('order_id', $order_id)->set(['transaction_status' => $transaction_status, 'settlement_time' => date('Y-m-d H:i:s')])->update();
                }
            } else if ($transaction_status == 'settlement') {
                $this->topupModel->where('order_id', $order_id)->set(['transaction_status' => $transaction_status, 'settlement_time' => date('Y-m-d H:i:s')])->update();
            } else if (
                $transaction_status == 'cancel' ||
                $transaction_status == 'deny' ||
                $transaction_status == 'expire'
            ) {
                $this->topupModel->where('order_id', $order_id)->set(['transaction_status' => $transaction_status])->update();
            } else if ($transaction_status == 'pending') {
                $parts = explode('-', $order_id);
                $type_user = $parts[1];

                // Insert data into tb_topup
                $topupModel->insert([
                    'order_id' => $order_id,
                    'gross_amount' => $gross_amount,
                    'user_id' => 1,
                    'type_user' => $type_user,
                    'transaction_time' => $notification->transaction_time,
                    'payment_type' => $notification->payment_type,
                    'settlement_time' => date('Y-m-d H:i:s'),
                    'fraud_status' => $fraud_status,
                    'transaction_status' => $transaction_status,
                    'signature_key' => $signature_key
                ]);
            }
        }
    }
}
