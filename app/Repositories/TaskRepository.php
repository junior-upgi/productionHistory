<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Models\taskTracking\TaskList;

/**
 * Class TaskRepository
 * @package App\Repositories
 */
class TaskRepository extends BaseRepository
{
    /**
     * @var TaskList
     */
    public $task;

    /**
     * TaskRepository constructor.
     * @param TaskList $task
     */
    public function __construct(TaskList $task) {
        $this->task = $task;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function saveTask($input)
    {
        $table = $this->task;
        $result = $this->save($table, $input);
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteTask($id)
    {
        $table = $this->task;
        $result = $this->delete($table, $id);
        return $result;
    }
}