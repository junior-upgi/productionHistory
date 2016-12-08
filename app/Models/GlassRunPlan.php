<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassRunPlan extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "glassRunPlan";
}