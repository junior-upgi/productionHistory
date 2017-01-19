<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use App\Repositories\QCRepository;

//
class QCController extends Controller
{
    //
    public $qc;
    
    /**
     * 建構式
     * 
     * @param QCRepostiry $qc 注入QCRepository
     */
    public function __construct(QCRepository $qc) 
    {
        $this->qc = $qc;
    }

    //*****
    public function getStaff()
    {
        $staff = $this->qc->getStaff()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    //*****
    public function getCustomer()
    {
        $staff = $this->qc->getCustomer()->get()->toArray();
        $json = [];
        $json['message'] = '';
        $json['value'] = $staff;
        return $json;
    }

    //*****
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

    //******
    public function qcSchedule()
    {
        $request = request();
        $list = $this->qc->getScheduleList('plan', $request)->paginate(20);

        return view('qc.schedule')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));    
    }

    //*****
    public function qcList()
    {
        $request = request();
        $list = $this->qc->getQCList($request)->paginate(20);
        return view('qc.list')
            ->with('list', $list)
            ->with('snm', $request->input('snm'))
            ->with('glassProdLineID', $request->input('glassProdLineID'))
            ->with('schedate', $request->input('schedate'));  
    }

    //****
    public function getQC()
    {
        $id = request()->input('id');
        $data = $this->qc->getQC($id)->first();
        if (isset($data)) {
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];
    }

    
    //*****
    public function saveQC()
    {
        $request = request();
        $input = $request->input();
        $file = $request->file('setDraw');
        if (isset($file)) {
            $fileID = $this->qc->saveFile($file);
            if (!isset($fileID)) {
                return ['success' => false, 'msg' => '檔案上傳失敗'];
            }
            $input['draw'] = $fileID;
        } else {
            $input['draw'] = $input['setDraw'];
            $input = array_except($input, ['setDraw']);
        }
        //$a = implode(',', $input);
        if (isset($input['fullInspection'])) {
            $input['fullInspection'] = implode(',', $input['fullInspection']);
        }
        $result = $this->qc->saveQC($input);
        return $result;
    }

    //******
    public function deleteQC()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->qc->deleteQC($id);
        return $result;        
    }
}