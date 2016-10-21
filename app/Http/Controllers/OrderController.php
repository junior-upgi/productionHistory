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
            $search = array(
                'key' => 'COMB_ITEM_NAME',
                'op' => 'like',
                'value' => "%$value%" 
            );
            array_push($where, $search);
            $order = $this->order->getOrderWhere($where)
                ->orderBy('OS_NO')
                ->orderBy('ITM')
                ->get();
        }
        return view('order.orderSearch')
            ->with('order', $order)
            ->with('search', $searchContent);
    }


}