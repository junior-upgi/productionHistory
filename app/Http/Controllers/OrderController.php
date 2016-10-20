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

    public function orderSearch()
    {   
        return view('order.orderSearch');
    }

}