<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Service\Common;
use App\Repositories\TaskRepository;

/**
 * Class ServiceController
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * @var Common
     */
    public $common;
    /**
     * @var TaskRepository
     */
    public $task;

    /**
     * ServiceController constructor.
     * @param Common $common
     * @param TaskRepository $task
     */
    public function __construct(Common $common, TaskRepository $task)
    {
        $this->common = $common;
        $this->task = $task;
    }

    /**
     * @param $id
     * @return string
     */
    public function getPic($id)
    {
        $base64 = $this->common->getFile($id);
        return $base64;
    }

    /**
     * @param $id
     * @return string
     */
    public function blankPic($id)
    {
        $base64 = $this->common->getFile($id);
        $img = '<image src=\'' . $base64 . '\' class=\"carousel-inner img-responsive img-rounded\" />';
        return $img;
    }

    /**
     * @return mixed
     */
    public function deleteTask()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->task->deleteTask($id);
        return $result;
    }

    /**
     * @return mixed
     */
    public function saveTask()
    {
        $input = request()->input();
        $result = $this->task->saveTask($input);
        return $result;
    }
}
