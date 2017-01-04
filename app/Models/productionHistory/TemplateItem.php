<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

class templateItem extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "templateItem";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
}