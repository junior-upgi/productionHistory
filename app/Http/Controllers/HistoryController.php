<?php
/**
 * 生產履歷controller
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/1/23
 * @since 1.0.0 spark: 於此版本開始編寫註解，已優化程式碼
 */
namespace App\Http\Controllers;

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
    /**
     * @var HistoryRepository
     */
    public $history;
    /**
     * @var HistoryService
     */
    public $service;

    /**
     * 建構式
     *
     * @param HistoryRepository|HistoryRepository $history 注入HistoryRepository
     * @param HistoryService $service
     */
    public function __construct(
        HistoryRepository $history, HistoryService $service
    ) {
        $this->history = $history;
        $this->service = $service;
    }

    /**
     *
     *
     * @return array
     */
    public function getStaff()
    {
        return [
            'message' => '',
            'value' => $this->history->getStaff()->get()->toArray()
        ];
    }

    /**
     *
     *
     * @return array
     */
    public function getCustomer()
    {
        return [
            'message' => '',
            'value' => $this->history->getCustomer()->get()->toArray()
        ];
    }

    /**
     *
     *
     * @return array
     */
    public function getGlass()
    {
        return [
            'message' => '',
            'value' => $this->history->getGlass()->get()->toArray()
        ];
    }

    /**
     *
     *
     * @return array
     */
    public function getSchedule()
    {
        return $this->history->getSchedule(request());
    }

    /**
     *
     *
     * @return \Illuminate\View\View
     */
    public function historySchedule()
    {
        return view('history.schedule')
            ->with('list',$this->history->getScheduleList('run', request())->paginate(20))
            ->with('snm', request()->input('snm'))
            ->with('glassProdLineID', request()->input('glassProdLineID'))
            ->with('schedate', request()->input('schedate'));
    }

    /**
     *
     *
     * @return \Illuminate\View\View
     */
    public function historyList()
    {
        $page = request()->input('page', 1);
        $paginate = 20;

        $list = $this->history->getHistoryList(request())->get()->toArray();

        $offSet = ($page * $paginate) - $paginate;
		$itemsForCurrentPage = array_slice($list, $offSet, $paginate, true);
        $result = new LengthAwarePaginator($itemsForCurrentPage, count($list), $paginate, $page);

        return view('history.list')
            ->with('list', $result)
            ->with('snm', request()->input('snm'))
            ->with('glassProdLineID', request()->input('glassProdLineID'))
            ->with('schedate', request()->input('schedate'));
    }

    /**
     *
     *
     * @return array
     */
    public function getHistory()
    {
        $data = $this->history->getHistory(request()->input('id'))->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    /**
     *
     *
     * @return array|mixed
     */
    public function saveHistory()
    {
        return $this->service->saveHistoryPrework(request()->input());
    }

    /**
     *
     *
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