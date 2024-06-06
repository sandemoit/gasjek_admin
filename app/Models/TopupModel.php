<?php

namespace App\Models;

use CodeIgniter\Model;

class TopupModel extends Model
{
    protected $table = 'tb_topup';
    protected $primaryKey = 'id_topup';

    protected $allowedFields = [
        'order_id', 'gross_amount', 'user_id', 'type_user', 'transaction_time', 'payment_type', 'settlement_time', 'fraud_status', 'transaction_status', 'signature_key'
    ];
}
