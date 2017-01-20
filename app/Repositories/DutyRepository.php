<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

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
     * DutyRepository constructor.
     * @param Common $common
     * @param ProductionDuty $duty
     * @param Staff $staff
     * @param GlassRun $run
     * @param GlassRunPlan $plan
     * @param AllGlassRun $allGlass
     */
    public function __construct(
        Common $common,
        ProductionDuty $duty,
        Staff $staff,
        GlassRun $run,
        GlassRunPlan $plan,
        AllGlassRun $allGlass
    ) {
        $this->common = $common;
        $this->run = $run;
        $this->plan = $plan;
        $this->allGlass = $allGlass;
        $this->duty = $duty;
        $this->staff = $staff;
    }

    /**
     * @param $request
     * @return array
     */
    public function getSchedule($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'run':
                $data = $this->run
                    ->where('prd_no', $prd_no)
                    ->where('glassProdLineID', $runProdLineID)
                    ->where('schedate', $schedate)
                    ->select('id', 'prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
                break;
            
            case 'plan':
                $data = $this->plan
                    ->where('prd_no', $prd_no)
                    ->where('glassProdLineID', $runProdLineID)
                    ->where('schedate', $schedate)
                    ->select('prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
                break;
            
            case 'allGlass':
                $data = $this->allGlass
                    ->where('prd_no', $prd_no)
                    ->where('glassProdLineID', $runProdLineID)
                    ->where('schedate', $schedate)
                    ->select('id', 'prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
                break;
        }
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    /**
     * @param $view
     * @param $request
     * @return null
     */
    public function getScheduleList($view, $request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $schedateOp = 'like';
        $schedate = '%' . $request->input('schedate') . '%';
        if ($schedate != '%%') {
            $schedateOp = '=';
            $schedate = date('Y-m-d', strtotime($request->input('schedate')));
        }
        switch ($view) {
            case 'plan':
                $table = $this->plan;
                break;
            case 'run':
                $table = $this->run;
                break;
            case 'allGlass':
                $table = $this->allGlass;
                break;
            default:
                return null;
        }
        $list = $table
            ->where('PRDT_SNM', 'like', "%$snm%")
            ->where('glassProdLineID', 'like', "%$runProdLineID%")
            ->where('schedate', $schedateOp, $schedate)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('PRDT_SNM')
            ->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID');
        if ($view == 'run') {
            $list = $list->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID', 'sampling');
        }
        return $list;
    }

    /**
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
        $schedule = $this->duty;
        $scheduleList = $schedule
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
        $a = $scheduleList->get();
        return $scheduleList;
    }

    /**
     * @return mixed
     */
    public function getStaff()
    {
        $list = $this->staff->where('serving', 1);
        return $list;
    }

    /**
     * @param $id
     * @return null
     */
    public function getDuty($id)
    {
        $data = $this->duty->where('productionDuty.id', $id);
        if ($data->exists()) {
            $data = $data
                ->join('allGlassRun', function ($join) {
                    $join->on('productionDuty.glassProdLineID', 'allGlassRun.glassProdLineID')
                        ->on('productionDuty.prd_no', 'allGlassRun.prd_no')
                        ->on('productionDuty.schedate', 'allGlassRun.schedate');
                })
                ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
                ->orderBy('dutyDate', 'shift', 'glassProdLineID')
                ->select('productionDuty.id', 'productionDuty.glassProdLineID', 'productionDuty.schedate', 'productionDuty.prd_no', 
                    'dutyDate', 'shift', 'UPGWeb.dbo.vStaffNode.ID as staffID', 'UPGWeb.dbo.vStaffNode.name as staffName', 
                    'allGlassRun.PRDT_SNM as snm', 'quantity', 'pack', 'productionDuty.efficiency', 'annealGrade', 
                    'startShutdown', 'endShutdown', 'jobChange', 'speedChange', 'improve');
            return $data;
        }
        return null;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function saveDuty($input)
    {
        $table = $this->duty;
        $result = $this->save($table, $input);
        return $result;
    }
}