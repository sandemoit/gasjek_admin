<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table = 'tb_mitra';
    protected $primaryKey = 'id_mitra';
    protected $allowedFields = [
        'user_phone_mitra',
        'user_email_mitra',
        'user_password_mitra',
        'status',
        'balance_mitra',
        'fcm_token',
        'date_register'
    ];
    protected $useTimestamps = false;

    public function getRestoWithMitra()
    {
        $builder = $this->db->table('tb_mitra');
        $builder->join('tb_restaurant', 'tb_restaurant.user_email_mitra = tb_mitra.user_email_mitra');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getMitra($id_mitra = false)
    {
        if ($id_mitra == false) {
            return $this->findAll();
        }
        return $this->where(['id_mitra' => $id_mitra])->paginate();
    }
}
