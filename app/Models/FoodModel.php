<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodModel extends Model
{
    protected $table      = 'tb_food';
    protected $primaryKey = 'id_food';
    protected $allowedFields = ['id_restaurant', 'food_name', 'food_price', 'food_quantity', 'food_image', 'food_desc'];

    public function getFood($id_restaurant = false)
    {
        if ($id_restaurant == false) {
            return $this->findAll();
        }
        return $this->where(['id_restaurant' => $id_restaurant])->paginate(10, 'restaurants');
    }

    public function getFoodId($id_food = false)
    {
        if ($id_food == false) {
            return $this->findAll();
        }
        return $this->where(['id_food' => $id_food])->first();
    }

    public function getMinFoodPrice($id_restaurant)
    {
        $result = $this->selectMin('food_price')
            ->where('id_restaurant', $id_restaurant)
            ->get()
            ->getRow(); // Menggunakan getRow() daripada getRowArray()

        // Mengambil nilai minimum dari hasil query
        $minPrice = isset($result->food_price) ? (float) $result->food_price : null;

        return $minPrice;
    }

    public function getMaxFoodPrice($id_restaurant)
    {
        $result = $this->selectMax('food_price')
            ->where('id_restaurant', $id_restaurant)
            ->get()
            ->getRow(); // Menggunakan getRow() daripada getRowArray()

        // Mengambil nilai maksimum dari hasil query
        $maxPrice = isset($result->food_price) ? (float) $result->food_price : null;

        return $maxPrice;
    }
}
