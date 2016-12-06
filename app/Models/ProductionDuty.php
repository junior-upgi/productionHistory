<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDuty extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "productionDuty";
    public $keyType = 'string';
}