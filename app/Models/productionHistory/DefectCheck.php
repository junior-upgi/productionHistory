<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/19
 * Time: 下午4:44
 */

namespace App\Models\productionHistory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefectCheck extends Model
{

    use SoftDeletes;

    protected $connection = 'productionHistory';
    protected $table = "defectCheck";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'
    ];
    protected $softDelete = true;
    public $timestamps = true;
}