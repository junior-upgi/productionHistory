<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormLink extends Model
{   
    protected $connection = 'MSSQL';
    protected $table = "formLink";
}