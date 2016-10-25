<?php
/**
 * order相關資料邏輯處理
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/20
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\OrderRepository;

use App\Models\Parameter;


/**
 * Class OrderController
 *
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /** @var OrderRepository 注入OrderRepository */
    private $order;

    /**
     * 建構式
     *
     * @param OrderRepository $order
     * @return void
     */
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    public function orderSearch(Request $request)
    {   
        $order = null;
        $searchContent = $request->input('searchContent');
        if (isset($searchContent)) {
            $where = array();
            $value = iconv("UTF-8", "BIG-5", $searchContent);
            $osno = array(
                'key' => 'OS_NO',
                'op' => 'like',
                'value' => "%$value%",
                'or' => true,
            );
            $comb = array(
                'key' => 'COMB_ITEM_NAME',
                'op' => 'like',
                'value' => "%$value%",
                'or' => true,
            );
            array_push($where, $osno);
            array_push($where, $comb);
            $order = $this->order->getProductHistoryWhere($where)
                ->orderBy('OS_NO')
                ->orderBy('ITM')
                ->get();
        }
        return view('order.orderSearch')
            ->with('order', $order)
            ->with('search', $searchContent);
    }

    public function insert(Request $request, $cat)
    {
        $a = new Parameter();
        //return var_dump( $a->first() );
        $re = $request;
        $input = $re->input();
        /*
        $textbox = $re->input('textbox');
        $textarea = $re->input('textarea');
        $droplist = $re->input('droplist');
        $checkbox = $re->input('checkbox');
        $radiobox = $re->input('radiobox');
        $matrix = $re->input('matrix');
        $dytextbox = $re->input('dytextbox');
        */

        $countInput = count($re->input());
        $now = \Carbon\Carbon::now();
        /*
        $params = array(
            'OS_NO' => $input['os_no'],
            'ITM' => $input['itm'],
            'created' => '2016-10-10',
            'department' => $input['department'],
            'gobTemp' => $input['gobtemp'],
            'sectCount' => $input['sectcount'],
            'gobWeight' => $input['gobweight'],
            'shearCount' => $input['shearcount'],
            'gobsPerCut' => $input['gobspercut'],
        );
        */
        $params = array(
            'OS_NO' => 111,
            'ITM' => 111,
            'created' => '2016-10-10',
            'department' => 111,
            'gobTemp' => 111,
            'sectCount' => 111,
            'gobWeight' => 111,
            'shearCount' => 111,
            'gobsPerCut' => 111,
        );
        $ins = $this->order->insert($params);

        
        return $ins;
    }

}