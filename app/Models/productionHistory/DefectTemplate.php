<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

class DefectTemplate extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "defecttemplate";
    public $keyType = 'string';
}