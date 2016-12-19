<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

class OldSchedule extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "productionHistory.dbo.tbmkno";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;
}