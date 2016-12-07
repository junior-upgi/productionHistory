<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionHistory extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "productionHistory";
    public $keyType = 'string';
}