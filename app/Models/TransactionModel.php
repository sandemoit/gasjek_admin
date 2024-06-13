<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'tb_transaction';
    protected $primaryKey = 'id_transaction';

    protected $allowedFields = [
        'order_id', 'gross_amount', 'user_id', 'type_user', 'transaction_time', 'transaction_date', 'payment_type', 'settlement_time', 'fraud_status', 'transaction_status', 'signature_key', 'type_transaction'
    ];
}
