<?php

namespace App\Http\Controllers;

use App\Repositories\CheckRepository;
use App\Service\CheckService;

/**
 * Class CheckController
 * @package App\Http\Controllers
 */
class CheckController extends Controller
{
    /**
     * @var CheckRepository
     */
    public $check;

    /**
     * @var CheckService
     */
    public $service;

    /**
     * CheckController constructor.
     *
     * @param CheckRepository $check
     * @param CheckService $service
     */
    public function __construct(CheckRepository $check, CheckService $service)
    {
        $this->check = $check;
        $this->service = $service;
    }

    /**
     * 取得檢查表清單
     *
     * @return mixed
     */
    public function getCheckList()
    {
        return $this->check->getCheckList();
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

}
