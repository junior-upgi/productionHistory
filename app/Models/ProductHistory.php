<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{   
    protected $connection = 'SQL_PH';
    protected $table = "vProductHistory";
}