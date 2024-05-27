<?php

namespace App\Models;

use CodeIgniter\Model;

class MapModel extends Model
{
    protected $table      = 'tb_map';

    protected $allowedFields = ['satu_km', 'tiga_koma_lima_km','lima_km'];
}
?>