<?php

namespace App\Http\Controllers;

use App\Service\CheckService;

/**
 * Class CheckController
 * @package App\Http\Controllers
 */
class CheckController extends Controller
{
    /**
     * @var CheckService
     */
    public $service;

    /**
     * CheckController constructor.
     *
     * @param CheckService $service
     * @internal param CheckRepository $check
     */
    public function __construct(CheckService $service)
    {
        $this->service = $service;
    }

    /**
     * 取得檢查表清單
     *
     * @return mixed
     */
    public function getCheckList()
    {
        return $this->service->getCheckList();
    }

    /**
     * 搜尋檢查表清單
     *
     * @return mixed
     */
    public function searchCheck()
    {
        return $this->service->searchCheck(request()->input());
    }

    /**
     *  取得排程清單
     *
     * @return mixed
     */
    public function getScheduleList()
    {
        return $this->service->getScheduleList(request());
    }

    public function insertCheck()
    {
        return $this->service->insertCheck(request()->input());
    }
}
