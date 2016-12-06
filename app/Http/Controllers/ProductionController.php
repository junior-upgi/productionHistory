<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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

    public function scheduleList()
    {
        $request = request();
        $list = $this->production->getSchedule($request)->paginate(20);

        return view('duty.schedule')
            ->with('list', $list)
            ->with('prd_no', $request->input('prd_no'))
            ->with('machno', $request->input('machno'));            
    }

    public function dutyList()
    {
        $request = request();
        $page = $request->input('page', 1);
        $paginate = 20;
        $list = $this->production->getDutyList($request)->get()->toArray();

        $slice = array_slice($list, $paginate * ($page - 1), $paginate);
        $paginator = new Paginator($slice, count($list), $paginate);
        $result = $paginator;

        return view('duty.list')
            ->with('list', $result)
            ->with('prd_no', $request->input('prd_no'))
            ->with('machno', $request->input('machno'));  
    }

    public function getSchedule()
    {
        $id = request()->input('id');
        $data = $this->production->getTable('schedule')->scheduleList()->where('mk_no', $id)
            ->select('mk_no', 'NAME', 'machno')->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
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

    public function saveDuty()
    {
        $request = request();
        $input = $request->input();
        $ignore = ['mk_no', 'snm', 'machno'];
        $result = $this->production->saveDuty($input);
        return $result;
    }
}