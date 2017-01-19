<?php
namespace App\Repositories;

use App\Models\productionHistory\Defect;
use App\Models\productionHistory\DefectItem;
use App\Models\productionHistory\DefectGroup;
use App\Service\Common;

//
class ItemRepository extends BaseRepository
{
    //
    public $defect;
    public $item;
    public $defectGroup;
    public $common;

    //
    public function __construct(
        Defect $defect,
        DefectItem $item,
        DefectGroup $defectGroup,
        Common $common
    ) {
        //parent::__construct();
        $this->defect = $defect;
        $this->item = $item;
        $this->defectGroup = $defectGroup;
        $this->common = $common;
    }

    //
    public function getItemList()
    {
        return $this->item;
    }

    //
    public function getDefectGroup($id)
    {
        return $this->defectGroup->where('itemID', $id);
    }

    //
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

    //
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

    //
    public function getItem($id)
    {
        return $this->item->where('id', $id);
    }

    //
    public function saveData($table, $input)
    {
        return $this->save($table, $input);
    }

    //
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

    //
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

    //
    public function deleteItem($id)
    {
        $result = $this->delete($this->item, $id);
        $defectGroupResult = $this->forceDelete($this->defectGroup, $id);
        return $result;
    }

    //
    private function setItemParams($array, $type)
    {
        $params = [];
        for ($i = 0; $i < count($array); $i++) {
            $param = [
                'itemID' => $array[$i]['id'],
                'defectID' => $array[$i]['id'],
                'sequence' => $i + 1,
                $type . '_at' => \Carbon\Carbon::now(),
                $type . '_by' => \Auth::user()->erpID,
            ];
            array_push($params, $param);
        }
        return $params;
    }
}
