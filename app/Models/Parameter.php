<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{   
    protected $connection = 'SQL_25';
    protected $table = "productionHistory.dbo.parameter";
}