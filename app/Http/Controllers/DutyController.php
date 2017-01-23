<?php
/**
 * DutyController
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/23
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers;

use Auth;
use App\Repositories\DutyRepository;

/**
 * Class DutyController
 * @package App\Http\Controllers
 */
class DutyController extends Controller
{
    /**
     * @var DutyRepository
     */
    public $duty;

    /**
     * construct
     *
     * @param DutyRepository $duty
     */
    public function __construct(DutyRepository $duty) 
    {
        $this->duty= $duty;
    }

    /**
     * 取得員工資料
     *
     * @return array
     */
    public function getStaff()
    {
        $staff = $this->duty->getStaff()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    /**
     * 取得排程資料
     *
     * @return array
     */
    public function getSchedule()
    {
        $data = $this->duty->getSchedule(request());
        return $data;
    }

    /**
     * 回傳 duty.schedule view
     *
     * @return \Illuminate\View\View
     */
    public function dutySchedule()
    {
        return view('duty.schedule')
            ->with('list', $this->duty->getScheduleList('allGlass', request())->paginate(20))
            ->with('snm', request()->input('snm'))
            ->with('glassProdLineID', request()->input('glassProdLineID'))
            ->with('schedate', request()->input('schedate'));
    }

    /**
     * 回傳 duty.list view
     *
     * @return \Illuminate\View\View
     */
    public function dutyList()
    {
        return view('duty.list')
            ->with('list', $this->duty->getDutyList(request())->paginate(20))
            ->with('snm', request()->input('snm'))
            ->with('glassProdLineID', request()->input('glassProdLineID'))
            ->with('dutyDate', request()->input('dutyDate'));
    }

    /**
     * @return array
     */
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

    /**
     * @return mixed
     */
    public function saveDuty()
    {
        $request = request();
        $input = $request->input();
        $result = $this->duty->saveDuty($input);
        return $result;
    }

}