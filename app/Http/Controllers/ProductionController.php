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
        $id = request()->input('id');
        $data = $this->production->getTable('schedule')->scheduleList()->where('mk_no', $id)
            ->select('mk_no', 'DB_U105.dbo.PRDT.NAME', 'machno', 'Z_DB_U105.dbo.tbmkno.cus_no as cus_no',
                'UPGWeb.dbo.vCustomer.name as customerName', 'UPGWeb.dbo.vCustomer.sname as customerSName')->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    public function dutySchedule()
    {
        $request = request();
        $list = $this->production->getSchedule($request)->paginate(20);

        return view('duty.schedule')
            ->with('list', $list)
            ->with('pname', $request->input('pname'))
            ->with('machno', $request->input('machno'));            
    }

    public function historySchedule()
    {
        $request = request();
        $list = $this->production->getSchedule($request)->paginate(20);

        return view('history.schedule')
            ->with('list', $list)
            ->with('pname', $request->input('pname'))
            ->with('machno', $request->input('machno'));            
    }

    public function dutyList()
    {
        $request = request();
        $list = $this->production->getDutyList($request)->paginate(20);

        return view('duty.list')
            ->with('list', $list)
            ->with('pname', $request->input('pname'))
            ->with('machno', $request->input('machno'));  
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
            ->with('pname', $request->input('pname'))
            ->with('machno', $request->input('machno'));  
    }

    public function getDuty()
    {
        $id = request()->input('id');
        $data = $this->production->getDuty($id)->first();
        $data['startShutdown'] = date('h:i', strtotime($data['startShutdown']));
        $data['endShutdown'] = date('h:i', strtotime($data['endShutdown']));
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
        if ($input['mk_no'] != '--') {
            $input = array_except($input, ['snm', 'cus_no']);
        }
        $result = $this->production->saveHistory($input);
        return $result;
    }
}