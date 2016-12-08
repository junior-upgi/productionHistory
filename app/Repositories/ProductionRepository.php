<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

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
        AllGlassRun $allGlass
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
    }

    public function getSchedule($request)
    {
        $snm = $request->input('snm');
        $glassProdLineID = $request->input('glassProdLineID');
        $schedate = $request->input('schedate');
        if ($schedate != '') {
            $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        }
        $table = $this->getTable('allGlass');
        $list = $table
            ->where('PRDT_SNM', 'like', "%$snm%")
            ->where('glassProdLineID', 'like', "%$glassProdLineID%")
            ->where('schedate', 'like', "%$schedate%")
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
        if ($dutyDate != '') {
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
        return $scheduleList;
    }

    public function getHistoryList($request)
    {
        $pname = $request->input('pname');
        $machno = $request->input('machno');
        $schedule = $this->getTable('history');
        
        $formSchedule = $schedule
            ->join('Z_DB_U105.dbo.tbmkno', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionHistory.dbo.productionHistory.mk_no')
            ->join('DB_U105.dbo.PRDT', 'Z_DB_U105.dbo.tbmkno.prd_no', 'DB_U105.dbo.PRDT.PRD_NO')
            ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'Z_DB_U105.dbo.tbmkno.cus_no')
            ->where('DB_U105.dbo.PRDT.NAME', 'like', "%$pname%")
            ->where('Z_DB_U105.dbo.tbmkno.machno', 'like', "%$machno%")
            ->select('productionHistory.id', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionDate', 'Z_DB_U105.dbo.tbmkno.machno', 'gauge', 
                'DB_U105.dbo.PRDT.NAME', 'blow', 'other', 'productionHistory.efficiency', 'productionHistory.weight', 'actualWeight', 'skewPower', 
                'termalShock', 'productionHistory.speed', 'productionHistory.defect',
                'Z_DB_U105.dbo.tbmkno.cus_no as cus_no', 'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName');
        $a = $formSchedule->get();
        $testModel = $this->getTable('history');
        $formTestModel = $testModel
            ->where('mk_no', '--')
            ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'productionHistory.cus_no')
            ->where('snm', 'like', "%$pname%")
            ->where('machno', 'like', "%$machno%")
            ->union($formSchedule)
            ->orderBy('productionDate', 'machno')
            ->select('productionHistory.id', 'mk_no', 'productionDate', 'machno', 'gauge', 
                'snm as NAME', 'blow', 'other', 'productionHistory.efficiency', 'productionHistory.weight', 'actualWeight', 'skewPower', 
                'termalShock', 'productionHistory.speed', 'productionHistory.defect',
                'cus_no', 'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName');
        $b = $formTestModel->get();
        return $formTestModel;
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
        $list = $this->getTable('history')->where('productionHistory.id', $id);
        if ($list->exists()) {
            if ($list->first()->mk_no != '--') {
                $list = $list->join('Z_DB_U105.dbo.tbmkno', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionHistory.dbo.productionHistory.mk_no')
                    ->join('DB_U105.dbo.PRDT', 'Z_DB_U105.dbo.tbmkno.prd_no', 'DB_U105.dbo.PRDT.PRD_NO')
                    ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'Z_DB_U105.dbo.tbmkno.cus_no')
                    ->select('productionHistory.id', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionDate', 'Z_DB_U105.dbo.tbmkno.machno', 'gauge', 
                        'DB_U105.dbo.PRDT.NAME', 'blow', 'other', 'productionHistory.efficiency', 'productionHistory.weight', 'actualWeight', 'skewPower', 
                        'termalShock', 'productionHistory.speed', 'productionHistory.defect',
                        'Z_DB_U105.dbo.tbmkno.cus_no as cus_no', 'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName');
            } else {
                $list = $list->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'productionHistory.cus_no')
                    ->select('productionHistory.id', 'mk_no', 'productionDate', 'machno', 'gauge', 
                        'snm as NAME', 'blow', 'other', 'productionHistory.efficiency', 'productionHistory.weight', 'actualWeight', 'skewPower', 
                        'termalShock', 'productionHistory.speed', 'productionHistory.defect',
                        'cus_no', 'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName');
            }
            return $list;
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

            default:
                throw new Exception('No model exception');
        }
    }
}