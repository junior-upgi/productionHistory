<?php
/**
 * CheckRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use DB;
use App\Service\Common;
use App\Models\productionHistory\ProductionDuty;
use App\Models\UPGWeb\Staff;
use App\Models\productionHistory\GlassRun;
use App\Models\productionHistory\GlassRunPlan;
use App\Models\productionHistory\AllGlassRun;

/**
 * Class DutyRepository
 * @package App\Repositories
 */
class DutyRepository extends BaseRepository
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
     * @var GlassRunPlan
     */
    public $plan;
    /**
     * @var AllGlassRun
     */
    public $allGlass;
    /**
     * @var ProductionDuty
     */
    public $duty;
    /**
     * @var Staff
     */
    public $staff;
    /**
     * @var ScheduleRepository
     */
    public $schedule;

    /**
     * DutyRepository constructor.
     * @param Common $common
     * @param ProductionDuty $duty
     * @param Staff $staff
     * @param GlassRun $run
     * @param GlassRunPlan $plan
     * @param AllGlassRun $allGlass
     * @param ScheduleRepository $schedule
     */
    public function __construct(
        Common $common,
        ProductionDuty $duty,
        Staff $staff,
        GlassRun $run,
        GlassRunPlan $plan,
        AllGlassRun $allGlass,
        ScheduleRepository $schedule
    ) {
        $this->common = $common;
        $this->run = $run;
        $this->plan = $plan;
        $this->allGlass = $allGlass;
        $this->duty = $duty;
        $this->staff = $staff;
        $this->schedule = $schedule;
    }

    /**
     * 取得排程資料
     *
     * @param $request
     * @return array
     */
    public function getSchedule($request)
    {
        return $this->schedule->getSchedule($request);
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
        return $this->schedule->getScheduleList($view, $request);
    }

    /**
     * 取得值班表清單
     *
     * @param $request
     * @return mixed
     */
    public function getDutyList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $dutyDateOp = 'like';
        $dutyDate = '%' . $request->input('dutyDate') . '%';
        if ($dutyDate != '%%') {
            $dutyDateOp = '=';
            $dutyDate = date('Y-m-d', strtotime($request->input('dutyDate')));
        }
        $scheduleList = $this->duty
            ->join('allGlassRun', function ($join) {
                $join->on('productionDuty.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionDuty.prd_no', 'allGlassRun.prd_no')
                    ->on('productionDuty.schedate', 'allGlassRun.schedate');
            })
            ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
            ->where('allGlassRun.PRDT_SNM', 'like', "%$snm%")
            ->where('productionDuty.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('productionDuty.dutyDate', $dutyDateOp, $dutyDate)
            ->orderBy('dutyDate', 'desc')->orderBy('shift')->orderBy('glassProdLineID')
            ->select('productionDuty.id', 'productionDuty.glassProdLineID', 'dutyDate', 
                'shift', 'UPGWeb.dbo.vStaffNode.name as staffName', 'allGlassRun.PRDT_SNM as snm', 
                'quantity', 'pack', 'productionDuty.efficiency', 'annealGrade', 'startShutdown', 'endShutdown');
        return $scheduleList;
    }

    /**
     * 取得員工資料
     *
     * @return mixed
     */
    public function getStaff()
    {
        return $this->staff->where('serving', 1);
    }

    /**
     * 取得值班表資料
     *
     * @param $id
     * @return null
     */
    public function getDuty($id)
    {
        return $this->duty
            ->join('allGlassRun', function ($join) {
                $join->on('productionDuty.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionDuty.prd_no', 'allGlassRun.prd_no')
                    ->on('productionDuty.schedate', 'allGlassRun.schedate');
            })
            ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
            ->where('productionDuty.id', $id)
            ->orderBy('dutyDate', 'shift', 'glassProdLineID')
            ->select('productionDuty.id', 'productionDuty.glassProdLineID', 'productionDuty.schedate', 'productionDuty.prd_no',
                'dutyDate', 'shift', 'UPGWeb.dbo.vStaffNode.ID as staffID', 'UPGWeb.dbo.vStaffNode.name as staffName',
                'allGlassRun.PRDT_SNM as snm', 'quantity', 'pack', 'productionDuty.efficiency', 'annealGrade',
                'startShutdown', 'endShutdown', 'jobChange', 'speedChange', 'improve');
    }

    /**
     * 儲存值班珴資料
     *
     * @param $input
     * @return mixed
     */
    public function saveDuty($input)
    {
        return $this->save($this->duty, $input);
    }
}