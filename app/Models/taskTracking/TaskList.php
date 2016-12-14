<?php

namespace App\Models\taskTracking;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $connection = 'taskTracking';
    protected $table = "taskList";
    public $keyType = 'string';
    public $timestamps = false;
}
