<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\productionHistory\QualityControl;

/**
 * Class QCRepository
 * @package App\Repositories
 */
class QCRepository extends BaseRepository
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
     * @var ScheduleRepository
     */
    public $schedule;
    /**
     * @var QualityControl
     */
    public $qc;
    /**
     * @var BaseDataRepository
     */
    public $base;

    /**
     * QCRepository constructor.
     * @param Common $common
     * @param ProductionHistory $history
     * @param ScheduleRepository $schedule
     * @param QualityControl $qc
     * @param BaseDataRepository $base
     */
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

    /**
     * @param $request
     * @return array
     */
    public function getSchedule($request)
    {
        return $this->schedule->getSchedule($request);
    }

    /**
     * @param $request
     * @return string
     */
    public function getScheduleCustomer($request)
    {
        return $this->schedule->getScheduleCustomer($request);
    }

    /**
     * @param $prd_no
     * @return mixed
     */
    public function getQCDefect($prd_no)
    {
        $table = $this->history;
        $defect = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('defect')->first();
        return $defect['defect'];
    }

    /**
     * @param $prd_no
     * @return mixed
     */
    public function getQCPackRate($prd_no)
    {
        $table = $this->history;
        $packRate = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('efficiency')->first();
        return $packRate['efficiency'];
    }

    /**
     * @param $request
     * @return string
     */
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
        foreach ($data as $d) {
            array_push($array, $d['SPC_NAME'] . number_format($d['QTY']));
        }
        $str = implode(' ', $array);
        return $str;
    }

    /**
     * @param $request
     * @return string
     */
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

    /**
     * @param $view
     * @param $request
     * @return null
     */
    public function getScheduleList($view, $request)
    {
        return $this->getScheduleList($view, $request);
    }

    /**
     * @param $request
     * @return mixed
     */
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

    /**
     * @return \App\Models\UPGWeb\Staff
     */
    public function getStaff()
    {
        return $this->base->getStaff();
    }

    /**
     * @return \App\Models\UPGWeb\Customer
     */
    public function getCustomer()
    {
        return $this->base->getCustomer();
    }

    /**
     * @param $id
     * @return null
     */
    public function getQC($id)
    {
        return $this->qc
            ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
            ->where('qualityControl.id', $id)
            ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
    }

    /**
     * @param $input
     * @return mixed
     */
    public function saveQC($input)
    {
        $table = $this->qc;
        $result = $this->save($table, $input);
        return $result;
    }

    /**
     * @param $file
     * @return string
     */
    public function saveFile($file)
    {
        return $this->common->saveFile($file);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteQC($id)
    {
        $table = $this->qc;
        $result = $this->delfete($table, $id);
        return $result;
    }
}