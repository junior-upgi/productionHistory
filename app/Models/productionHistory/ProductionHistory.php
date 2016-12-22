<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;
use App\Models\UPGWeb\Customer;
use App\Models\productionHistory\OldSchedule;
use App\Models\productionHistory\AllGlassRun;

class ProductionHistory extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "productionHistory";
    public $keyType = 'string';

    public function customer()
    {
        return $this->hasOne(Customer::class, 'ID', 'cus_no');
    }

    public function sampleSchedule()
    {
        return $this->whereHas(OldSchedule::class, function ($quest) {
            $quest->where('thmkno.shcedate', 'productionHistory.shcedate')
                ->where('thmkno.prd_no', 'productionHistory.prd_no')
                ->where('thmkno.glassProdLineID', 'productionHistory.glassProdLineID');
        });
    }
    
    public function schedule()
    {
        return $this->whereHas(AllGlassRun::class, function ($quest) {
            $quest->where('allGlassRun.shcedate', 'productionHistory.shcedate')
                ->where('allGlassRun.prd_no', 'productionHistory.prd_no')
                ->where('allGlassRun.glassProdLineID', 'productionHistory.glassProdLineID');
        });
    }
}