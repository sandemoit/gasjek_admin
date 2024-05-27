<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table      = 'tb_review';
    protected $primaryKey = 'id_review';

    protected $allowedFields = ['id_restaurant', 'id_user','rating','review_user','user_name'];


    public function getReview($id_restaurant = false)
    {
        if ($id_restaurant == false) {
            return $this->findAll();
        }
        return $this->where(['id_restaurant' => $id_restaurant])->paginate(10, 'reviews');
    }
}
?>