<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

//
class AllGlassRun extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "allGlassRun";
    public $keyType = 'string';
}