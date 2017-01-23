<?php
namespace App\Repositories;

use DB;

use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\productionHistory\GlassRun;
use App\Models\productionHistory\GlassRunPlan;
use App\Models\productionHistory\AllGlassRun;
use App\Models\productionHistory\OldSchedule;

/**
 * Class HistoryRepository
 * @package App\Repositories
 */
class HistoryRepository extends BaseRepository
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
     * @var GlassRun
     */
    public $run;
    /**
     * @var GlassRunPlan
     */
    public $plan;
    /**
     * @var AllGlassRun
     */
    public $allGlass;
    /**
     * @var OldSchedule
     */
    public $oldSchedule;
    /**
     * @var BaseDataRepository
     */
    public $base;
    /**
     * @var ScheduleRepository
     */
    public $schedule;

    /**
     * HistoryRepository constructor.
     * @param Common $common
     * @param ProductionHistory $history
     * @param GlassRun $run
     * @param GlassRunPlan $plan
     * @param AllGlassRun $allGlass
     * @param OldSchedule $oldSchedule
     * @param BaseDataRepository $base
     * @param ScheduleRepository $schedule
     */
    public function __construct(
        Common $common,
        ProductionHistory $history,
        GlassRun $run,
        GlassRunPlan $plan,
        AllGlassRun $allGlass,
        OldSchedule $oldSchedule,
        BaseDataRepository $base,
        ScheduleRepository $schedule
    ) {
        $this->common = $common;
        $this->history = $history;
        $this->run = $run;
        $this->plan = $plan;
        $this->allGlass = $allGlass;
        $this->oldSchedule = $oldSchedule;
        $this->base = $base;
        $this->schedule = $schedule;
    }

    /**
     * @param $input
     * @return bool
     */
    public function checkExists($input)
    {
        if ($input['type'] == 'add') {
            $data = $this->history
                ->where('prd_no', $input['prd_no'])
                ->where('schedate', date('Y/m/d', strtotime($input['schedate'])))
                ->where('glassProdLineID', $input['glassProdLineID']);
            if ($data->exists()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $input
     * @return array
     */
    public function checkSchedule($input)
    {
        $schedate = $input['schedate'];
        $prd_no = $input['prd_no'];
        $glassProdLineID = $input['glassProdLineID'];
        $sampling = $input['sampling'];
        if ($sampling == '--') {
            return ['result' => false];
        }
        $data = $this->allGlass
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $glassProdLineID)
            ->where('schedate', $schedate);
        if ($data->exists()) {
            return [
                'result' => true,
                'id' => $data->first()->id,
            ];
        }
        return ['result' => false];
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
     * @param $view
     * @param $request
     * @return null
     */
    public function getScheduleList($view, $request)
    {
        return $this->schedule->getScheduleList($view, $request);
    }

    /**
     * @param $request
     * @return mixed
     */
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
     * @return \App\Models\UPGWeb\Glass
     */
    public function getGlass()
    {
        return $this->base->getGlass();
    }

    /**
     * @param $id
     * @return null
     */
    public function getHistory($id)
    {
        $data = $this->history->where('productionHistory.id', $id);
        if ($data->exists()) {
            if ($data->first()->sampling == 0) {
                $data = $data
                    ->join('allGlassRun', function ($join) {
                        $join->on('productionHistory.glassProdLineID', 'allGlassRun.glassProdLineID')
                            ->on('productionHistory.prd_no', 'allGlassRun.prd_no')
                            ->on('productionHistory.schedate', 'allGlassRun.schedate');
                    })
                    ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                        'fillOutDate', 'gauge', 'allGlassRun.PRDT_SNM as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 
                        'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect', 'productionHistory.sampling', 'allGlassRun.orderQty');
            } else {
                $data = $data
                    ->join('tbmkno', function ($join) {
                        $join->on('productionHistory.glassProdLineID', 'tbmkno.glassProdLineID')
                            ->on('productionHistory.prd_no', 'tbmkno.prd_no')
                            ->on('productionHistory.schedate', 'tbmkno.schedate');
                    })
                    ->join('UPGWeb.dbo.vCustomer', 'UPGWeb.dbo.vCustomer.ID', 'cus_no')
                    ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'productionHistory.prd_no')
                    ->select('productionHistory.id', 'productionHistory.prd_no', 'productionHistory.glassProdLineID', 'productionHistory.schedate', 
                        'fillOutDate', 'gauge', 'UPGWeb.dbo.glass.snm as snm', 'formingMethod', 'other', 'productionHistory.efficiency', 'cus_no', 
                        'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName', 'tbmkno.id as tbmknoID', 
                        'productionHistory.weight', 'actualWeight', 'stressLevel', 'thermalShock', 'productionHistory.speed', 'productionHistory.defect', 'productionHistory.sampling', 'tbmkno.orderQty');
            }
            return $data;
        }
        return null;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function saveOldSchedule($params)
    {
        $table = $this->oldSchedule;
        $params['machno'] = $this->getMachno($params['glassProdLineID']);
        $result = $this->save($table, $params, [], 'id', true);
        return $result;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function saveHistory($input)
    {
        return $this->save($this->history, $input, [], 'id', true);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteHistory($id)
    {
        return $this->delete($this->history, $id);
    }

    /**
     * @param $key
     * @return mixed
     */
    private function getMachno($key)
    {
        $keys = [
            'L1-1' => '1-1 1-1線', 'L1' => '01 1線', 'L2' => '02 2線', 'L3' => '03 3線', 
            'L5' => '04 5線', 'L6' => '05 6線', 'L7' => '06 7線', 'L8' => '07 8線'
        ];
        return $keys[$key];
    }
}