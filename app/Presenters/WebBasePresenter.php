<?php
/**
 * 前端頁面基本處理方法
 *
 * @version 1.0.1
 * @author spark it@upgi.com.tw
 * @date 16/10/21
 * @since 1.0.0 spark: 於此版本開始編寫註解
 * @since 1.0.1 spark: 已可動態載入表單連結
 */
namespace App\Presenters;

use App\Repositories\OrderRepository;

/**
 * Class WebBasepresenter
 *
 * @package App\Presenters
 */
class WebBasePresenter
{
    /** @var OrderRepository 注入OrderRepository */
    private $order;

    /**
     * 建構式
     *
     * @return void
     */
    public function __construct(OrderRepository $order) {
        $this->order = $order;
    }

    /**
    * 轉換日期格式
    * 
    * @return string 回傳string 日期
    */
    public function getDate($date)
    {
        if (isset($date)) {
            return date('Y-m-d', strtotime($date));
        }
        return '';
    }

    public function getFormLink($order, $table, $unit)
    {
        $OS_NO = $order->OS_NO;
        $ITM = $order->ITM;
        $COMB_ITEM_NAME = str_replace("#", "%23", $order->COMB_ITEM_NAME);
        $department = $order->department;
        $process = $order->process;
        $productionLine = $order->productionLine;
        $params = "?OS_NO=$OS_NO&ITM=$ITM&department=$department&process=$process&productionLine=$productionLine&COMB_ITEM_NAME=$COMB_ITEM_NAME";
        $formLink = $this->order->getFormLink($table, $unit);
        $url = '#';
        if (isset($formLink)) {
            $url = $formLink . $params;
        }
        return $url;
    }

    public function getPic($src)
    {
        if (isset($src)) {
            return "<span class='glyphicon glyphicon-picture' 
                onclick=\"showimage('$src')\"></span>";
        }
        return '';
    }

    public function getPicAjax($order)
    {
        $no = $order->OS_NO;
        $item = $order->ITM;
        $prc = $order->process;
        $dep = $order->department;
        $pl = $order->productionLine;
        $src = $order->image;
        if (isset($src)) {
            return "<span class='glyphicon glyphicon-picture' 
                onclick=\"showimageAjax('$no', '$item', '$prc', '$dep', '$pl')\"></span>";
        }
        return '';
    }
}