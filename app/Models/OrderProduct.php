<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{   
    protected $connection = 'SQL_PH';
    protected $table = "orderProduct";
}
