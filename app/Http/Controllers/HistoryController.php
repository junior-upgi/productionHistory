<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use App\Repositories\HistoryRepository;
use App\Service\HistoryService;

/**
 * Class HistoryController
 * @package App\Http\Controllers
 */
class HistoryController extends Controller
{
    //
    public $history;
    public $service;
    
    /**
     * 建構式
     * 
     * @param HistoryRepostiry $history 注入HistoryRepository
     */
    public function __construct(HistoryRepository $history, HistoryService $service) 
    {
        $this->history = $history;
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getStaff()
    {
        $staff = $this->history->getStaff()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    /**
     * @return array
     */
    public function getCustomer()
    {
        $staff = $this->history->getCustomer()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    /**
     * @return array
     */
    public function getGlass()
    {
        $glass = $this->history->getGlass()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $glass;
        return $json;
    }

    /**
     * @return array
     */
    public function getSchedule()
    {
        $request = request();
        $data = $this->history->getSchedule($request);
        return $data;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function historySchedule()
    {
        $request = request();
        $list = $this->history->getScheduleList('run', $request)->paginate(20);

        return view('history.schedule')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));            
    }

    /**
     * @return \Illuminate\View\View
     */
    public function historyList()
    {
        $request = request();
        $page = $request->input('page', 1);
        $paginate = 20;

        $list = $this->history->getHistoryList($request)->get()->toArray();

        $offSet = ($page * $paginate) - $paginate;
		$itemsForCurrentPage = array_slice($list, $offSet, $paginate, true);
        $result = new LengthAwarePaginator($itemsForCurrentPage, count($list), $paginate, $page);

        return view('history.list')
            ->with('list', $result)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate')); 
    }

    /**
     * @return array
     */
    public function getHistory()
    {
        $id = request()->input('id');
        $data = $this->history->getHistory($id)->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    /**
     * @return array|mixed
     */
    public function saveHistory()
    {
        $request = request();
        $input = $request->input();

        $history_params = $this->service->saveHistoryPrework($input);
        if (isset($history_params['success'])) {
            return $history_params;
        }

        if ($history_params['sampling'] == '--') {
            $history_params = array_except($history_params, ['cus_no', 'orderQty', 'sampling']);
            $result = $this->history->saveHistory($history_params);
            return $result;
        } else {
            $params = $this->service->getScheduleParams($history_params);
            $history_params = array_except($history_params, ['orderQty']);
            $result = $this->history->saveHistory($history_params);
            if ($result['success']) {
                $insertOld = $this->history->saveOldSchedule($params);
                if (!$insertOld) {
                    return $insertOld;
                }
            }
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function deleteHistory()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->history->deleteHistory($id);
        return $result;        
    }
}