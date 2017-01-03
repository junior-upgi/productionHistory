<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

class DefectItem extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "defectItem";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
}