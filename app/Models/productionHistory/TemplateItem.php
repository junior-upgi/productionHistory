<?php
namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//
class templateItem extends Model
{   
    //
    use SoftDeletes;

    //
    protected $connection = 'productionHistory';
    protected $table = "templateItem";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'
    ];
    public $softDelete = true;
}