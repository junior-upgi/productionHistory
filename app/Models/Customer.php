<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{   
    protected $connection = 'UPGWeb';
    protected $table = "vCustomer";
    public $keyType = 'string';
}