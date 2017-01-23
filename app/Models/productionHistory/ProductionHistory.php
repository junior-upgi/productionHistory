<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;
use App\Models\UPGWeb\Customer;
use App\Models\productionHistory\OldSchedule;
use App\Models\productionHistory\AllGlassRun;

/**
 * Class ProductionHistory
 * @package App\Models\productionHistory
 */
class ProductionHistory extends Model
{
    protected $connection = 'productionHistory';
    protected $table = "productionHistory";
    public $keyType = 'string';
}