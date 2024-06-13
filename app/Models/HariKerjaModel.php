<?php

namespace App\Models;

use CodeIgniter\Model;

class HariKerjaModel extends Model
{
    protected $table      = 'tb_harikerja';
    protected $primaryKey = 'id_harikerja';
    protected $allowedFields = ['hari'];
}
