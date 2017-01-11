<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class ProductionRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct();
    }

    public function checkExists($input)
    {
        if ($input['type'] == 'add') {
            $data = $this->history
                ->where('prd_no', $input['prd_no'])
                ->where('schedate', date('Y/m/d', strtotime($input['schedate'])))
                ->where('glassProdLineID', $input['glassProdLineID'])
                ->where('sampling', $input['sampling']);
            if ($data->exists()) {
                return true;
            }
        }
        return false;
    }

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

    public function getScheduleCustomer($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'plan':
                $table = $this->planDetail;
                break;
            case 'run':
                $table = $this->runDetail;
                break;
            default:
                return '';
        } 
        $data = $table
            ->where('prd_no', $prd_no)
            ->where('glassProdLineID', $runProdLineID)
            ->where('schedate', $schedate)
            ->select('CUS_SNM')->get()->toArray();
        $data = array_collapse($data);
        $str = implode(' , ', $data);
        return $str;
    }

    public function getQCDefect($prd_no)
    {
        $table = $this->history;
        $defect = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('defect')->first();
        return $defect['defect'];
    }

    public function getQCPackRate($prd_no)
    {
        $table = $this->history;
        $packRate = $table->where('prd_no', $prd_no)->orderBy('schedate', 'desc')->select('efficiency')->first();
        return $packRate['efficiency'];
    }

    public function getQCNote($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'plan':
                $table = $this->planDetail;
                break;
            case 'run':
                $table = $this->runDetail;
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

    public function getQCDecoration($request)
    {
        $prd_no = $request->input('prd_no');
        $runProdLineID = $request->input('glassProdLineID');
        $schedate = date('Y/m/d', strtotime($request->input('schedate')));
        $view = $request->input('view');
        switch ($view) {
            case 'plan':
                $table = $this->planDetail;
                break;
            case 'run':
                $table = $this->runDetail;
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
                $table = $this->planDetail;
                break;
            case 'run':
                $table = $this->runDetail;
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

    public function getDutyList($request)
    {
        $snm = $request->input('snm');
        $runProdLineID = $request->input('glassProdLineID');
        $dutyDateOp = 'like';
        $dutyDate = '%' . $request->input('dutyDate') . '%';
        if ($dutyDate != '%%') {
            $dutyDateOp = '=';
            $dutyDate = date('Y-m-d', strtotime($request->input('dutyDate')));
        }
        $schedule = $this->duty;
        $scheduleList = $schedule
            ->join('allGlassRun', function ($join) {
                $join->on('productionDuty.glassProdLineID', 'allGlassRun.glassProdLineID')
                    ->on('productionDuty.prd_no', 'allGlassRun.prd_no')
                    ->on('productionDuty.schedate', 'allGlassRun.schedate');
            })
            ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
            ->where('allGlassRun.PRDT_SNM', 'like', "%$snm%")
            ->where('productionDuty.glassProdLineID', 'like', "%$runProdLineID%")
            ->where('productionDuty.dutyDate', $dutyDateOp, $dutyDate)
            ->orderBy('dutyDate', 'desc')->orderBy('shift')->orderBy('glassProdLineID')
            ->select('productionDuty.id', 'productionDuty.glassProdLineID', 'dutyDate', 
                'shift', 'UPGWeb.dbo.vStaffNode.name as staffName', 'allGlassRun.PRDT_SNM as snm', 
                'quantity', 'pack', 'productionDuty.efficiency', 'annealGrade', 'startShutdown', 'endShutdown');
        $a = $scheduleList->get();
        return $scheduleList;
    }

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
        $a = $formSchedule->get();
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
        $b = $formTestModel->get();
        return $formTestModel;
    }

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
        $a = $formSchedule->get();
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
        $b = $formTestModel->get();
        return $formTestModel;
    }

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

    public function getStaff()
    {
        $list = $this->staff->where('serving', 1);
        return $list;
    }

    public function getCustomer()
    {
        $list = $this->customer->orderby('name');
        return $list;
    }

    public function getGlass()
    {
        $list = $this->glass->orderBy('snm');
        return $list;
    }

    public function getTaskDetailByPrdNO($prd_no)
    {
        $list = $this->taskDetail->where('PRD_NO', $prd_no);
        return $list;
    }

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

    public function getDuty($id)
    {
        $data = $this->duty->where('productionDuty.id', $id);
        if ($data->exists()) {
            $data = $data
                ->join('allGlassRun', function ($join) {
                    $join->on('productionDuty.glassProdLineID', 'allGlassRun.glassProdLineID')
                        ->on('productionDuty.prd_no', 'allGlassRun.prd_no')
                        ->on('productionDuty.schedate', 'allGlassRun.schedate');
                })
                ->join('UPGWeb.dbo.vStaffNode', 'UPGWeb.dbo.vStaffNode.ID', 'productionDuty.staffID')
                ->orderBy('dutyDate', 'shift', 'glassProdLineID')
                ->select('productionDuty.id', 'productionDuty.glassProdLineID', 'productionDuty.schedate', 'productionDuty.prd_no', 
                    'dutyDate', 'shift', 'UPGWeb.dbo.vStaffNode.ID as staffID', 'UPGWeb.dbo.vStaffNode.name as staffName', 
                    'allGlassRun.PRDT_SNM as snm', 'quantity', 'pack', 'productionDuty.efficiency', 'annealGrade', 
                    'startShutdown', 'endShutdown', 'jobChange', 'speedChange', 'improve');
            return $data;
        }
        return null;
    }

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

    public function saveDuty($input)
    {
        $table = $this->duty;
        $result = $this->save($table, $input);
        return $result;
    }

    public function saveHistory($input)
    {
        $table = $this->history;
        $result = $this->save($table, $input, [], 'id', true);
        return $result;
    }

    public function saveQC($input)
    {
        $table = $this->qc;
        $result = $this->save($table, $input);
        return $result;
    }

    public function saveFile($file)
    {
        return $this->common->saveFile($file);
    }

    public function saveOldSchedule($params)
    {
        $table = $this->oldSchedule;
        $params['machno'] = $this->getMachno($params['glassProdLineID']);
        $result = $this->save($table, $params, [], 'id', true);
        return $result;
    }

    public function saveTask($input)
    {
        $table = $this->task;
        $result = $this->save($table, $input);
        return $result;
    }

    public function deleteTask($id)
    {
        $table = $this->task;
        $result = $this->delete($table, $id);
        return $result;
    }

    public function deleteHistory($id)
    {
        $table = $this->history;
        $result = $this->delfete($table, $id);
        return $result;
    }

    public function deleteQC($id)
    {
        $table = $this->qc;
        $result = $this->delfete($table, $id);
        return $result;
    }

    private function getMachno($str)
    {
        switch ($str) {
            case 'L1-1':
                return '1-1 1-1線';
                break;
            case 'L1':
                return '01 1線';
                break;
            case 'L2':
                return '02 2線';
                break;
            case 'L3':
                return '03 3線';
                break;
            case 'L5':
                return '04 5線';
                break;
            case 'L6':
                return '05 6線';
                break;
            case 'L7':
                return '06 7線';
                break;
            case 'L8':
                return '07 8線';
                break;
            default:
                return null;
        }
    }
}