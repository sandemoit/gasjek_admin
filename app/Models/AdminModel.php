<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'tb_admin';
    protected $primaryKey = 'id_admin';

    protected $allowedFields = ['username_admin', 'email_admin','password_admin'];


    public function getAdmin($id_admin = false)
    {
        if ($id_admin == false) {
            return $this->findAll();
        }
        return $this->where(['id_admin' => $id_admin])->first();
    }
}
?>