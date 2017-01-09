<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class DefectRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct();
    }

    public function getItemList()
    {
        $list = $this->item;
        return $list;
    }

    public function getDefectList()
    {
        $list = $this->defect;
        return $list;
    }

    public function getTemplateItem($id)
    {
        $list = $this->templateItem->where('templateID', $id);
        return $list;
    }

    public function getDefectGroup($id)
    {
        $list = $this->defectGroup->where('itemID', $id);
        return $list;
    }

    public function getNonSelectItem($id)
    {
        $list = $this->item
            ->whereNotExists(function ($query) use ($id) {
                $query->select(\DB::raw(1))
                    ->from('templateItem')
                    ->whereRaw('defectItem.id = templateItem.itemID COLLATE database_default')
                    ->whereRaw("templateItem.templateID = '$id'");
            })
            ->orderBy('name')
            ->select('defectItem.*');
        return $list;
    }

    public function getSelectedItem($id)
    {
        $list = $this->templateItem
            ->where('templateID', $id)
            ->join('defectItem', function ($join) {
                $join->whereRaw('templateItem.itemID = defectItem.id COLLATE database_default');
            })
            ->orderBy('sequence')
            ->select('defectItem.*');
        return $list;
    }

    public function getNonSelectDefect($id)
    {
        $list = $this->defect
            ->whereNotExists(function ($query) use ($id) {
                $query->select(\DB::raw(1))
                    ->from('defectGroup')
                    ->whereRaw('defect.id = defectGroup.defectID COLLATE database_default')
                    ->whereRaw("defectGroup.itemID = '$id'");
            })
            ->orderBy('name')
            ->select('defect.*');
        return $list;
    }

    public function getSelectedDefect($id)
    {
        $list = $this->defectGroup
            ->where('itemID', $id)
            ->join('defect', function ($join) {
                $join->whereRaw('defectGroup.defectID = defect.id COLLATE database_default');
            })
            ->orderBy('sequence')
            ->select('defect.*');
        return $list;
    }

    public function getItem($id)
    {
        $data = $this->item->where('id', $id);
        return $data;
    }

    public function getDefect($id)
    {
        $data = $this->defect->where('id', $id);
        return $data;
    }

    public function saveData($table, $input)
    {
        $result = $this->save($table, $input);
        return $result;
    }

    public function insertTemplate($main, $detail)
    {
        $id = $this->common->getNewGUID();
        $template = $this->getTable('template');
        $templateItem = $this->getTable('templateItem');
        $main = array_except($main, ['type']);
        $main['id'] = $id;

        try {
            $template->getConnection()->beginTransaction();
            $main['name'] = iconv('utf8', 'big5', $main['name']);
            $main = $this->common->timestamps($main, 'created');
            $template->insert($main);

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                    'sequence' => $i + 1,
                ];
                $params = $this->common->timestamps($params, 'created');
                $templateItem->insert($params);
            }

            $template->getConnection()->commit();
    
            return [
                'success' => true,
                'msg' => 'success',
            ];
        } catch (\Exception $e) {
            $template->getConnection()->rollback();
            return [
                'success' => true,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function updateTemplate($main, $detail)
    {
        $id = $main['id'];
        $template = $this->getTable('template')->where('id', $id);
        $templateItem = $this->getTable('templateItem');
        $main = array_except($main, ['type', 'id']);

        try {
            $template->getConnection()->beginTransaction();
            $main['name'] = iconv('utf8', 'big5', $main['name']);
            $main = $this->common->timestamps($main, 'updated');
            $template->update($main);

            $templateItem->where('templateID', $id)->forceDelete();

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                    'sequence' => $i + 1,
                ];
                $params = $this->common->timestamps($params, 'created');
                $templateItem->insert($params);
            }

            $template->getConnection()->commit();
    
            return [
                'success' => true,
                'msg' => 'success',
            ];
        } catch (\Exception $e) {
            $template->getConnection()->rollback();
            return [
                'success' => true,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function insertItem($main, $detail)
    {
        $id = $this->common->getNewGUID();
        $item = $this->item;
        $defectGroup = $this->defectGroup;
        $main = array_except($main, ['type']);
        $main['id'] = $id;

        try {
            $item->getConnection()->beginTransaction();
            $main['name'] = iconv('utf8', 'big5', $main['name']);
            $main = $this->common->timestamps($main, 'created');
            $item->insert($main);

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'itemID' => $id,
                    'defectID' => $detail[$i]['id'],
                    'sequence' => $i + 1,
                ];
                $params = $this->common->timestamps($params, 'created');
                $defectGroup->insert($params);
            }

            $item->getConnection()->commit();
    
            return [
                'success' => true,
                'msg' => 'success',
            ];
        } catch (\Exception $e) {
            $item->getConnection()->rollback();
            return [
                'success' => true,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function updateItem($main, $detail)
    {
        $id = $main['id'];
        $item = $this->item->where('id', $id);
        $defectGroup = $this->defectGroup;
        $main = array_except($main, ['type', 'id']);

        try {
            $item->getConnection()->beginTransaction();
            $main['name'] = iconv('utf8', 'big5', $main['name']);
            $main = $this->common->timestamps($main, 'updated');
            $item->update($main);

            $defectGroup->where('templateID', $id)->forceDelete();

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                    'sequence' => $i + 1,
                ];
                $params = $this->common->timestamps($params, 'created');
                $defectGroup->insert($params);
            }

            $item->getConnection()->commit();
    
            return [
                'success' => true,
                'msg' => 'success',
            ];
        } catch (\Exception $e) {
            $item->getConnection()->rollback();
            return [
                'success' => true,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function deleteData($tableName, $id)
    {
        $table = $this->getTable($tableName)->where('id', $id);
        $result = $this->delete($table ,$id);
        $result = $this->deleteSubTable($tableName, $id, $result);
        return $result;
    }

    public function deleteSubTable($tableName, $id, $result)
    {
        if ($result['success']) {
            switch ($tableName) {
                case 'template':
                    $table = $this->templateItem;
                    $table->where('templateID', $id)->forceDelete();
                    $result = [
                        'success' => true,
                        'msg' => 'success',
                    ];
                    break;

                case 'item':
                    $table = $this->defectGroup;
                    $table->where('itemID', $id)->forceDelete();
                    $result = [
                        'success' => true,
                        'msg' => 'success',
                    ];
                default:
                    return $result;
            }
        }
        return $result;
    }

    public function getTemplateList()
    {
        $list = $this->template;
        return $list;
    }

    public function getTemplate($id)
    {
        $data = $this->template->where('id', $id);
        return $data;
    }
}
