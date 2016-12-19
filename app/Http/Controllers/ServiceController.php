<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Service\Common;

class ServiceController extends Controller
{
    public $common;

    public function __construct(Common $common)
    {
        $this->common = $common;
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
}
