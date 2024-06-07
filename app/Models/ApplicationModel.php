<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationModel extends Model
{
    protected $table      = 'tb_application';
    protected $primaryKey = 'id_application';

    protected $allowedFields = [
        'admin_phone',
        'app_name',
        'favicon',
        'key_message'
    ];
}
