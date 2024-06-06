<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModelApi extends Model
{
    protected $table      = 'tb_pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $allowedFields = ['email_pengguna', 'nomor_pengguna', 'saldo_pengguna', 'password_pengguna', 'gambar_pengguna', 'fcm_token', 'nama_pengguna', 'is_verify'];


    public function getEmail($email = false)
    {
        if ($email == false) {
            return $this->findAll();
        }
        return $this->where(['email_pengguna' => $email])->first();
    }

    public function getUser($id_user = false)
    {
        if ($id_user == false) {
            return $this->findAll();
        }
        return $this->where(['id_pengguna' => $id_user])->first();
    }
}
