<?php
namespace App\Models\UPGWeb;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{   
    protected $connection = 'UPGWeb';
    protected $table = "vStaffNode";
    public $keyType = 'string';
}