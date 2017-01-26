<?php
/**
 * QCRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/25
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use App\Service\DataFormatService;
use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\productionHistory\QualityControl;
use Illuminate\Support\Facades\DB;

/**
 * Class QCRepository
 * @package App\Repositories
 */
class QCRepository extends BaseRepository
{
    use DataFormatService;
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
     * 取得排程資料
     *
     * @param $request
     * @return array
     */
    public function getSchedule($request)
    {
        return $this->schedule->getSchedule($request);
    }

    /**
     * 取得排程客戶資料
     *
     * @param $request
     * @return string
     */
    public function getScheduleCustomer($request)
    {
        return $this->schedule->getScheduleCustomer($request);
    }

    /**
     * 取得品管缺點
     *
     * @param $prd_no
     * @return mixed
     */
    public function getQCDefect($prd_no)
    {
        return $this->history
            ->where('prd_no', $prd_no)
            ->orderBy('schedate', 'desc')
            ->select('defect')
            ->first()->defect;
    }

    /**
     * 取得良率
     *
     * @param $prd_no
     * @return mixed
     */
    public function getQCPackRate($prd_no)
    {
        return $this->history
            ->where('prd_no', $prd_no)
            ->orderBy('schedate', 'desc')
            ->select('efficiency')
            ->first()->efficiency;
    }

    /**
     * 取得品管附註
     *
     * @param $request
     * @return string
     */
    public function getQCNote($request)
    {
        $set = $this->setData($request);
        $table = $this->schedule->getTable($request->input('view') . 'Detail');
        $data = $table->where('prd_no', $set['prd_no'])
            ->where('glassProdLineID', $set['glassProdLineID'])
            ->where('schedate', $set['schedate'])
            ->groupBy('SPC_NAME')
            ->select('SPC_NAME', DB::raw('SUM(QTY) as QTY'))
            ->get()->toArray();
        return $this->formatNote($data);
    }

    /**
     * 格式化附註資料
     *
     * @param $array
     * @return string
     */
    private function formatNote($array)
    {
        $new = [];
        foreach ($array as $a) {
            array_push($new, $a['SPC_NAME'] . number_format($a['QTY']));
        }
        return implode(' ', $array);
    }

    /**
     * 取得品管加工資料
     *
     * @param $request
     * @return string
     */
    public function getQCDecoration($request)
    {
        $set = $this->setData($request);
        $table = $this->schedule->getTable($request->input('view') . 'Detail');
        $data = $table->where('prd_no', $set['prd_no'])
            ->where('glassProdLineID', $set['glassProdLineID'])
            ->where('schedate', $set['schedate'])
            ->groupBy('SPC_NAME')->select('SPC_NAME')->get()->toArray();
        return implode(',', array_collapse($data));
    }

    /**
     * 設定查詢資料
     *
     * @param $request
     * @return mixed
     */
    private function setData($request)
    {
        $set['prd_no'] = $request->input('prd_no');
        $set['glassProdLineID'] = $request->input('glassProdLineID');
        $set['schedate'] = date('Y/m/d', strtotime($request->input('schedate')));
        return $set;
    }

    /**
     * 取得品管排程清單
     *
     * @param $view
     * @param $request
     * @return null
     */
    public function getScheduleList($view, $request)
    {
        return $this->schedule->getScheduleList($view, $request);
    }

    /**
     * 取得品管清單
     *
     * @param $request
     * @return mixed
     */
    public function getQCList($request)
    {
        $date = $this->formatSchedate($request->input('schedate'));
        $snm = $request->input('snm');
        $glassProdLineID = $request->input('glassProdLineID');
        $scheduleList = $this->qc
            ->join('DB_U105.dbo.PRDT', 'DB_U105.dbo.PRDT.PRD_NO', 'qualityControl.prd_no')
            ->where('DB_U105.dbo.PRDT.SNM', 'like', "%$snm%")
            ->where('qualityControl.glassProdLineID', 'LIKE', "%$glassProdLineID%")
            ->where('qualityControl.schedate', $date['op'], $date['date'])->orderBy('schedate', 'desc')
            ->orderBy('DB_U105.dbo.PRDT.SNM')->orderBy('glassProdLineID')
            ->select('qualityControl.*', 'DB_U105.dbo.PRDT.SNM as snm');
        return $scheduleList;
    }

    /**
     * 取得員工清單
     *
     * @return \App\Models\UPGWeb\Staff
     */
    public function getStaff()
    {
        return $this->base->getStaff();
    }

    /**
     * 取得客戶清單
     *
     * @return \App\Models\UPGWeb\Customer
     */
    public function getCustomer()
    {
        return $this->base->getCustomer();
    }

    /**
     * 取得品管資料
     *
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
     * 儲存品管資料
     *
     * @param $input
     * @return mixed
     */
    public function saveQC($input)
    {
        return $this->save($this->qc, $input);
    }

    /**
     * 刪除品管資料
     *
     * @param $id
     * @return mixed
     */
    public function deleteQC($id)
    {
        return $this->delfete($this->qc, $id);
    }
}