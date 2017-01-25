<?php
/**
 * ScheduleRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/25
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use DB;

use App\Service\Common;
use App\Models\productionHistory\GlassRun;
use App\Models\productionHistory\GlassRunDetail;
use App\Models\productionHistory\GlassRunPlan;
use App\Models\productionHistory\GlassRunPlanDetail;
use App\Models\productionHistory\AllGlassRun;

/**
 * Class ScheduleRepository
 * @package App\Repositories
 */
class ScheduleRepository extends BaseRepository
{
    /**
     * @var Common
     */
    public $common;
    /**
     * @var GlassRun
     */
    public $run;
    /**
     * @var GlassRunDetail
     */
    public $runDetail;
    /**
     * @var GlassRunPlan
     */
    public $plan;
    /**
     * @var GlassRunPlanDetail
     */
    public $planDetail;
    /**
     * @var AllGlassRun
     */
    public $allGlass;

    /**
     * ScheduleRepository constructor.
     * @param Common $common
     * @param GlassRun $run
     * @param GlassRunDetail $runDetail
     * @param GlassRunPlan $plan
     * @param GlassRunPlanDetail $planDetail
     * @param AllGlassRun $allGlass
     */
    public function __construct(
        Common $common,
        GlassRun $run,
        GlassRunDetail $runDetail,
        GlassRunPlan $plan,
        GlassRunPlanDetail $planDetail,
        AllGlassRun $allGlass
    ) {
        $this->common = $common;
        $this->run = $run;
        $this->plan = $plan;
        $this->allGlass = $allGlass;
        $this->runDetail = $runDetail;
        $this->planDetail = $planDetail;
    }

    /**
     * 取得排程資料
     *
     * @param $request
     * @return array
     */
    public function getSchedule($request)
    {
        $where['prd_no'] = $request->input('prd_no');
        $where['glassProdLineID'] = $request->input('glassProdLineID');
        $where['schedate'] = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getScheduleViewSelect($this->getTable($view), $where);
        isset($data) ? $set = ['success' => true, 'data' => $data] : $set = ['success' => false];
        return $set;
    }

    /**
     * 回傳排程資料
     *
     * @param $table
     * @param $where
     * @return mixed
     */
    private function getScheduleViewSelect($table, $where)
    {
        return $table
            ->where('prd_no', $where['prd_no'])
            ->where('glassProdLineID', $where['glassProdLineID'])
            ->where('schedate', $where['schedate'])
            ->select('prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
    }

    /**
     * 取得排程顧客資料
     *
     * @param $request
     * @return string
     */
    public function getScheduleCustomer($request)
    {
        $date = $this->formatSchedate(request()->input('schedate'));
        $where['prd_no'] = $request->input('prd_no');
        $where['glassProdLineID'] = $request->input('glassProdLineID');
        $where['schedate'] = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        return $this->getSheduleCustomer($this->getTable($view . 'Detail'), $where);
    }

    /**
     * 取得排程清單
     *
     * @param $view
     * @param $request
     * @return null
     */
    public function getScheduleList($view, $request)
    {
        $date = $this->formatSchedate(request()->input('schedate'));
        $where['snm'] = $request->input('snm');
        $where['glassProdLineID'] = $request->input('glassProdLineID');
        $where['op'] = $date['op'];
        $where['date'] = $date['date'];
        if ($view == 'plan') {
            return $this->getScheduleWithoutSample($this->getTable($view), $where);
        }
        return $this->getScheduleWithSample($this->getTable($view), $where);
    }

    /**
     * 取得排程顧客資料
     *
     * @param $table
     * @param $where
     * @return string
     */
    private function getSheduleCustomer($table, $where)
    {
        $data = $table
            ->where('prd_no', $where['prd_no'])
            ->where('glassProdLineID', $where['glassProdLineID'])
            ->where('schedate', $where['schedate'])
            ->select('CUS_SNM')->get()->toArray();
        $data = array_collapse($data);
        $str = implode(' , ', $data);
        return $str;

    }

    /**
     * 調整排程日期格式
     *
     * @param $date
     * @return mixed
     */
    private function formatSchedate($date)
    {
        $set['op'] = 'like';
        $set['date'] = '%' . $date . '%';
        if ($set['date'] != '%%') {
            $set['op'] = '=';
            $set['date'] = date('Y-m-d', strtotime($date));
        }
        return $set;
    }

    /**
     * 取得排程資料，含sampling欄位
     *
     * @param $table
     * @param $where
     * @return
     * @internal param $view
     */
    private function getScheduleWithSample($table, $where)
    {
        return $table
            ->where('PRDT_SNM', 'like', '%' . $where['snm'] . '%')
            ->where('glassProdLineID', 'like', '%' . $where['glassProdLineID'] . '%')
            ->where('schedate', $where['op'], $where['date'])
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('PRDT_SNM')
            ->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID', 'sampling');
    }

    /**
     * 取得排程資料，不含sampling欄位
     *
     * @param $table
     * @param $where
     * @return mixed
     */
    private function getScheduleWithoutSample($table, $where)
    {
        return $table
            ->where('PRDT_SNM', 'like', '%' . $where['snm'] . '%')
            ->where('glassProdLineID', 'like', '%' . $where['glassProdLineID'] . '%')
            ->where('schedate', $where['op'], $where['date'])
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('PRDT_SNM')
            ->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID');
    }

    /**
     * 回傳資料表
     *
     * @param $view
     * @return AllGlassRun|GlassRun|GlassRunDetail|GlassRunPlan|GlassRunPlanDetail|null
     */
    public function getTable($view)
    {
        switch ($view) {
            case 'plan':
                return $this->plan;
                break;
            case 'run':
                return $this->run;
                break;
            case 'allGlass':
                return $this->allGlass;
                break;
            case 'planDetail':
                return $this->planDetail;
                break;
            case 'runDetail':
                return $this->runDetail;
                break;
            default:
                return null;
        }
    }
}