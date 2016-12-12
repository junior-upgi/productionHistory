<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "qualityControl";
    public $keyType = 'string';
}