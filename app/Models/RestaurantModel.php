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

    public function getRestaurant($id_restaurant = false, $fcm_token = null, $user_phone_mitra = null)
    {
        $this->select('id_restaurant, restaurant_name, restaurant_location, latitude_restaurant, longitude_restaurant, open_restaurant, close_restaurant, is_open, is_active, restaurant_image, user_email_mitra, restaurant_rating, id_harikerja',);

        if ($id_restaurant == false) {
            $result = $this->findAll();
        } else {
            $result = $this->where(['id_restaurant' => $id_restaurant])->first();
        }

        // Tambahkan fcm_token ke hasil array jika $result adalah array
        if ($fcm_token !== null && $user_phone_mitra !== null && is_array($result)) {
            $result['fcm_token'] = $fcm_token;
            $result['user_phone_mitra'] = $user_phone_mitra;
        }

        return $result;
    }

    public function searchRestaurant($search_restaurant = false)
    {
        $this->select('id_restaurant, restaurant_name, restaurant_location, latitude_restaurant, longitude_restaurant, open_restaurant, close_restaurant, is_open, is_active, restaurant_image, user_email_mitra, restaurant_rating, id_harikerja');

        if ($search_restaurant == false) {
            return $this->findAll();
        }
        return $this->orLike(['restaurant_name' => $search_restaurant])->paginate(10, 'restaurants');
    }

    public function filterRestaurant($filter = false)
    {
        $this->select('id_restaurant, restaurant_name, restaurant_location, latitude_restaurant, longitude_restaurant, open_restaurant, close_restaurant, is_open, is_active, restaurant_image, user_email_mitra, restaurant_rating, id_harikerja');

        if ($filter === null) {
            return $this->findAll();
        } else {
            return $this->orderBy($filter, 'DESC')->paginate(10, 'restaurants');
        }
    }

    public function searchAndFilterRestaurant(?string $search_restaurant = null, ?string $filter = null)
    {
        $this->select('id_restaurant, restaurant_name, restaurant_location, latitude_restaurant, longitude_restaurant, open_restaurant, close_restaurant, is_open, is_active, restaurant_image, user_email_mitra, restaurant_rating, id_harikerja');

        // Jika tidak ada parameter yang diberikan, ambil semua data
        if ($search_restaurant === null && $filter === null) {
            return $this->findAll();
        }

        // Jika hanya parameter pencarian yang diberikan
        if ($search_restaurant !== null && $filter === null) {
            return $this->like('restaurant_name', $search_restaurant)->paginate(10, 'restaurants');
        }

        // Jika hanya parameter filter yang diberikan
        if ($search_restaurant === null && $filter !== null) {
            if ($filter === "All") {
                return $this->paginate(10, 'restaurants');
            } else {
                return $this->orderBy($filter, 'DESC')->paginate(10, 'restaurants');
            }
        }

        // Jika parameter pencarian dan filter keduanya diberikan
        if ($search_restaurant !== null && $filter !== null) {
            if ($filter === "All") {
                return $this->like('restaurant_name', $search_restaurant)->paginate(10, 'restaurants');
            } else {
                return $this->like('restaurant_name', $search_restaurant)
                    ->orderBy($filter, 'DESC')
                    ->paginate(10, 'restaurants');
            }
        }
    }

    public function getNearestRestaurants($latitude, $longitude)
    {
        $this->select('id_restaurant, restaurant_name, restaurant_location, latitude_restaurant, longitude_restaurant, open_restaurant, close_restaurant, is_open, is_active, restaurant_image, user_email_mitra, restaurant_rating, id_harikerja');
        $this->select("( 6371 * acos( cos( radians($latitude) ) 
                * cos( radians( latitude_restaurant ) ) 
                * cos( radians( longitude_restaurant ) - radians($longitude) ) 
                + sin( radians($latitude) ) 
                * sin( radians( latitude_restaurant ) ) ) ) AS distance");
        $this->having('distance < 10');
        $this->orderBy('distance', 'ASC');

        return $this->findAll();
    }
}
