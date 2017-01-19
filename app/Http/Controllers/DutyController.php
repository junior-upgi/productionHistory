<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use App\Repositories\DutyRepository;

//
class DutyController extends Controller
{
    //
    public $duty;
    
    /**
     * construct
     * 
     * @param DutyRepostiry $duty
     */
    public function __construct(DutyRepository $duty) 
    {
        $this->duty= $duty;
    }

    //****
    public function getStaff()
    {
        $staff = $this->duty->getStaff()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    //****
    public function getSchedule()
    {
        $request = request();
        $data = $this->duty->getSchedule($request);
        return $data;
    }

    //**
    public function dutySchedule()
    {
        $request = request();
        $list = $this->duty->getScheduleList('allGlass', $request)->paginate(20);

        return view('duty.schedule')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));       
    }

    //***
    public function dutyList()
    {
        $request = request();
        $list = $this->duty->getDutyList($request)->paginate(20);
        return view('duty.list')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('dutyDate', $request->input('dutyDate'));  
    }

    //****
    public function getDuty()
    {
        $id = request()->input('id');
        $data = $this->duty->getDuty($id)->first();
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

    //****
    public function saveDuty()
    {
        $request = request();
        $input = $request->input();
        $result = $this->duty->saveDuty($input);
        return $result;
    }

}