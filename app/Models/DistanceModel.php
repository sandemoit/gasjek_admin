<?php

namespace App\Models;

use CodeIgniter\Model;

class DistanceModel extends Model
{
    protected $table      = 'tb_map';
    protected $allowedFields = ['1km', '2km', '3km', '4km', 'dua_koma_tujuh_km', 'tiga_setengah_km', 'minimum_balance', 'api_key_user'];

    public function getDistance($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }
}
