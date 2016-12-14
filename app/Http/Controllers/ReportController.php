<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;

use App\Repositories\ProductionRepository;

class ReportController extends Controller
{
    public $prouction;
    

    public function __construct(ProductionRepository $production) 
    {
        $this->production = $production;
    }

    public function getHistory()
    {
        $request = request();
        $input = $request->input();
        $snm = $input['snm'];
        try {
            $historyList = $this->production->getHistoryList($request)->get()->toArray();
            $qcData = $this->production->getQCList($request)->first()->toArray();
            $info = null;

            return [
                'success' => true, 
                'historyList' => $historyList,
                'qc' => $qcData,
                'info' => $info,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'msg' => $e,
            ];
        }
    }
}
