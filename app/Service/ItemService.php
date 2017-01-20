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
use Auth;

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
     * @param array $detail
     * @param string $id
     *
     * @return array
     */
    private function setInsertDetailParams($detail, $id)
    {
        $params = [];
        $now = \Carbon\Carbon::now();
        for ($i = 0; $i < count($detail); $i++) {
            $param['itemID'] = $id;
            $param['defectID'] = $detail[$i]['id'];
            $param['sequence'] = $i;
            $param['vauleType'] = $detail[$i]['valueType'];
            $param['created_at'] = $now;
            $param['created_by'] = $this->getErpID();
            array_push($params, $param);
        }
        return $params;
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
        $main = array_except($main, ['type']);
        $main['name'] = iconv('utf8', 'big5', $main['name']);
        $main['updated_at'] = \Carbon\Carbon::now();
        $main['updated_by'] = $this->getErpID();
        return $main;
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
        $params = [];
        $now = \Carbon\Carbon::now();
        for ($i = 0; $i < count($detail); $i++) {
            $param['itemID'] = $id;
            $param['defectID'] = $detail[$i]['id'];
            $param['sequence'] = $i;
            $param['updated_at'] = $now;
            $param['updated_by'] = $this->getErpID();
            array_push($params, $param);
        }
        return $params;
    }
}