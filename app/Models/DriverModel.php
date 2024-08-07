<?php

namespace App\Models;

use CodeIgniter\Model;

class DriverModel extends Model
{
    protected $table      = 'tb_driver';
    protected $primaryKey      = 'id_driver';


    protected $allowedFields = ['balance_rider', 'is_limited', 'location', 'date_register', 'username_rider', 'phone_rider', 'email_rider', 'password_rider', 'image_rider', 'type_vehicle', 'police_number', 'fcm_token', 'is_active', 'vehicle_name', 'is_status', 'rider_latitude', 'rider_longitude', 'rating_driver'];

    public function getDriver($token = false)
    {
        if ($token == false) {
            return $this->findAll();
        }
        return $this->where(['id_driver' => $token])->first();
    }

    public function getPoliceNumber($police_number = false)
    {
        if ($police_number == false) {
            return $this->findAll();
        }
        return $this->where(['police_number' => $police_number])->first();
    }

    public function getStatus($is_status = false)
    {
        if ($is_status == false) {
            return $this->findAll();
        }

        return $this->where(['is_active' => $is_status]);
    }

    public function getDriverEmail($email_driver = false)
    {
        if ($email_driver == false) {
            return $this->findAll();
        }
        return $this->where(['email_rider' => $email_driver])->first();
    }

    public function updateLocation($id_driver, $latitude, $longitude)
    {
        return $this->set(['rider_latitude' => $latitude, 'rider_longitude' => $longitude])->where(['id_driver' => $id_driver])->update();
    }

    public function getDriverByPoliceNumber($police_number)
    {
        return $this->where(['police_number' => $police_number])->first();
    }

    public function checkLimited($is_limited)
    {
        return $this->where(['is_limited' => $is_limited])->first();
    }
}
