<?php
namespace App\Http\Controllers;

use App\Service\QCService;
use App\Repositories\QCRepository;

/**
 * Class QCController
 * @package App\Http\Controllers
 */
class QCController extends Controller
{
    /**
     * @var QCRepostiry|QCRepository
     */
    public $qc;
    public $service;

    /**
     * 建構式
     *
     * @param QCRepository $qc 注入QCRepository
     * @param QCService $service
     */
    public function __construct(QCRepository $qc, QCService $service)
    {
        $this->qc = $qc;
        $this->service = $service;
    }

    /**
     * 取得員工清單
     *
     * @return array
     */
    public function getStaff()
    {
        return [
            'message' => '',
            'value' => $this->qc->getStaff()->get()->toArray()
        ];
    }

    /**
     * 取得顧客清單
     *
     * @return array
     */
    public function getCustomer()
    {
        return [
            'message' => '',
            'value' => $this->qc->getCustomer()->get()->toArray()
        ];
    }

    /**
     * 取得排程資料
     *
     * @return array
     */
    public function getQCSchedule()
    {
        $request = request();
        $result = $this->qc->getSchedule($request);
        $data = $result['data']->toArray();
        $data['schedate'] = date('Y-m-d', strtotime($data['schedate']));
        $data['customer'] = $this->qc->getScheduleCustomer($request);
        $data['recentProdDefectList'] = $this->qc->getQCDefect($data['prd_no']);
        $data['packRate'] = $this->qc->getQCPackRate($data['prd_no']);
        $data['note'] = $this->qc->getQCNote($request);
        $data['decoration'] = $this->qc->getQCDecoration($request);

        $result['data'] = $data;
        return $result;
    }

    /**
     * 取得排程清單
     *
     * @return \Illuminate\View\View
     */
    public function qcSchedule()
    {
        return view('qc.schedule')
            ->with('list', $this->qc->getScheduleList('plan', request())->paginate(20))
            ->with('snm', request()->input('snm'))
            ->with('glassProdLineID', request()->input('glassProdLineID'))
            ->with('schedate', request()->input('schedate'));
    }

    /**
     * 取得品管清單
     *
     * @return \Illuminate\View\View
     */
    public function qcList()
    {
        return view('qc.list')
            ->with('list', $this->qc->getQCList(request())->paginate(20))
            ->with('snm', request()->input('snm'))
            ->with('glassProdLineID', request()->input('glassProdLineID'))
            ->with('schedate', request()->input('schedate'));
    }

    /**
     * 取得品管資料
     *
     * @return array
     */
    public function getQC()
    {
        return $this->qc->getQC(request()->input('id'))->first();
    }

    /**
     * 儲存品管資料
     *
     * @return array|mixed
     */
    public function saveQC()
    {
        return $this->service->saveQC(request());
    }

    /**
     * 刪除品管資料
     *
     * @return mixed
     */
    public function deleteQC()
    {
        return $this->qc->deleteQC(request()->input('id'));
    }
}