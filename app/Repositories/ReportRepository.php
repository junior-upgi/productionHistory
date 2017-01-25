<?php
/**
 * ReportRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/23
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\productionHistory\GlassRunDetail;
use App\Models\productionHistory\QualityControl;
use App\Models\UPGWeb\Glass;
use App\Models\taskTracking\TaskListDetail;

/**
 * Class ReportRepository
 * @package App\Repositories
 */
class ReportRepository extends BaseRepository
{
    /**
     * @var Common
     */
    public $common;
    /**
     * @var ProductionHistory
     */
    public $history;
    /**
     * @var GlassRunDetail
     */
    public $runDetail;
    /**
     * @var QualityControl
     */
    public $qc;
    /**
     * @var TaskListDetail
     */
    public $taskDetail;
    /**
     * @var Glass
     */
    public $glass;

    /**
     * ReportRepository constructor.
     * @param Common $common
     * @param ProductionHistory $history
     * @param GlassRunDetail $runDetail
     * @param QualityControl $qc
     * @param Glass $glass
     * @param TaskListDetail $taskDetail
     */
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

    /**
     *
     *
     * @param $snm
     * @return mixed
     */
    public function getReportHistoryList($snm)
    {
        return $this->getReportFromSchedule($snm);
    }

    /**
     *
     *
     * @param $snm
     * @return mixed
     */
    private function getReportFromSchedule($snm)
    {
        $schedule = $this->history
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
        return $this->getReportFromTestModel($schedule, $snm);
    }

    /**
     *
     *
     * @param $schedule
     * @param $snm
     * @return mixed
     */
    private function getReportFromTestModel($schedule, $snm)
    {
        return $this->history
            ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
            ->where('sampling', 1)
            ->where('UPGWeb.dbo.glass.snm', $snm)
            ->union($schedule)
            ->orderBy('schedate', 'desc')->orderBy('glassProdLineID')->orderBy('prd_no')
            ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate',
                'fillOutDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 'other', 'productionHistory.efficiency',
                'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect');
    }

    /**
     *
     *
     * @param $request
     * @return mixed
     */
    public function getHistoryList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $date = $this->initSchedate($request->input('schedate'));
        $fromSchedule = $this->gethistorylistschedule($snm, $runProdLineID, $date['op'], $date['date']);
        $fromOldSchedule = $this->unionHistoryListSchedule($snm, $runProdLineID, $date['op'], $date['date'], $fromSchedule);

        return $fromOldSchedule;
    }

    /**
     *
     *
     * @param $date
     * @return mixed
     */
    private function initSchedate($date)
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
     *
     *
     * @param $snm
     * @param $runProdLineID
     * @param $schedateOp
     * @param $schedate
     * @return mixed
     */
    private function getHistoryListSchedule($snm, $runProdLineID, $schedateOp, $schedate)
    {
        return $this->history
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
    }

    /**
     *
     *
     * @param $snm
     * @param $runProdLineID
     * @param $schedateOp
     * @param $schedate
     * @param $fromSchedule
     * @return mixed
     */
    private function unionHistoryListSchedule($snm, $runProdLineID, $schedateOp, $schedate, $fromSchedule)
    {
        return $this->history
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
    }

    /**
     *
     *
     * @param $id
     * @return mixed
     */
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

    /**
     *
     *
     * @param $table
     * @return array
     */
    public function getFormHistoryCustomer($table)
    {
        $customer = [];
        foreach ($table as $item) {
            $customer = $this->getProductionCustomer($item, $customer);
        }
        return $customer;
    }

    /**
     *
     *
     * @param $item
     * @param array $customer
     * @return array
     */
    private function getProductionCustomer($item, $customer = [])
    {
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
        return $customer;
    }

    /**
     *
     *
     * @param $request
     * @return mixed
     */
    public function getQCList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $date = $this->initSchedate($request->input('schedate'));
        $scheduleList = $this->qc
            ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
            ->where('DB_U105.dbo.PRDT.SNM', 'like', "%$snm%")
            ->where('qualityControl.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('qualityControl.schedate', $date['op'], $date['date'])
            ->orderBy('schedate', 'desc')->orderBy('DB_U105.dbo.PRDT.SNM')->orderBy('glassProdLineID')
            ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
        return $scheduleList;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getGlass()
    {
        return $this->glass->orderBy('snm');
    }

    /**
     *
     *
     * @param $prd_no
     * @return mixed
     */
    public function getTaskDetailByPrdNO($prd_no)
    {
        return $this->taskDetail->where('PRD_NO', $prd_no);
    }

    /**
     *
     *
     * @param $prd_no
     * @return mixed
     */
    public function getProdData($prd_no)
    {
        return $this->history
            ->where('productionHistory.prd_no', $prd_no)
            ->leftJoin('isProdData', function ($join) {
                $join->on('isProdData.schedate', 'productionHistory.schedate')
                    ->on('isProdData.glassProdLineID', 'productionHistory.glassProdLineID')
                    ->on('isProdData.prd_no', 'productionHistory.prd_no');
            })
            ->select('productionHistory.id as historyID', 'productionHistory.schedate as hschedate', 'productionHistory.glassProdLineID as hline', 
                'productionHistory.efficiency', 'productionHistory.gauge', 'productionHistory.formingMethod', 'productionHistory.weight',
                'productionHistory.defect', 'productionHistory.speed', 'isProdData.*');
    }

    /**
     *
     *
     * @param $id
     * @return null
     */
    public function getQC($id)
    {
        return $this->qc->where('qualityControl.id', $id)
            ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
            ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
    }
}