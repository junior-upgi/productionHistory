<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\Models\ProductionDuty;
use App\Models\Schedule;
use App\Models\Staff;

class ProductionRepository extends BaseRepository
{
    public $duty;
    public $schedule;
    public $staff;

    public function __construct(
        ProductionDuty $duty,
        Schedule $schedule,
        Staff $staff
    ) {
        parent::__construct();
        $this->duty = $duty;
        $this->schedule = $schedule;
        $this->staff = $staff;
    }

    public function getSchedule($request)
    {
        $prd_no = $request->input('prd_no');
        $machno = $request->input('machno');
        $table = $this->getTable('schedule');
        $list = $table->scheduleList()
            ->where('NAME', 'like', "%$prd_no%")
            ->where('machno', 'like', "%$machno%")
            ->orderBy('schedate', 'desc')->orderBy('machno')
            ->select('mk_no', 'schedate', 'NAME', 'scheqty', 'allscheqty', 'machno', 'speed', 'Z_DB_U105.dbo.tbmkno.weight', 'worktime', 'outqty');
        return $list;
    }

    public function getDutyList($request)
    {
        $prd_no = $request->input('prd_no');
        $machno = $request->input('machno');
        $schedule = $this->getTable('duty');
        $formSchedule = $schedule
            ->join('Z_DB_U105.dbo.tbmkno', 'Z_DB_U105.dbo.tbmkno.mk_no', 'productionHistory.dbo.productionDuty.mk_no')
            ->join('DB_U105.dbo.PRDT', 'Z_DB_U105.dbo.tbmkno.prd_no', 'DB_U105.dbo.PRDT.PRD_NO')
            ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
            ->where('DB_U105.dbo.PRDT.NAME', 'like', "%$prd_no%")
            ->where('Z_DB_U105.dbo.tbmkno.machno', 'like', "%$machno%")
            ->select('productionDuty.id', 'Z_DB_U105.dbo.tbmkno.mk_no', 'dutyDate', 'class', 'Z_DB_U105.dbo.tbmkno.machno', 'UPGWeb.dbo.vStaffNode.name as staffName', 'DB_U105.dbo.PRDT.NAME', 'quantity', 'piece', 'productionDuty.efficiency', 'anneal', 'startShutdown', 'endShutdown');
        $testModel = $this->getTable('duty');
        $formTestModel = $testModel
            ->whereNull('mk_no')
            ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
            ->where('snm', 'like', "%$prd_no%")
            ->where('machno', 'like', "%$machno%")
            ->union($formSchedule)
            ->orderBy('dutyDate', 'class', 'machno')
            ->select('productionDuty.id', 'mk_no', 'dutyDate', 'class', 'machno', 'UPGWeb.dbo.vStaffNode.name as staffName', 'snm as NAME', 'quantity', 'piece', 'efficiency', 'anneal', 'startShutdown', 'endShutdown');
        return $formTestModel;
    }

    public function getStaff()
    {
        $list = $this->staff->where('serving', 1);
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

    public function getTable($table)
    {
        switch ($table) {
            case 'duty':
                return $this->duty;
                break;

            case 'schedule':
                return $this->schedule;
                break;

            case 'staff':
                return $this->staff;
                break;

            default:
                throw new Exception('No model exception');
        }
    }
}