<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;

use App\Repositories\ProductionRepository;

class ReportController extends Controller
{
    public $prouction;
    

    public function __construct(ProductionRepository $production) 
    {
        $this->production = $production;
    }

    public function productionMeeting()
    {
        $request = request();
        $input = $request->input();
        $snm = $request->input('snm');

        $historyList = $this->production->getReportHistoryList($snm)->get()->toArray();
        if (count($historyList) > 0) {
            $qcData = $this->production->getQCList($request)->first()->toArray();
            $task = $this->production->getTaskDetailByPrdNO($historyList[0]['prd_no'])->orderBy('deadline', 'desc')->get()->toArray();

            return view('report.meeting')
                ->with('snm', $snm)
                ->with('historyList', $historyList)
                ->with('qc', $qcData)
                ->with('task', $task);
        }

        return view('report.meeting')->with('snm', $snm);
    }

    public function getHistory()
    {
        $request = request();
        $input = $request->input();
        $snm = $input['snm'];
        try {
            $prd_no = $this->production->getGlass()->where('snm', $snm)->first()->prd_no;
            $historyList = $this->production->getHistoryList($request)->get()->toArray();
            $qcData = $this->production->getQCList($request)->first()->toArray();
            $task = $this->production->getTaskDetailByPrdNO($prd_no)->orderBy('deadline', 'desc')->get()->toArray();

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
}
