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
use Illuminate\Support\Facades\DB;

/**
 * Class CheckService
 * @package App\Service
 */
class CheckService
{
    use DataFormatService;

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

    /**
     * 取得檢查表清單
     *
     * @return \App\Models\productionHistory\DefectCheck
     */
    public function getCheckList()
    {
        return $this->check->getCheckList()->get();
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
            return $this->check->searchCheckByPrdNo($this->base->getPrdNo($input['snm']))->get();
        }
        if (isset($input['start']) || isset($input['end'])) {
            return $this->check->searchCheckBySchedate($input['start'], $input['end'])->get();
        }
        return null;
    }

    /**
     * 以ID取得檢查表資料
     *
     * @param $id
     * @return mixed
     */
    public function getCheck($id)
    {
        $data = $this->check->getCheck($id)->first();
        $data['customer'] = $this->schedule->getScheduleCustomerByParams('run', $data['prd_no'], $data['glassProdLineID'], $data['schedate']);
        return $data;
    }

    /**
     * 取得排程清單，已執行完畢之排程
     *
     * @param $request
     * @return null
     */
    public function getScheduleList($request)
    {
        return $this->schedule->getScheduleList('run', $request)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('defectCheck')
                    ->whereRaw('defectCheck.schedate = glassRun.schedate')
                    ->whereRaw('defectCheck.glassProdLineID = glassRun.glassProdLineID')
                    ->whereRaw('defectCheck.prd_no = glassRun.prd_no')
                    ->whereRaw('defectCheck.deleted_at = null');
            })->get();
    }

    /**
     * 取得排程客戶資料
     *
     * @param $request
     * @return string
     * @internal param $input
     */
    public function getScheduleCustomer($request)
    {
        return $this->schedule->getScheduleCustomer($request);
    }

    /**
     * 新增檢查表
     *
     * @param $input
     * @return mixed
     */
    public function insertCheck($input)
    {
        $params = array_except($input, ['_token']);
        $params = $this->insertCheckPrework($params);
        $params = $this->setCheckID($params);
        return $this->check->insertCheck($this->toBig5($params));
    }

    /**
     * @param $params
     * @return mixed
     */
    private function insertCheckPrework($params)
    {
        if (isset($params['decoration'])) {
            $params['decoration'] = implode(',', $params['decoration']);
        }
        return $params;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function setCheckID($params)
    {
        if (strlen($params['id']) != 36) {
            $params['id'] = $this->getNewGUID();
        }
        return $params;
    }

    /**
     * 更新檢查表
     *
     * @param $input
     * @return mixed
     */
    public function updateCheck($input)
    {
        $id = $input['id'];
        $params = array_except($input, ['id', '_token']);
        $params = $this->insertCheckPrework($params);
        return $this->check->updateCheck($id, $this->toBig5($params));
    }

    /**
     * 刪除檢查表
     *
     * @param $id
     * @return array
     */
    public function deleteCheck($id)
    {
        return $this->check->deleteCheck($id);
    }
}