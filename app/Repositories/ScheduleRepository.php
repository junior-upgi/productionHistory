<?php
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
     * @param $request
     * @return string
     */
    public function getScheduleCustomer($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'plan':
                $table = $this->planDetail;
                break;
            case 'run':
                $table = $this->runDetail;
                break;
            default:
                return '';
        } 
        $data = $table
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $runProdLineID)
            ->where('schedate', $schedate)
            ->select('CUS_SNM')->get()->toArray();
        $data = array_collapse($data);
        $str = implode(' , ', $data);
        return $str;
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
}