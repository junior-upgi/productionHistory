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

/**
 * Class ItemService
 *
 * @package App\Service
 */
class ItemService
{
    /** Itemrepository $item */
    public $item;

    /** 
     * construct
     *
     * @param ItemRepository $defect
     */
    public function __conscruct(ItemRepository $item)
    {
        $this->item = $item;
    }

    /**
     * 上層項目新增資料處理
     *
     * @param Request->input() $input
     *
     * @return Array
     */
    public function insertItem($input)
    {
        $mainParams = $this->setInsertMainParams($input['main']);
        $detailParams = $this->setInsertDetailParams($input['detail'], $mainParams['id']);
        return $this->item->insertItem($mainParams, $detailParams);
    }

    /**
     * 上層項目更新資料處理
     *
     * @param Request->input() $input
     * 
     * @return Array
     */
    public function updateItem($input)
    {
        $mainParams = $this->setUpdateMainParams($input['main']);
        $detailParams = $this->setUpdateDetailParams($input['detail'], $mainParams['id']);
        return $this->item->updateItem($mainParams, $detailParams);
    }

    /**
     * 設定新增上層項目主表參數
     *
     * @param Array $main
     *
     * @return Array
     */
    private function setInsertMainParams($main)
    {
        $main = array_except($main, ['type']);
        $main['id'] = $this->item->common->getNewGUID();
        $main['name'] = iconv('utf8', 'big5', $main['name']);
        $main['created_at'] = \Carbon\Carbon::now();
        $main['created_by'] = \Auth::user()->erpID;
        return $main;
    }

    /**
     * 設定新增上層項目已選缺點參數
     *
     * @param Array $detail
     * @param string $id
     *
     * @return Array
     */
    private function setInsertDetailParams($detail, $id)
    {
        $params = [];
        $now = \Carbon\Carbon::now();
        $user = \Auth::user()->erpID;
        for ($i = 0; $i < count($detail); $i++) {
            $param['itemID'] = $id;
            $param['defectID'] = $detail[$i]['id'];
            $param['sequence'] = $i;
            $param['vauleType'] = $detail[$i]['valueType'];
            $param['created_at'] = $now;
            $param['created_by'] = $user;
            array_push($params, $param);
        }
        return $params;
    }

    /**
     * 設定更新上層項目主表參數
     *
     * @param Array $main
     *
     * @return Array
     */
    private function setUpdateMainParams($main)
    {
        $main = array_except($main, ['type']);
        $main['name'] = iconv('utf8', 'big5', $main['name']);
        $main['updated_at'] = \Carbon\Carbon::now();
        $main['updated_by'] = \Auth::user()->erpID;
        return $main;
    }

    /**
     * 設定更新上層項目已選缺點參數
     *
     * @param Array $detail
     * @param string $id
     *
     * @return Array
     */
    private function setUpdateDetailParams($detail, $id)
    {
        $params = [];
        $now = \Carbon\Carbon::now();
        $user = \Auth::user()->erpID;
        for ($i = 0; $i < count($detail); $i++) {
            $param['itemID'] = $id;
            $param['defectID'] = $detail[$i]['id'];
            $param['sequence'] = $i;
            $param['vauleType'] = $detail[$i]['valueType'];
            $param['updated_at'] = $now;
            $param['updated_by'] = $user;
            array_push($params, $param);
        }
        return $params;
    }
}