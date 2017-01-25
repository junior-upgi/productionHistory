<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/25
 * Time: 上午8:36
 */

namespace App\Models\productionHistory;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionDefect extends Model
{
    use SoftDeletes;

    protected $connection = 'productionHistory';
    protected $table = "productionDefect";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'
    ];
    protected $softDelete = true;
    public $timestamps = true;
}