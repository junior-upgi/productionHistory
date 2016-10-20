<?php
/**
 * order相關資料邏輯處理
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/20
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use App\Models\OrderProduct;

/**
 * Class OrderRepository
 *
 * @package App\Repositories
 */
class OrderRepository
{
    /** @var OrderProduct 注入OrderProduct */
    private $order;


    /**
     * 建構式
     *
     * @param OrderProduct $order
     * @return void
     */
    public function __construct(OrderProduct $order)
    {
        $this->order = $order;
    }


    public function getOrderList()
    {
        $list = $this->order->get();
        return $list;
    }
}