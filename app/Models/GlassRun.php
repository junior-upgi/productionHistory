<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassRun extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "glassRun";
}