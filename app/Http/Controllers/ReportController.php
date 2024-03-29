<?php

namespace App\Http\Controllers;

use Auth;
use App\Repositories\ReportRepository;

/**
 * Class ReportController
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{
    /**
     * @var ReportRepository
     */
    public $report;

    /**
     * ReportController constructor.
     * @param ReportRepository $report
     */
    public function __construct(ReportRepository $report) 
    {
        $this->report = $report;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function productionMeeting()
    {
        $request = request();
        $snm = $request->input('snm');
        if ($snm == '') {
            return view('report.meeting')->with('snm', null);
        }

        $historyList = $this->report->getReportHistoryList($snm)->get()->toArray();
        $schedule = $this->report->getReportScheduleList($snm)->get()->toArray();
        $prd_no = $schedule[0]['prd_no'];
        $qcData = $this->report->getQCList($request)->first();
        $task = $this->report->getTaskDetailByPrdNO($prd_no)->orderBy('deadline', 'desc')->get()->toArray();
        $prodData = $this->report->getProdData($prd_no)->orderBy('glassRun.schedate', 'desc')->orderBy('glassRun.glassProdLineID')->get()->toArray();
        $prodInfo = $this->report->getProdInfo($prd_no)->get()->toArray();
        $defect = $this->report->getDefect($prd_no);

        return view('report.meeting')
            ->with('snm', $snm)
            ->with('schedule', $schedule)
            ->with('historyList', $historyList)
            ->with('qc', $qcData)
            ->with('prodData', $prodData)
            ->with('prodInfo', $prodInfo)
            ->with('defect', $defect)
            ->with('task', $task);
    }

    /**
     * @return array
     */
    public function getHistory()
    {
        $request = request();
        $input = $request->input();
        $snm = $input['snm'];
        try {
            $prd_no = $this->report->getGlass()->where('snm', $snm)->first()->prd_no;
            $historyList = $this->report->getHistoryList($request)->get()->toArray();
            $qcData = $this->report->getQCList($request)->first()->toArray();
            $task = $this->report->getTaskDetailByPrdNO($prd_no)->orderBy('deadline', 'desc')->get()->toArray();

            return [
                'success' => true, 
                'historyList' => $historyList,
                'qc' => $qcData,
                'task' => $task,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'msg' => $e,
            ];
        }
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function qcForm($id)
    {
        $qc = $this->report->getQC($id)
            ->leftJoin('upgiSystem.dbo.file', 'qualityControl.draw', 'file.ID')
            ->join('UPGWeb.dbo.glass', 'qualityControl.prd_no', 'glass.prd_no')
            ->select('qualityControl.*', 'file.type', 'file.code', 'glass.snm')
            ->first();
            
        if (isset($qc)) {
            $prd_no = $qc->prd_no;
            $history = $this->report->getTable('history')
                ->where('prd_no', $prd_no)
                ->orderBy('schedate', 'desc')
                ->first();
            return view('report.qc')
                ->with('qc', $qc)
                ->with('history', $history);
        }
        return view('report.history');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function historyForm($id)
    {
        $history = $this->report->getTable('history')
            ->where('id', $id)
            ->join('UPGWeb.dbo.glass', 'productionHistory.prd_no', 'glass.prd_no')
            ->select('productionHistory.*', 'glass.snm', 'glass.spc')
            ->first();
        $historyList = $this->report->getFormhistoryList($history->prd_no)->take(8)->get()->toArray();
        $customer = $this->report->getFormHistoryCustomer($historyList);
        return view('report.history')
            ->with('history', $history)
            ->with('historyList', $historyList)
            ->with('customer', $customer);
    }
}
