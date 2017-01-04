<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

class DefectTemplate extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "defectTemplate";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
}