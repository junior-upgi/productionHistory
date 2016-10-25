<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{   
    protected $connection = 'MSSQL';
    protected $table = "vProductHistory";
}