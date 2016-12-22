<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Service\Common;
use App\Repositories\ProductionRepository;

class ServiceController extends Controller
{
    public $common;
    public $production;

    public function __construct(Common $common, ProductionRepository $production)
    {
        $this->common = $common;
        $this->production = $production;
    }

    public function getPic($id)
    {
        $base64 = $this->common->getFile($id);
        return $base64;
    }

    public function blankPic($id)
    {
        $base64 = $this->common->getFile($id);
        $img = '<image src=\'' . $base64 . '\' class=\"carousel-inner img-responsive img-rounded\" />';
        return $img;
    }

    public function deleteTask()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->production->deleteTask($id);
        return $result;
    }

    public function saveTask()
    {
        $input = request()->input();
        $result = $this->production->saveTask($input);
        return $result;
    }
}
