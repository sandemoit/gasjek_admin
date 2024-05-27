<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'tb_balance';
    protected $primaryKey = 'id_transaction';

    protected $allowedFields = ['id_transaction', 'method_payment', 'status_payment', 'user_name', 'balance', 'type_payment', 'date', 'id_user', 'role'];

    public function getWallet($id_transaction = false)
    {
        if ($id_transaction == false) {
            return $this->findAll();
        }
        return $this->where(['id_transaction' => $id_transaction])->first();
    }
}
