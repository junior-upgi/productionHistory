<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{   
    protected $connection = 'MSSQL';
    protected $table = "issue";
}