<?php
/**
 * ItemController資料處理服務
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/1/17
 * @since 1.0.0 spark: 於此版本開始編寫註解，已優化程式碼
 */
namespace App\Service;

use App\Repositories\ItemRepository;
use Carbon\Carbon;

/**
 * Class ItemService
 *
 * @package App\Service
 */
class ItemService
{
    use UserService;

    /** ItemRepository $item */
    public $item;

    /** 
     * construct
     *
     * @param ItemRepository $item
     */
    public function __construct(ItemRepository $item)
    {
        $this->item = $item;
    }

    /**
     * 上層項目新增資料處理
     *
     * @param Request->input() $input
     *
     * @return array
     */
    public function insertItem($input)
    {
        $mainParams = $this->setInsertMainParams($input['mainData']);
        $detailParams = $this->setInsertDetailParams($input['detailData'], $mainParams['id']);
        return $this->item->insertItem($mainParams, $detailParams);
    }

    /**
     * 上層項目更新資料處理
     *
     * @param Request->input() $input
     * 
     * @return array
     */
    public function updateItem($input)
    {
        $mainParams = $this->setUpdateMainParams($input['mainData']);
        $detailParams = $this->setUpdateDetailParams($input['detailData'], $mainParams['id']);
        return $this->item->updateItem($mainParams, $detailParams);
    }

    /**
     * 設定新增上層項目主表參數
     *
     * @param array $main
     *
     * @return array
     */
    private function setInsertMainParams($main)
    {
        $main['id'] = $this->template->common->getNewGUID();
        return $this->setMainParams($main, 'created');
    }

    /**
     * 設定更新上層項目主表參數
     *
     * @param array $main
     *
     * @return array
     */
    private function setUpdateMainParams($main)
    {
        return $this->setMainParams($main, 'updated');
    }

    /**
     * 設定上層項目主表參數
     *
     * @param $main
     * @param $type
     * @return array
     */
    private function setMainParams($main, $type)
    {
        $main = array_except($main, ['type']);
        $main['name'] = iconv('utf8', 'big5', $main['name']);
        $main[$type.'_at'] = Carbon::now();
        $main[$type.'_by'] = $this->getErpID();
        return $main;
    }

    /**
     * 設定新增上層項目已選缺點參數
     *
     * @param array $detail
     * @param string $id
     *
     * @return array
     */
    private function setInsertDetailParams($detail, $id)
    {
        return $this->setDetailParams($detail, $id, 'created');
    }

    /**
     * 設定更新上層項目已選缺點參數
     *
     * @param array $detail
     * @param string $id
     *
     * @return array
     */
    private function setUpdateDetailParams($detail, $id)
    {
        return $this->setDetailParams($detail, $id, 'updated');
    }

    /**
     * 設定上層項目己選缺點參數
     *
     * @param $detail
     * @param $id
     * @param $type
     * @return array
     */
    private function setDetailParams($detail, $id, $type)
    {
        $params = [];
        $now = Carbon::now();
        for ($i = 0; $i < count($detail); $i++) {
            $param['itemID'] = $id;
            $param['defectID'] = $detail[$i]['id'];
            $param['sequence'] = $i;
            $param[$type.'_at'] = $now;
            $param[$type.'_by'] = $this->getErpID();
            array_push($params, $param);
        }
        return $params;
    }
}