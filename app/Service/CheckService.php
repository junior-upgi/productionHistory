<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/20
 * Time: 下午1:22
 */

namespace App\Service;

use App\Repositories\CheckRepository;
use App\Repositories\BaseDataRepository;
use App\Repositories\ScheduleRepository;

/**
 * Class CheckService
 * @package App\Service
 */
class CheckService
{
    /**
     * @var CheckRepository
     */
    public $check;
    /**
     * @var BaseDataRepository
     */
    public $base;
    /**
     * @var ScheduleRepository
     */
    public $schedule;

    /**
     * CheckService constructor.
     * @param CheckRepository $check
     * @param BaseDataRepository $base
     * @param ScheduleRepository $schedule
     */
    public function __construct(
        CheckRepository $check,
        BaseDataRepository $base,
        ScheduleRepository $schedule
    ){
        $this->check = $check;
        $this->base = $base;
        $this->schedule = $schedule;
    }

    public function getCheckList()
    {
        return $this->check->getCheckList();
    }

    /**
     * 根據傳入的資料，判斷是以品號或排程日搜尋
     *
     * @param $input
     * @return mixed
     */
    public function searchCheck($input)
    {
        if (isset($input['snm'])) {
            $prd_no = $this->base->getPrdNo($input['snm']);
            return $this->check->searchCheckByPrdNo($prd_no);
        }
        if (isset($input['start']) || isset($input['end'])) {
            return $this->check->searchCheckBySchedate($input['start'], $input['end']);
        }
    }

    public function getScheduleList($request)
    {
        $view = 'run';
        return $this->schedule->getScheduleList($view, $request);
    }

    public function insertCheck($input)
    {
        return $this->check->insertCheck($input);
    }
}