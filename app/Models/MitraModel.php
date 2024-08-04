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

    public function getRestoWithMitra($perPage, $currentPage, $search = null)
    {
        $builder = $this->table('tb_mitra') // Assuming 'tb_mitra' is the main table
            ->join('tb_restaurant', 'tb_restaurant.user_email_mitra = tb_mitra.user_email_mitra')
            ->select('tb_mitra.*, tb_restaurant.*');

        if ($search) {
            $builder->groupStart()
                ->like('tb_mitra.user_phone_mitra', $search)
                ->orLike('tb_mitra.user_email_mitra', $search)
                ->groupEnd();
        }

        $builder->limit($perPage, ($currentPage - 1) * $perPage);

        // Get total count of results
        $total = $builder->countAllResults(false); // false to avoid querying twice

        // Fetch results
        $result = $builder->get()->getResultArray();

        // Set pager
        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($currentPage, $perPage, $total, 'pager_bootstrap');

        return ['data' => $result, 'pager' => $pagerLinks];
    }

    public function getMitra($id_mitra = false)
    {
        if ($id_mitra == false) {
            return $this->findAll();
        }
        return $this->where(['id_mitra' => $id_mitra])->paginate();
    }
}
