<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use DB;

use App\Models\ProductionDuty;
use App\Models\ProductionHistory;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Staff;
use App\Models\GlassRun;
use App\Models\GlassRunDetail;
use App\Models\GlassRunPlan;
use App\Models\GlassRunPlanDetail;
use App\Models\AllGlassRun;
use App\Models\QualityControl;

class ProductionRepository extends BaseRepository
{
    public $duty;
    public $histosy;
    public $customer;
    public $schedule;
    public $staff;
    public $glass;
    public $glassDetail;
    public $plan;
    public $planDetail;
    public $allGlass;
    public $qc;

    public function __construct(
        ProductionDuty $duty,
        Schedule $schedule,
        Staff $staff,
        ProductionHistory $history,
        Customer $customer,
        GlassRun $glass,
        GlassRunDetail $glassDetail,
        GlassRunPlan $plan,
        GlassRunPlanDetail $planDetail,
        AllGlassRun $allGlass,
        QualityControl $qc
    ) {
        parent::__construct();
        $this->duty = $duty;
        $this->schedule = $schedule;
        $this->staff = $staff;
        $this->history = $history;
        $this->customer = $customer;
        $this->glass = $glass;
        $this->glassDetail = $glassDetail;
        $this->plan = $plan;
        $this->planDetail = $planDetail;
        $this->allGlass = $allGlass;
        $this->qc = $qc;
    }

    public function getSchedule($request)
    {
        $prd_no = $request->input('prd_no');
        $glassProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view)
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $glassProdLineID)
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
        $glassProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view . 'Detail')
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $glassProdLineID)
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
        $glassProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view . 'Detail')
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $glassProdLineID)
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
        $glassProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        $data = $this->getTable($view . 'Detail')
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $glassProdLineID)
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
        $glassProdLineID = $request->input('glassProdLineID');
        $schedateOp = 'like';
        $schedate = '%' . $request->input('schedate') . '%';
        if ($schedate != '%%') {
            $schedateOp = '=';
            $schedate = date('Y-m-d', strtotime($request->input('schedate')));
        }
        $table = $this->getTable($view);
        $list = $table
            ->where('PRDT_SNM', 'like', "%$snm%")
            ->where('glassProdLineID', 'like', "%$glassProdLineID%")
            ->where('schedate', $schedateOp, $schedate)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('PRDT_SNM')
            ->select('schedate', 'prd_no', 'PRDT_SNM as snm', 'orderQty', 'glassProdLineID');
        return $list;
    }

    public function getDutyList($request)
    {
        $snm = $request->input('snm');
        $glassProdLineID = $request->input('glassProdLineID');
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
            ->where('productionDuty.glassProdLineID', 'like', "%$glassProdLineID%")
            ->where('productionDuty.dutyDate', $dutyDateOp, $dutyDate)
            ->orderBy('dutyDate', 'desc')->orderBy('shift')->orderBy('glassProdLineID')
            ->select('productionDuty.id', 'productionDuty.glassProdLineID', 'dutyDate', 
                'shift', 'UPGWeb.dbo.vStaffNode.name as staffName', 'allGlassRun.PRDT_SNM as snm', 
                'quantity', 'pack', 'productionDuty.efficiency', 'annealGrade', 'startShutdown', 'endShutdown');
        $a = $scheduleList->get();
        return $scheduleList;
    }

    public function getHistoryList($request)
    {
        $snm = $request->input('snm');
        $glassProdLineID = $request->input('glassProdLineID');
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
            ->where('productionHistory.glassProdLineID', 'like', "%$glassProdLineID%")
            ->where('productionhistory.productionDate', $productionDateOp, $productionDate)
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'productionDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $a = $formSchedule->get();
        $testModel = $this->getTable('history');
        $formTestModel = $testModel
            ->whereNull('prd_no')
            ->whereNull('schedate')
            ->where('snm', 'like', "%$snm%")
            ->where('productionHistory.glassProdLineID', 'like', "%$glassProdLineID%")
            ->where('productionHistory.productionDate', $productionDateOp, $productionDate)
            ->union($formSchedule)
            ->orderBy('productionDate')->orderBy('glassProdLineID')->orderBy('prd_no')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'productionDate', 'gauge', 'snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $b = $formTestModel->get();
        return $formTestModel;
    }

    public function getQCList($request)
    {
        $snm = $request->input('snm');
        $glassProdLineID = $request->input('glassProdLineID');
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
            ->where('qualityControl.glassProdLineID', 'like', "%$glassProdLineID%")
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
            if (isset($data->first()->prd_no)) {
                $data = $data
                    ->join('allGlassRun', function ($join) {
                        $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                            ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                            ->on('productionHistory.schedate', 'allGlassRun.schedate');
                    })
                    ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                        'productionDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                        'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
            } else {
                $data = $data
                    ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'cus_no')
                    ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                        'productionDate', 'gauge', 'snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'cus_no', 
                        'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName', 
                        'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
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

            case 'glass':
                return $this->glass;
                break;

            case 'glassDetail':
                return $this->glassDetail;
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

            default:
                throw new Exception('No model exception');
        }
    }
}