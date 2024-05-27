<?php

namespace App\Models;

use CodeIgniter\Model;

class FeatureModel extends Model
{
    protected $table      = 'tb_feature';
    protected $primaryKey = 'id_fitur';
    protected $allowedFields = ['feature_name', 'feature_description','feature_status','feature_image'];
}
?>