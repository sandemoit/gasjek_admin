<?php

namespace App\Models;

use CodeIgniter\Model;

class VerifyModel extends Model
{
    protected $table      = 'users_verify';
    protected $primaryKey = 'id_verify';

    protected $allowedFields = ['email', 'token', 'date_created'];
}
