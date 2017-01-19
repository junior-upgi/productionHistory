<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\productionHistory\QualityControl;

//
class QCRepository extends BaseRepository
{
    public $common;
    public $history;
    public $schedule;
    public $qc;
    public $base;

    //
    public function __construct(
        Common $common,
        ProductionHistory $history,
        ScheduleRepository $schedule,
        QualityControl $qc,
        BaseDataRepository $base
    ) {
        $this->common = $common;
        $this->schedule = $schedule;
        $this->history = $history;
        $this->qc = $qc;
        $this->base = $base;
    }

    //********
    public function getSchedule($request)
    {
        return $this->schedule->getSchedule($request);
    }

    //********
    public function getScheduleCustomer($request)
    {
        return $this->schedule->getScheduleCustomer($request);
    }

    //******
    public function getQCDefect($prd_no)
    {
        $table = $this->history;
        $defect = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('defect')->first();
        return $defect['defect'];
    }

    //******
    public function getQCPackRate($prd_no)
    {
        $table = $this->history;
        $packRate = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('efficiency')->first();
        return $packRate['efficiency'];
    }

    //******
    public function getQCNote($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'plan':
                $table = $this->schedule->planDetail;
                break;
            case 'run':
                $table = $this->schedule->runDetail;
                break;
            default:
                return '';
        } 
        $data = $table
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

    //*******
    public function getQCDecoration($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'plan':
                $table = $this->schedule->planDetail;
                break;
            case 'run':
                $table = $this->schedule->runDetail;
                break;
            default:
                return '';
        } 
        $data = $table
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
    
    //*******
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
                $table = $this->schedule->plan;
                break;
            case 'run':
                $table = $this->schedule->run;
                break;
            case 'allGlass':
                $table = $this->schedule->allGlass;
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

    //********
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
        $a = $scheduleList->get();
        return $scheduleList;
    }

    //******
    public function getStaff()
    {
        return $this->base->getStaff();
    }

    //*******
    public function getCustomer()
    {
        return $this->base->getCustomer();
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

    //*****
    public function saveQC($input)
    {
        $table = $this->qc;
        $result = $this->save($table, $input);
        return $result;
    }

    //******
    public function saveFile($file)
    {
        return $this->common->saveFile($file);
    }

    //******
    public function deleteQC($id)
    {
        $table = $this->qc;
        $result = $this->delfete($table, $id);
        return $result;
    }
}