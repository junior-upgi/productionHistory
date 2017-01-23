<?php
/**
 * ItemRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/23
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use App\Models\productionHistory\Defect;
use App\Models\productionHistory\DefectItem;
use App\Models\productionHistory\DefectGroup;
use App\Service\Common;

/**
 * Class ItemRepository
 * @package App\Repositories
 */
class ItemRepository extends BaseRepository
{
    /**
     * @var Defect
     */
    public $defect;
    /**
     * @var DefectItem
     */
    public $item;
    /**
     * @var DefectGroup
     */
    public $defectGroup;
    /**
     * @var Common
     */
    public $common;

    /**
     * ItemRepository constructor.
     * @param Defect $defect
     * @param DefectItem $item
     * @param DefectGroup $defectGroup
     * @param Common $common
     */
    public function __construct(
        Defect $defect,
        DefectItem $item,
        DefectGroup $defectGroup,
        Common $common
    ) {
        $this->defect = $defect;
        $this->item = $item;
        $this->defectGroup = $defectGroup;
        $this->common = $common;
    }

    /**
     * 取得上層項目清單
     *
     * @return DefectItem
     */
    public function getItemList()
    {
        return $this->item;
    }

    /**
     * 取得上層項目缺點清單
     *
     * @param $id
     * @return mixed
     */
    public function getDefectGroup($id)
    {
        return $this->defectGroup->where('itemID', $id);
    }

    /**
     * 取得可選缺點清單
     *
     * @param $id
     * @return mixed
     */
    public function getNonSelectDefect($id)
    {
        return $this->defect
            ->whereNotExists(function ($query) use ($id) {
                $query->select(\DB::raw(1))
                    ->from('defectGroup')
                    ->whereRaw('defect.id = defectGroup.defectID COLLATE database_default')
                    ->whereRaw("defectGroup.itemID = '$id'");
            })
            ->orderBy('name')
            ->select('defect.*');
    }

    /**
     * 取得已選缺點清單
     *
     * @param $id
     * @return mixed
     */
    public function getSelectedDefect($id)
    {
        return $this->defectGroup
            ->where('itemID', $id)
            ->join('defect', function ($join) {
                $join->whereRaw('defectGroup.defectID = defect.id COLLATE database_default');
            })
            ->orderBy('sequence')
            ->select('defect.*');
    }

    /**
     * 取得上層項目資料
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->item->where('id', $id);
    }

    /**
     * 新增上層項目資料
     *
     * @param $main
     * @param $detail
     * @return array
     */
    public function insertItem($main, $detail)
    {
        try {
            $this->item->getConnection()->beginTransaction();
            $this->item->insert($main);
            $this->defectGroup->insert($detail);
            $this->item->getConnection()->commit();
            return ['success' => true, 'msg' => 'success'];
        } catch (\Exception $e) {
            $this->item->getConnection()->rollback();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 更新上層項目資料
     *
     * @param $main
     * @param $detail
     * @return array
     */
    public function updateItem($main, $detail)
    {
        try {
            $this->item->getConnection()->beginTransaction();
            $this->item->where('id', $main['id'])->update($main);
            $this->defectGroup->where('itemID', $main['id'])->forceDelete();
            $this->defectGroup->where('itemID', $main['id'])->insert($detail);
            $this->item->getConnection()->commit();
            return ['success' => true, 'msg' => 'success'];
        } catch (\Exception $e) {
            $this->item->getConnection()->rollback();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 刪除上層項目資料
     *
     * @param $id
     * @return mixed
     */
    public function deleteItem($id)
    {
        $result = $this->delete($this->item, $id);
        $this->forceDelete($this->defectGroup, $id);
        return $result;
    }
}
