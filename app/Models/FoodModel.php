<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodModel extends Model
{
    protected $table = 'tb_food';
    protected $primaryKey = 'id_food';
    protected $allowedFields = [
        'id_restaurant',
        'food_name',
        'food_price',
        'food_quantity',
        'food_image',
        'food_desc',
        'food_category'
    ];

    // Mendapatkan semua makanan atau makanan berdasarkan ID restoran
    public function getFood($id_restaurant = false)
    {
        if ($id_restaurant == false) {
            return $this->findAll();
        }
        return $this->where(['id_restaurant' => $id_restaurant]);
    }

    // Mendapatkan makanan berdasarkan ID makanan
    public function getFoodId($id_food = false)
    {
        if ($id_food == false) {
            return $this->findAll();
        }
        return $this->where(['id_food' => $id_food])->first();
    }

    // Mendapatkan makanan berdasarkan kategori
    public function getFoodByCategory($category, $id_restaurant)
    {
        return $this->where([
            'food_category' => $category,
            'id_restaurant' => $id_restaurant
        ])->findAll();
    }

    // Mendapatkan harga makanan terendah di restoran tertentu
    public function getMinFoodPrice($id_restaurant)
    {
        $result = $this->selectMin('food_price')
            ->where('id_restaurant', $id_restaurant)
            ->get()
            ->getRow();

        return $result ? (float) $result->food_price : null;
    }

    // Mendapatkan harga makanan tertinggi di restoran tertentu
    public function getMaxFoodPrice($id_restaurant)
    {
        $result = $this->selectMax('food_price')
            ->where('id_restaurant', $id_restaurant)
            ->get()
            ->getRow();

        return $result ? (float) $result->food_price : null;
    }
}
