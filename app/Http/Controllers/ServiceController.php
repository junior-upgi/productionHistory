<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Service\Common;
use App\Repositories\TaskRepository;

//
class ServiceController extends Controller
{
    //
    public $common;
    //
    public $task;

    //
    public function __construct(Common $common, TaskRepository $task)
    {
        $this->common = $common;
        $this->task = $task;
    }

    //
    public function getPic($id)
    {
        $base64 = $this->common->getFile($id);
        return $base64;
    }

    //
    public function blankPic($id)
    {
        $base64 = $this->common->getFile($id);
        $img = '<image src=\'' . $base64 . '\' class=\"carousel-inner img-responsive img-rounded\" />';
        return $img;
    }

    //
    public function deleteTask()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->task->deleteTask($id);
        return $result;
    }

    //
    public function saveTask()
    {
        $input = request()->input();
        $result = $this->task->saveTask($input);
        return $result;
    }
}
