<?php
/**
 * 上層項目管理controller
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/1/17
 * @since 1.0.0 spark: 於此版本開始編寫註解，已優化程式碼
 */
namespace App\Http\Controllers;

use App\Service\ItemService;

/**
 * Class ItemController
 *
 * @package App\Http\Controllers
 */
class ItemController extends BaseController
{
    /** ItemService $service */
    private $service;

    /** 
     * construct
     *
     * @param ItemService $service
     */
    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    /**
     * 以id取得上層項目資料
     *
     * @return array
     */
    public function getItem()
    {
        return $this->service->item->getItem(request()->input('id'))->first()->toArray();
    }

    /**
     * 以name搜尋所有上層項目清單
     *
     * @return array
     */
    public function searchItemList()
    {
        return $this->service->item->getItemList()
            ->where('name', 'like', '%' . iconv('utf8', 'big5', request()->input('name')) . '%')
            ->orderBy('created_at')->get()->toArray();
    }

    /**
     * 取得所有上層項目清單
     *
     * @return array
     */
    public function getItemList()
    {
        return $this->service->item->getItemList()->orderBy('created_at')->get()->toArray();
    }

    /**
     * 取得上層項目之資料、可選缺點清單以及已選缺點清單
     *
     * @return array
     */
    public function getDefectGroup()
    {
        return [
            'item' => $this->service->item->getItem(request()->input('id'))->first(),
            'defectGroup' => $this->service->item->getNonSelectDefect(request()->input('id'))->get(),
            'selected' => $this->service->item->getSelectedDefect(request()->input('id'))->get(),
        ];
    }

    /**
     * 新增上層項目資料
     *
     * @return array
     */
    public function insertItem()
    {
        return $this->service->insertItem(request()->input());
    }

    /**
     * 更新上層項目資料
     *
     * @return array
     */
    public function updateItem()
    {
        return $this->service->updateItem(request()->input());
    }

    /**
     * 刪除上層項目資料
     *
     * @return array
     */
    public function deleteItem()
    {
        return $this->service->item->deleteItem(request()->input());
    }
}
