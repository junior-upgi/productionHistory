<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

//
class TemplateRepository extends BaseRepository
{
    //
    public function __construct() {
        parent::__construct();
    }

    //
    public function getTemplateItem($id)
    {
        $list = $this->templateItem->where('templateID', $id);
        return $list;
    }

    //
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

    //
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

    //
    public function saveData($table, $input)
    {
        $result = $this->save($table, $input);
        return $result;
    }

    //
    public function insertTemplate($main, $detail)
    {
        $id = $this->common->getNewGUID();
        $template = $this->template;
        $templateItem = $this->templateItem;
        $main = array_except($main, ['type']);
        $main['id'] = $id;

        try {
            $template->getConnection()->beginTransaction();
            $main['name'] = iconv('utf8', 'big5', $main['name']);
            $main = $this->common->timestamps($template, $main, 'created');
            $template->insert($main);

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                    'sequence' => $i + 1,
                ];
                $params = $this->common->timestamps($template, $params, 'created');
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
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }
    }

    //
    public function updateTemplate($main, $detail)
    {
        $id = $main['id'];
        $template = $this->template;
        $templateItem = $this->templateItem;
        $main = array_except($main, ['type', 'id']);

        try {
            $template->getConnection()->beginTransaction();
            $main['name'] = iconv('utf8', 'big5', $main['name']);
            $main = $this->common->timestamps($template, $main, 'updated');
            $template->where('id', $id)->update($main);

            $templateItem->where('templateID', $id)->forceDelete();

            for ($i = 0; $i < count($detail); $i++) {
                $params = [
                    'templateID' => $id,
                    'itemID' => $detail[$i]['id'],
                    'sequence' => $i + 1,
                ];
                $params = $this->common->timestamps($templateItem, $params, 'created');
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
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }
    }
    
    //
    public function deleteTemplate($id)
    {
        $template = $this->template;
        $templateResult = $this->delete($template, $id);

        $templateItem = $this->templateItem;
        $templateItemResult = $this->forceDelete($templateItem, $id);

        return $templateResult;
    }

    //
    public function getTemplateList()
    {
        $list = $this->template;
        return $list;
    }

    //
    public function getTemplate($id)
    {
        $data = $this->template->where('id', $id);
        return $data;
    }
}
