<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use DB;

use App\Models\productionHistory\ProductionDuty;
use App\Models\productionHistory\ProductionHistory;
use App\Models\UPGWeb\Customer;
use App\Models\Z_DB_U105\Schedule;
use App\Models\UPGWeb\Staff;
use App\Models\productionHistory\GlassRun;
use App\Models\productionHistory\GlassRunDetail;
use App\Models\productionHistory\GlassRunPlan;
use App\Models\productionHistory\GlassRunPlanDetail;
use App\Models\productionHistory\AllGlassRun;
use App\Models\productionHistory\QualityControl;
use App\Models\productionHistory\IsProdData;
use App\Models\productionHistory\OldSchedule;
use App\Models\UPGWeb\Glass;
use App\Models\taskTracking\TaskList;
use App\Models\taskTracking\TaskListDetail;

class ProductionRepository extends BaseRepository
{
    public $duty;
    public $histosy;
    public $customer;
    public $schedule;
    public $staff;
    public $run;
    public $runDetail;
    public $plan;
    public $planDetail;
    public $allGlass;
    public $qc;
    public $prod;
    public $glass;
    public $task;
    public $taskDetail;
    public $oldSchedule;

    public function __construct(
        ProductionDuty $duty,
        Schedule $schedule,
        Staff $staff,
        ProductionHistory $history,
        Customer $customer,
        GlassRun $run,
        GlassRunDetail $runDetail,
        GlassRunPlan $plan,
        GlassRunPlanDetail $planDetail,
        AllGlassRun $allGlass,
        QualityControl $qc,
        IsProdData $prod,
        Glass $glass,
        TaskList $task,
        TaskListDetail $taskDetail,
        OldSchedule $oldSchedule
    ) {
        parent::__construct();
        $this->duty = $duty;
        $this->schedule = $schedule;
        $this->staff = $staff;
        $this->history = $history;
        $this->customer = $customer;
        $this->run = $run;
        $this->runDetail = $runDetail;
        $this->plan = $plan;
        $this->planDetail = $planDetail;
        $this->allGlass = $allGlass;
        $this->qc = $qc;
        $this->prod = $prod;
        $this->glass = $glass;
        $this->task = $task;
        $this->taskDetail = $taskDetail;
        $this->oldSchedule = $oldSchedule;
    }

    public function getSchedule($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view)
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $runProdLineID)
            ->where('schedate', $schedate)
            ->select('prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    public function getScheduleCustomer($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view . 'Detail')
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $runProdLineID)
            ->where('schedate', $schedate)
            ->select('CUS_SNM')->get()->toArray();
        $data = array_collapse($data);
        $str = implode(' , ', $data);
        return $str;
    }

    public function getQCDefect($prd_no)
    {
        $table = $this->getTable('history');
        $defect = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('defect')->first();
        return $defect['defect'];
    }

    public function getQCPackRate($prd_no)
    {
        $table = $this->getTable('history');
        $packRate = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('efficiency')->first();
        return $packRate['efficiency'];
    }

    public function getQCNote($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view . 'Detail')
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $runProdLineID)
            ->where('schedate', $schedate)
            ->groupBy('SPC_NAME')
            ->select('SPC_NAME', DB::raw('SUM(QTY) as QTY'))
            ->get()->toArray();
        $array = [];
        $str = '';
        foreach ($data as $d) {
            array_push($array, $d['SPC_NAME'] . number_format($d['QTY']));
        }
        $str = implode(' ', $array);
        return $str;
    }

    public function getQCDecoration($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view . 'Detail')
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $runProdLineID)
            ->where('schedate', $schedate)
            ->groupBy('SPC_NAME')
            ->select('SPC_NAME')
            ->get()->toArray();
        $data = array_collapse($data);
        $str = implode(',', $data);
        return $str;
    }
    
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
        $table = $this->getTable($view);
        $list = $table
            ->where('PRDT_SNM', 'like', "%$snm%")
            ->where('glassProdLineID', 'like', "%$runProdLineID%")
            ->where('schedate', $schedateOp, $schedate)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('PRDT_SNM')
            ->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID');
        if ($view == 'glass') {
            $list = $list->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID', 'sampling');
        }
        return $list;
    }

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
        $schedule = $this->getTable('duty');
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

    public function getReportHistoryList($snm)
    {
        $schedule = $this->getTable('history');
        $formSchedule = $schedule
            ->where('sampling', 0)
            ->join('allGlassRun', function ($join) {
                $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                    ->on('productionHistory.schedate', 'allGlassRun.schedate');
            })
            ->where('allGlassRun.PRDT_SNM', $snm)
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'productionDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $a = $formSchedule->get();
        $testModel = $this->getTable('history');
        $formTestModel = $testModel
            ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
            ->where('sampling', 1)
            ->where('UPGWeb.dbo.glass.snm', $snm)
            ->union($formSchedule)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('prd_no')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'productionDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $b = $formTestModel->get();
        return $formTestModel;
    }

    public function getHistoryList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $productionDateOp = 'like';
        $productionDate = '%' . $request->input('productionDate') . '%';
        if ($productionDate != '%%') {
            $productionDateOp = '=';
            $productionDate = date('Y-m-d', strtotime($request->input('productionDate')));
        }
        $schedule = $this->getTable('history');
        
        $formSchedule = $schedule
            ->join('allGlassRun', function ($join) {
                $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                    ->on('productionHistory.schedate', 'allGlassRun.schedate');
            })
            ->where('allGlassRun.PRDT_SNM', 'like', "%$snm%")
            ->where('productionHistory.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('productionhistory.productionDate', $productionDateOp, $productionDate)
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'productionDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'productionHistory.sampling', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $a = $formSchedule->get();
        $testModel = $this->getTable('history');
        $formTestModel = $testModel
            ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
            ->where('sampling', 1)
            ->where('UPGWeb.dbo.glass.snm', 'like', "%$snm%")
            ->where('productionHistory.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('productionHistory.productionDate', $productionDateOp, $productionDate)
            ->union($formSchedule)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('prd_no')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'productionDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'productionHistory.sampling', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $b = $formTestModel->get();
        return $formTestModel;
    }

    public function getQCList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $schedateOp = 'like';
        $schedate = '%' . $request->input('schedate') . '%';
        if ($schedate != '%%') {
            $schedateOp = '=';
            $schedate = date('Y-m-d', strtotime($request->input('schedate')));
        }
        $schedule = $this->getTable('qc');
        $scheduleList = $schedule
            //->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'qualityControl.cus_no')
            ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
            ->where('DB_U105.dbo.PRDT.SNM', 'like', "%$snm%")
            ->where('qualityControl.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('qualityControl.schedate', $schedateOp, $schedate)
            ->orderBy('schedate', 'desc')->orderBy('DB_U105.dbo.PRDT.SNM')->orderBy('glassProdLineID')
            ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
        $a = $scheduleList->get();
        return $scheduleList;
    }

    public function getStaff()
    {
        $list = $this->staff->where('serving', 1);
        return $list;
    }

    public function getCustomer()
    {
        $list = $this->customer->orderby('name');
        return $list;
    }

    public function getGlass()
    {
        $list = $this->glass->orderBy('snm');
        return $list;
    }

    public function getTaskDetailByPrdNO($prd_no)
    {
        $list = $this->taskDetail->where('PRD_NO', $prd_no);
        return $list;
    }

    public function getProdData($prd_no)
    {
        $list = $this->getTable('history')
            ->where('productionHistory.prd_no', $prd_no)
            ->leftJoin('isProdData', function ($join) {
                $join->on('isProdData.schedate', 'productionHistory.schedate')
                    ->on('isProdData.glassProdLineID', 'productionHistory.glassProdLineID')
                    ->on('isProdData.prodReference', 'productionHistory.prd_no');
            })
            ->select('productionHistory.id as historyID', 'productionHistory.schedate as hschedate', 'productionHistory.glassProdLineID as hline', 
                'productionHistory.efficiency', 'productionHistory.gauge', 'productionHistory.formingMethod', 'productionHistory.weight',
                'productionHistory.defect', 'productionHistory.speed', 'isProdData.*');
            
        return $list;
    }

    public function getDuty($id)
    {
        $data = $this->getTable('duty')->where('productionDuty.id', $id);
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

    public function getHistory($id)
    {
        $data = $this->getTable('history')->where('productionHistory.id', $id);
        if ($data->exists()) {
            if ($data->first()->sampling == 0) {
                $data = $data
                    ->join('allGlassRun', function ($join) {
                        $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                            ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                            ->on('productionHistory.schedate', 'allGlassRun.schedate');
                    })
                    ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                        'productionDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                        'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect', 'productionHistory.sampling', 'allGlassRun.orderQty');
            } else {
                $data = $data
                    ->join('tbmkno', function ($join) {
                        $join->on('productionHistory.glassProdLineID', 'tbmkno.glassProdLineID')
                            ->on('productionHistory.prd_no', 'tbmkno.prd_no')
                            ->on('productionHistory.schedate', 'tbmkno.schedate');
                    })
                    ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'cus_no')
                    ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
                    ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                        'productionDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'cus_no', 
                        'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName', 'tbmkno.id as tbmknoID', 
                        'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect', 'productionHistory.sampling', 'tbmkno.orderQty');
            }
            return $data;
        }
        return null;
    }

    public function getQC($id)
    {
        $data = $this->getTable('qc')->where('qualityControl.id', $id);
        if ($data->exists()) {
            $data = $data
                ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
                ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
            return $data;
        }
        return null;
    }

    public function saveDuty($input)
    {
        $result = $this->save('duty', $input);
        return $result;
    }

    public function saveHistory($input)
    {
        $result = $this->save('history', $input);
        return $result;
    }

    public function saveQC($input)
    {
        $result = $this->save('qc', $input);
        return $result;
    }

    public function saveFile($file)
    {
        return $this->common->saveFile($file);
    }

    public function saveOldSchedule($params)
    {
        $params['machno'] = $this->getMachno($params['glassProdLineID']);
        $result = $this->save('oldSchedule', $params);
        return $result;
    }

    private function getMachno($str)
    {
        switch ($str) {
            case 'L1-1':
                return '1-1 1-1線';
                break;
            case 'L1':
                return '01 1線';
                break;
            case 'L2':
                return '02 2線';
                break;
            case 'L3':
                return '03 3線';
                break;
            case 'L5':
                return '04 5線';
                break;
            case 'L6':
                return '05 6線';
                break;
            case 'L7':
                return '06 7線';
                break;
            case 'L8':
                return '07 8線';
                break;
            default:
                return null;
        }
    }

    public function getTable($table)
    {
        switch ($table) {
            case 'duty':
                return $this->duty;
                break;

            case 'history':
                return $this->history;
                break;

            case 'schedule':
                return $this->schedule;
                break;

            case 'staff':
                return $this->staff;
                break;

            case 'customer':
                return $this->customer;
                break;

            case 'run':
                return $this->run;
                break;

            case 'runDetail':
                return $this->runDetail;
                break;

            case 'plan':
                return $this->plan;
                break;

            case 'planDetail':
                return $this->planDetail;
                break;

            case 'allGlass':
                return $this->allGlass;
                break;

            case 'qc':
                return $this->qc;
                break;
            
            case 'prod':
                return $this->prod;
                break;
            
            case 'glass':
                return $this->glass;
                break;

            case 'task':
                return $this->task;
                break;
            
            case 'taskDetail':
                return $this->taskDetail;
                break;
            
            case 'oldSchedule':
                return $this->oldSchedule;
                break;

            default:
                throw new Exception('No model exception');
        }
    }
}