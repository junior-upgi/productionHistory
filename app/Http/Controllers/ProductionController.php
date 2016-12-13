<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;

use App\Repositories\ProductionRepository;

class productionController extends Controller
{
    public $prouction;
    

    public function __construct(ProductionRepository $production) 
    {
        $this->production = $production;
    }

    public function getStaff()
    {
        $staff = $this->production->getStaff()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    public function getCustomer()
    {
        $staff = $this->production->getCustomer()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    public function getSchedule()
    {
        $request = request();
        $data = $this->production->getSchedule($request);
        return $data;
    }

    public function getQCSchedule()
    {
        $request = request();
        $result = $this->production->getSchedule($request);
        $data = $result['data']->toArray();
        $data['schedate'] = date('Y-m-d', strtotime($data['schedate']));
        $data['customer'] = $this->production->getScheduleCustomer($request);
        $data['recentProdDefectList'] = $this->production->getQCDefect($data['prd_no']);
        $data['packRate'] = $this->production->getQCPackRate($data['prd_no']);
        $data['note'] = $this->production->getQCNote($request);
        $data['decoration'] = $this->production->getQCDecoration($request);

        $result['data'] = $data;
        return $result;
    }

    public function dutySchedule()
    {
        $request = request();
        $list = $this->production->getScheduleList('allGlass', $request)->paginate(20);

        return view('duty.schedule')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));       
    }

    public function historySchedule()
    {
        $request = request();
        $list = $this->production->getScheduleList('glass', $request)->paginate(20);

        return view('history.schedule')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));            
    }

    public function qcSchedule()
    {
        $request = request();
        $list = $this->production->getScheduleList('plan', $request)->paginate(20);

        return view('qc.schedule')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));    
    }

    public function dutyList()
    {
        $request = request();
        $list = $this->production->getDutyList($request)->paginate(20);
        return view('duty.list')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('dutyDate', $request->input('dutyDate'));  
    }

    public function historyList()
    {
        $request = request();
        $page = $request->input('page', 1);
        $paginate = 20;

        $list = $this->production->getHistoryList($request)->get()->toArray();

        $offSet = ($page * $paginate) - $paginate;
		$itemsForCurrentPage = array_slice($list, $offSet, $paginate, true);
        $result = new LengthAwarePaginator($itemsForCurrentPage, count($list), $paginate, $page);

        return view('history.list')
            ->with('list', $result)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('productionDate', $request->input('productionDate')); 
    }

    public function qcList()
    {
        $request = request();
        $list = $this->production->getQCList($request)->paginate(20);
        return view('qc.list')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));  
    }

    public function getDuty()
    {
        $id = request()->input('id');
        $data = $this->production->getDuty($id)->first();
        if ($data['startShutdown'] == '1900-01-01 00:00:00') {
            $data['startShutdown'] = '';
        } else {
            $data['startShutdown'] = date('h:i', strtotime($data['startShutdown']));
        }
        if ($data['endShutdown'] == '1900-01-01 00:00:00') {
            $data['endShutdown'] = '';
        } else {
            $data['endShutdown'] = date('h:i', strtotime($data['endShutdown']));
        }
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    public function getHistory()
    {
        $id = request()->input('id');
        $data = $this->production->getHistory($id)->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    public function getQC()
    {
        $id = request()->input('id');
        $data = $this->production->getQC($id)->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    public function saveDuty()
    {
        $request = request();
        $input = $request->input();
        $result = $this->production->saveDuty($input);
        return $result;
    }

    public function saveHistory()
    {
        $request = request();
        $input = $request->input();
        if ($input['prd_no'] == '' && $input['schedate'] == '') {
            $input = array_except($input, ['prd_no', 'schedate']);
        } else {
            $input = array_except($input, ['snm', 'cus_no']);
        }
        $result = $this->production->saveHistory($input);
        return $result;
    }

    public function saveQC()
    {
        $request = request();
        $input = $request->input();
        $file = $request->file('draw');
        if (isset($file)) {
            $fileID = $this->production->saveFile($file);
            if (!isset($fileID)) {
                return ['success' => false, 'msg' => '檔案上傳失敗'];
            }
            $input['draw'] = $fileID;
        }
        $a = implode(',', $input);
        $result = $this->production->saveQC($input);
        return $result;
    }
}