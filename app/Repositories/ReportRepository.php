<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\productionHistory\GlassRunDetail;
use App\Models\productionHistory\QualityControl;
use App\Models\UPGWeb\Glass;
use App\Models\taskTracking\TaskListDetail;

//
class ReportRepository extends BaseRepository
{
    public $common;
    public $history;
    public $runDetail;
    public $qc;
    public $taskDetail;
    public $glass;
    //
    public function __construct(
        Common $common,
        ProductionHistory $history,
        GlassRundetail $runDetail,
        QualityControl $qc,
        Glass $glass,
        TaskListDetail $taskDetail
    ) {
        $this->common = $common;
        $this->history = $history;
        $this->runDetail = $runDetail;
        $this->qc = $qc;
        $this->glass = $glass;
        $this->taskDetail = $taskDetail;
    }

    //*******
    public function getReportHistoryList($snm)
    {
        $schedule = $this->history;
        $formSchedule = $schedule
            ->where('sampling', 0)
            ->join('allGlassRun', function ($join) {
                $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                    ->on('productionHistory.schedate', 'allGlassRun.schedate');
            })
            ->where('allGlassRun.PRDT_SNM', $snm)
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'fillOutDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $testModel = $this->history;
        $formTestModel = $testModel
            ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
            ->where('sampling', 1)
            ->where('UPGWeb.dbo.glass.snm', $snm)
            ->union($formSchedule)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('prd_no')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'fillOutDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        return $formTestModel;
    }

    //**********
    public function getHistoryList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $schedateOp = 'like';
        $schedate = '%' . $request->input('schedate') . '%';
        if ($schedate != '%%') {
            $schedateOp = '=';
            $schedate = date('Y-m-d', strtotime($request->input('schedate')));
        }
        $schedule = $this->history;
        
        $fromSchedule = $schedule
            ->join('allGlassRun', function ($join) {
                $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                    ->on('productionHistory.schedate', 'allGlassRun.schedate');
            })
            ->where('allGlassrun.id', null)
            ->where('allGlassRun.PRDT_SNM', 'like', "%$snm%")
            ->where('productionHistory.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('productionhistory.schedate', $schedateOp, $schedate)
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'fillOutDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'productionHistory.sampling', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        $oldSchedule = $this->history;
        $fromOldSchedule = $oldSchedule
            ->join('allGlassRun', function ($join) {
                $join->on('productionHistory.id', 'allGlassRun.id');
            })
            ->where('allGlassRun.PRDT_SNM', 'like', "%$snm%")
            ->where('productionHistory.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('productionhistory.schedate', $schedateOp, $schedate)
            ->union($fromSchedule)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('prd_no')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                'fillOutDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'productionHistory.sampling', 
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
        return $fromOldSchedule;
    }

    //*******
    public function getFormHistoryList($id)
    {
        $formSchedule = $this->history
            ->where('sampling', 0)
            ->where('productionHistory.prd_no', $id)
            ->join('allGlassRun', function ($join) {
                $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                    ->on('productionHistory.schedate', 'allGlassRun.schedate');
            })
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'cus_no',  
                'productionHistory.schedate', 'fillOutDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 
                'other', 'productionHistory.efficiency', 'productionHistory.weight', 'actualWeight', 'stressLevel', 
                'thermalShock', 'productionHistory.speed', 'productionHistory.defect', 'productionHistory.sampling');
        $testModel = $this->history;
        $formTestModel = $testModel
            ->where('sampling', 1)
            ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
            ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'productionHistory.cus_no')
            ->where('productionHistory.prd_no', $id)
            ->union($formSchedule)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'vCustomer.sname', 
                'productionHistory.schedate', 'fillOutDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 
                'other', 'productionHistory.efficiency', 'productionHistory.weight', 'actualWeight', 'stressLevel', 
                'thermalShock', 'productionHistory.speed', 'productionHistory.defect', 'productionHistory.sampling');
        return $formTestModel;
    }

    //*********
    public function getFormHistoryCustomer($table)
    {
        $customer = [];
        foreach ($table as $item) {
            if ($item['sampling'] == 0) {
                $list = $this->runDetail
                    ->where('schedate', $item['schedate'])
                    ->where('glassProdLineID', $item['glassProdLineID'])
                    ->where('prd_no', $item['prd_no'])
                    ->select('CUS_SNM')
                    ->get()->toArray();
                $set['id'] = $item['id'];
                $set['cus_snm'] = implode(',', array_collapse($list));
                array_push($customer, $set);
            }
        }
        return $customer;
    }

    //**********
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
        $schedule = $this->qc;
        $scheduleList = $schedule
            //->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'qualityControl.cus_no')
            ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
            ->where('DB_U105.dbo.PRDT.SNM', 'like', "%$snm%")
            ->where('qualityControl.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('qualityControl.schedate', $schedateOp, $schedate)
            ->orderBy('schedate', 'desc')->orderBy('DB_U105.dbo.PRDT.SNM')->orderBy('glassProdLineID')
            ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
        return $scheduleList;
    }

    //********
    public function getGlass()
    {
        $list = $this->glass->orderBy('snm');
        return $list;
    }

    //**************
    public function getTaskDetailByPrdNO($prd_no)
    {
        $list = $this->taskDetail->where('PRD_NO', $prd_no);
        return $list;
    }

    //***********
    public function getProdData($prd_no)
    {
        $list = $this->history
            ->where('productionHistory.prd_no', $prd_no)
            ->leftJoin('isProdData', function ($join) {
                $join->on('isProdData.schedate', 'productionHistory.schedate')
                    ->on('isProdData.glassProdLineID', 'productionHistory.glassProdLineID')
                    ->on('isProdData.prd_no', 'productionHistory.prd_no');
            })
            ->select('productionHistory.id as historyID', 'productionHistory.schedate as hschedate', 'productionHistory.glassProdLineID as hline', 
                'productionHistory.efficiency', 'productionHistory.gauge', 'productionHistory.formingMethod', 'productionHistory.weight',
                'productionHistory.defect', 'productionHistory.speed', 'isProdData.*');
            
        return $list;
    }

    //*****
    public function getQC($id)
    {
        $data = $this->qc->where('qualityControl.id', $id);
        if ($data->exists()) {
            $data = $data
                ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
                ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
            return $data;
        }
        return null;
    }
}