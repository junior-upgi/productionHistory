<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassRunPlanDetail extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "glassRunPlanDetail";
}