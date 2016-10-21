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

    /**
    * 取得所有OrderProduct資料
    * 
    * @return OrderProduct 回傳Module
    */
    public function getOrderList()
    {
        $list = $this->order->get();
        return $list;
    }

    /**
    * 依orderID取得資料
    * 
    * @param array $where 注入查詢條件
    * @return OrderProduct 回傳Module
    */
    public function getOrderWhere($where)
    {
        $list = $this->order
            ->where(function ($q) use ($where) {
                foreach ($where as $w) {
                    $key = $w['key'];
                    $op = (!isset($w['op'])) ? '=' : $w['op'];
                    $value = $w['value'];
                    $or = (!isset($w['or'])) ? false : $w['or'];
                    if ($or) {
                        $q->orWhere($key, $op, $value);
                    } else {
                        $q->where($key, $op, $value);
                    }
                }
            });
        return $list;
    }
}