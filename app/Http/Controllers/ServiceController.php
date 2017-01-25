<?php

namespace App\Http\Controllers;

use App\Repositories\BaseDataRepository;
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

    public $base;

    /**
     * ServiceController constructor.
     * @param Common $common
     * @param TaskRepository $task
     * @param BaseDataRepository $base
     */
    public function __construct(
        Common $common,
        TaskRepository $task,
        BaseDataRepository $base
    ) {
        $this->common = $common;
        $this->task = $task;
        $this->base = $base;
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

    /**
     * @return \App\Models\UPGWeb\Customer
     */
    public function getCustomer()
    {
        return $this->base->getCustomer();
    }


}
