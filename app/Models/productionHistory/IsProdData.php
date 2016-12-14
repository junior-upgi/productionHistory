<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;

class IsProdData extends Model
{   
    protected $connection = 'productionHistory';
    protected $table = "isProdData";
}