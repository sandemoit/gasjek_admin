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

    public function getRestoWithMitra($perPage, $currentPage)
    {
        $builder = $this->table($this->table)
            ->join('tb_restaurant', 'tb_restaurant.user_email_mitra = tb_mitra.user_email_mitra')
            ->select('tb_mitra.*, tb_restaurant.*');

        $builder->limit($perPage, ($currentPage - 1) * $perPage);

        // Menggunakan paginate dari Model
        $result = $builder->get()->getResultArray();

        // Set pager
        $this->pager = \Config\Services::pager();
        $total = $builder->countAllResults(false); // False agar tidak mengulang query

        // Dapatkan pager
        $pager = $this->pager->makeLinks($currentPage, $perPage, $total, 'pager_bootstrap');

        return ['data' => $result, 'pager' => $pager];
    }

    public function getMitra($id_mitra = false)
    {
        if ($id_mitra == false) {
            return $this->findAll();
        }
        return $this->where(['id_mitra' => $id_mitra])->paginate();
    }
}
