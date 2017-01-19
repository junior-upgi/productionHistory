<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Service\Common;
use App\Models\productionHistory\ProductionHistory;
use App\Models\UPGWeb\Customer;
use App\Models\UPGWeb\Staff;
use App\Models\productionHistory\GlassRun;
use App\Models\productionHistory\GlassRunPlan;
use App\Models\productionHistory\AllGlassRun;
use App\Models\productionHistory\OldSchedule;
use App\Models\UPGWeb\Glass;

//
class HistoryRepository extends BaseRepository
{
    //
    public function __construct(
        Common $common,
        ProductionHistory $history,
        Customer $customer,
        Staff $staff,
        GlassRun $run,
        GlassRunPlan $plan,
        AllGlassRun $allGlass,
        OldSchedule $oldSchedule,
        Glass $glass
    ) {
        $this->common = $common;
        $this->history = $history;
        $this->glass = $glass;
        $this->run = $run;
        $this->plan = $plan;
        $this->allGlass = $allGlass;
        $this->staff = $staff;
        $this->customer = $customer;
        $this->oldSchedule = $oldSchedule;
    }

    //*******
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

    //*******
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

    //******
    public function getSchedule($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'run':
                $data = $this->run
                    ->where('prd_no', $prd_no)
                    ->where('glassProdLineID', $runProdLineID)
                    ->where('schedate', $schedate)
                    ->select('id', 'prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
                break;
            
            case 'plan':
                $data = $this->plan
                    ->where('prd_no', $prd_no)
                    ->where('glassProdLineID', $runProdLineID)
                    ->where('schedate', $schedate)
                    ->select('prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
                break;
            
            case 'allGlass':
                $data = $this->allGlass
                    ->where('prd_no', $prd_no)
                    ->where('glassProdLineID', $runProdLineID)
                    ->where('schedate', $schedate)
                    ->select('id', 'prd_no', 'PRDT_SNM as snm', 'glassProdLineID', 'schedate', 'orderQty')->first();
                break;
        }
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }
    
    //********
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
                $table = $this->plan;
                break;
            case 'run':
                $table = $this->run;
                break;
            case 'allGlass':
                $table = $this->allGlass;
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

    //***********
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
        $a = $fromSchedule->get();
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
        $b = $fromOldSchedule->get();
        return $fromOldSchedule;
    }

    //*******
    public function getStaff()
    {
        $list = $this->staff->where('serving', 1);
        return $list;
    }

    //*******
    public function getCustomer()
    {
        $list = $this->customer->orderby('name');
        return $list;
    }

    //*****
    public function getGlass()
    {
        $list = $this->glass->orderBy('snm');
        return $list;
    }

    //******
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

    //*********
    public function saveOldSchedule($params)
    {
        $table = $this->oldSchedule;
        $params['machno'] = $this->getMachno($params['glassProdLineID']);
        $result = $this->save($table, $params, [], 'id', true);
        return $result;
    }

    public function saveHistory($input)
    {
        $table = $this->history;
        $result = $this->save($table, $input, [], 'id', true);
        return $result;
    }

    //*************
    public function deleteHistory($id)
    {
        $table = $this->history;
        $result = $this->delfete($table, $id);
        return $result;
    } 

    //*****
    private function getMachno($key)
    {
        $keys = [
            'L1-1' => '1-1 1-1線', 'L1' => '01 1線', 'L2' => '02 2線', 'L3' => '03 3線', 
            'L5' => '04 5線', 'L6' => '05 6線', 'L7' => '06 7線', 'L8' => '07 8線'
        ];
        return $keys[$key];
    }
}