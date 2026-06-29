<?php

namespace App\Models;

use CodeIgniter\Model;

class KulinerTagModel extends Model
{
    protected $table            = 'kuliner_tag';
    // pivot table doesn't have auto increment id
    protected $primaryKey       = null; 
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kuliner_id', 'tag_id'];
}
