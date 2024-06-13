<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantModel extends Model
{
    protected $table = 'tb_restaurant';
    protected $primaryKey = 'id_restaurant';
    protected $allowedFields = [
        'restaurant_name',
        'restaurant_location',
        'open_restaurant',
        'close_restaurant',
        'latitude_restaurant',
        'longitude_restaurant',
        'restaurant_image',
        'restaurant_rating',
        'user_email_mitra',
        'is_open',
        'is_active',
        'id_harikerja'
    ];

    public function getRestaurant($id_restaurant = false)
    {
        if ($id_restaurant == false) {
            return $this->findAll();
        }
        return $this->where(['id_restaurant' => $id_restaurant])->first();
    }

    public function searchRestaurant($search_restaurant = false)
    {
        if ($search_restaurant == false) {
            return $this->findAll();
        }
        return $this->orLike(['restaurant_name' => $search_restaurant])->paginate(10, 'restaurants');
    }

    public function filterRestaurant(?string $search_restaurant = null, ?string $filter = null)
    {
        if ($search_restaurant === null && $filter === null) {
            return $this->findAll();
        } elseif ($filter === null) {
            return $this->orLike(['restaurant_name' => $search_restaurant])->paginate(10, 'restaurants');
        } elseif ($search_restaurant === null) {
            if ($filter === "All") {
                return $this->findAll();
            } else {
                return $this->orderBy($filter, 'DESC')->paginate(10, 'restaurants');
            }
        }
        return $this->like(['restaurant_name' => $search_restaurant])->orderBy($filter, 'DESC')->paginate(5, 'restaurants');
    }

    public function getNearestRestaurants($latitude, $longitude)
    {
        $sql = "SELECT *, 
                ( 6371 * acos( cos( radians($latitude) ) 
                * cos( radians( latitude_restaurant ) ) 
                * cos( radians( longitude_restaurant ) - radians($longitude) ) 
                + sin( radians($latitude) ) 
                * sin( radians( latitude_restaurant ) ) ) ) AS distance 
            FROM tb_restaurant 
            HAVING distance < 10 
            ORDER BY distance";

        // Jalankan query dan kembalikan hasilnya
        return $this->db->query($sql)->getResultArray();
    }
}
