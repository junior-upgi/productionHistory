<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Models\taskTracking\TaskList;

//
class TaskRepository extends BaseRepository
{
    public $task;
    //
    public function __construct(TaskList $task) {
        $this->task = $task;
    }

    //
    public function saveTask($input)
    {
        $table = $this->task;
        $result = $this->save($table, $input);
        return $result;
    }

    //
    public function deleteTask($id)
    {
        $table = $this->task;
        $result = $this->delete($table, $id);
        return $result;
    }
}