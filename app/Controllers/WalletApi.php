<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\WalletModel;

class WalletApi extends ResourceController
{
    protected $modelName = 'App\Models\ReviewModel';
    protected $format = 'json';
    protected $walletModel;

    public function __construct()
    {
        $this->walletModel = new walletModel();
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
                'status_payment' => "Pending",
                'id_user' => $id_user,
                'method_payment' => $method_payment
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
            'method_payment' => $method_payment,
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
        $id_user = $this->request->getvar('id_user');

        // Mengambil data riwayat transaksi dari model
        $dataHistories = $this->walletModel->where('id_user', $id_user)->orderBy('date', 'DESC')->findAll();

        // Mengecek apakah ada data riwayat transaksi
        if ($dataHistories) {
            // Jika ada, proses data riwayat transaksi
            $response = [
                'status' => 200,
                'message' => 'success',
                'dataHistories' => $dataHistories
            ];
        } else {
            // Jika tidak ada data riwayat transaksi, respons kosong
            $response = [
                'status' => 404,
                'message' => 'Data riwayat transaksi tidak ditemukan',
                'dataHistories' => []
            ];
        }

        return $this->respond($response);
    }
}
