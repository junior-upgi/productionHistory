<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassRunDetail extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "glassRunDetail";
}