<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllGlassRun extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "allGlassRun";
}