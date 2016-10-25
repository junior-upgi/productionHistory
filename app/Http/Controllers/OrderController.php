<?php
/**
 * order相關資料邏輯處理
 *
 * @version 1.0.1
 * @author spark it@upgi.com.tw
 * @date 16/10/20
 * @since 1.0.0 spark: 於此版本開始編寫註解
 * @since 1.0.1 spark: 初步完成表單接收資料並寫入資料庫
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

    /**
     * 取得表單提交資料
     * 
     * @param Request $request request
     * @param string $table 資料表名稱
     * @return string 回傳新增結果
     */
    public function formSubmit(Request $request, $table)
    {
        $input = $request->input();
        $now = \Carbon\Carbon::now();
        $ignore = ['submission_id', 'formID', 'ip'];    //欲忽略的key
        $input = array_except($input, $ignore);         //移除忽略的key
        $countInput = count($input);
        $params = array();
        list($key, $value) = array_divide($input);      //分拆key & value
        for ($i = 0; $i < $countInput; $i++) {
            $params[$key[$i]] = iconv("UTF-8", "BIG-5", $value[$i]);             //寫入array
        }
        $ins = $this->order->insertForm($table, $params);

        
        return $ins->msg;
    }

}