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
use App\Models\History;
use App\Models\ProductHistory;
use App\Models\Parameter;

/**
 * Class OrderRepository
 *
 * @package App\Repositories
 */
class OrderRepository
{
    /** @var OrderProduct 注入OrderProduct */
    private $order;
    /** @var History 注入History */
    private $history;
    /** @var ProductHistory 注入ProductHistory */
    private $productHistory;

    private $parameter;

    /**
     * 建構式
     *
     * @param OrderProduct $order
     * @param History $history
     * @param ProductHistory $productHistory
     * @return void
     */
    public function __construct(
        OrderProduct $order,
        History $history,
        ProductHistory $productHistory,
        Parameter $parameter
    ) {
        $this->order = $order;
        $this->history = $history;
        $this->productHistory = $productHistory;
        $this->parameter = $parameter;
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
    * 依where條件取得Order資料
    * 
    * @param array $where 注入查詢條件
    * @return OrderProduct 回傳Module
    */
    public function getOrderWhere($where)
    {
        $table = $this->order;
        $obj = $this->getWhere($table, $where);
        return $obj;
    }

    /**
    * 依where條件取得ProductHistory資料
    * 
    * @param array $where 注入查詢條件
    * @return ProductHistory 回傳Module
    */
    public function getProductHistoryWhere($where)
    {
        $table = $this->productHistory;
        $obj = $this->getWhere($table, $where);
        return $obj;
    }

    /**
    * 依where條件取得資料
    * 
    * @param Model $table 傳入Model物件
    * @param array $where 傳入查詢條件
    * @return Model 回傳Module
    */
    private function getWhere($table, $where)
    {
        $obj = $table
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
        return $obj;
    }

    public function insert($params)
    {
        try{
            $table = $this->parameter;
            $table->insert($params);
            return true;
        } catch (\Exception $e) {
            return $e['errorInfo'][2];
        }
    }
}