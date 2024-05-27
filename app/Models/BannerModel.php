<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table      = 'tb_banner';
    protected $primaryKey = 'id_banner';

    protected $allowedFields = ['position_banner', 'url_image_banner'];
}
?>