<?php

namespace App\Models\taskTracking;

use Illuminate\Database\Eloquent\Model;

//
class TaskListDetail extends Model
{
    protected $connection = 'taskTracking';
    protected $table = "taskListDetail";
    public $keyType = 'string';
}
