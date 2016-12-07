<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\Models\ProductionDuty;
use App\Models\ProductionHistory;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Staff;

class ProductionRepository extends BaseRepository
{
    public $duty;
    public $histosy;
    public $customer;
    public $schedule;
    public $staff;

    public function __construct(
        ProductionDuty $duty,
        Schedule $schedule,
        Staff $staff,
        ProductionHistory $history,
        Customer $customer
    ) {
        parent::__construct();
        $this->duty = $duty;
        $this->schedule = $schedule;
        $this->staff = $staff;
        $this->history = $history;
        $this->customer = $customer;
    }

    public function getSchedule($request)
    {
        $pname = $request->input('pname');
        $machno = $request->input('machno');
        $table = $this->getTable('schedule');
        $list = $table->scheduleList()
            ->where('DB_U105.dbo.PRDT.NAME', 'like', "%$pname%")
            ->where('machno', 'like', "%$machno%")
            ->orderBy('schedate', 'desc')->orderBy('machno')
            ->select('mk_no', 'schedate', 'DB_U105.dbo.PRDT.NAME', 'scheqty', 'allscheqty', 'machno', 'speed', 'Z_DB_U105.dbo.tbmkno.weight', 'worktime', 'outqty', 
                'Z_DB_U105.dbo.tbmkno.cus_no as cus_no', 'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName');
        return $list;
    }

    public function getDutyList($request)
    {
        $pname = $request->input('pname');
        $machno = $request->input('machno');
        $schedule = $this->getTable('duty');
        $scheduleList = $schedule
            ->join('Z_DB_U105.dbo.tbmkno', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionHistory.dbo.productionDuty.mk_no')
            ->join('DB_U105.dbo.PRDT', 'Z_DB_U105.dbo.tbmkno.prd_no', 'DB_U105.dbo.PRDT.PRD_NO')
            ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
            ->where('DB_U105.dbo.PRDT.NAME', 'like', "%$pname%")
            ->where('Z_DB_U105.dbo.tbmkno.machno', 'like', "%$machno%")
            ->orderBy('dutyDate', 'class', 'machno')
            ->select('productionDuty.id', 'Z_DB_U105.dbo.tbmkno.mk_no', 'dutyDate', 
                'class', 'Z_DB_U105.dbo.tbmkno.machno', 'UPGWeb.dbo.vStaffNode.name as staffName', 'DB_U105.dbo.PRDT.NAME as NAME', 
                'quantity', 'piece', 'productionDuty.efficiency', 'anneal', 'startShutdown', 'endShutdown');
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
            ->orderBy('productionDate', 'class', 'machno')
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
        $list = $this->getTable('duty')->where('productionDuty.id', $id);
        if ($list->exists()) {
            if (isset($list->first()->mk_no)) {
                $list->join('Z_DB_U105.dbo.tbmkno', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionHistory.dbo.productionDuty.mk_no')
                    ->join('DB_U105.dbo.PRDT', 'Z_DB_U105.dbo.tbmkno.prd_no', 'DB_U105.dbo.PRDT.PRD_NO')
                    ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
                    ->select('productionDuty.id', 'Z_DB_U105.dbo.tbmkno.mk_no', 'dutyDate', 'class', 'Z_DB_U105.dbo.tbmkno.machno', 
                        'UPGWeb.dbo.vStaffNode.name as staffName', 'UPGWeb.dbo.vStaffNode.ID as staffID', 'DB_U105.dbo.PRDT.NAME', 'quantity', 'piece', 
                        'productionDuty.efficiency', 'anneal', 'startShutdown', 'endShutdown', 'changeModel', 'changeSpeed', 'improve', 'suggest');
            } else {
                $list->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
                    ->orderBy('dutyDate', 'class', 'machno')
                    ->select('productionDuty.id', 'mk_no', 'dutyDate', 'class', 'machno', 'UPGWeb.dbo.vStaffNode.name as staffName', 
                        'UPGWeb.dbo.vStaffNode.ID as staffID', 'snm as NAME', 'quantity', 'piece', 'efficiency', 'anneal', 'startShutdown', 'endShutdown', 'changeModel', 'changeSpeed', 'improve', 'suggest');
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

            default:
                throw new Exception('No model exception');
        }
    }
}