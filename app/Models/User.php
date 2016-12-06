<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{   
    protected $connection = 'upgiSystem';
    protected $table = "user";
    public $keyType = 'string';
}