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

    public function getTemplateItem($id)
    {
        $list = $this->templateItem->where('templateID', $id);
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

    public function getItem($id)
    {
        $data = $this->item->where('id', $id);
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
            $template->insert($main);

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                ];
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
            $template->update($main);

            $templateItem->where('templateID', $id)->delete();

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                ];
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

    public function deleteData($table, $id)
    {
        $table = $this->getTable($table);
        $result = $this->delete($table ,$id);
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
