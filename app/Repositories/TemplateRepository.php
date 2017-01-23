<?php
/**
 * TemplateRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use App\Models\productionHistory\DefectItem;
use App\Models\productionHistory\DefectTemplate;
use App\Models\productionHistory\TemplateItem;
use App\Service\Common;

/**
 * Template資料處理類別庫
 *
 * Class TemplateRepository
 * @package App\Repositories
 */
class TemplateRepository extends BaseRepository
{
    /**
     * @var DefectItem
     */
    public $item;
    /**
     * @var DefectTemplate
     */
    public $template;
    /**
     * @var TemplateItem
     */
    public $templateItem;
    /**
     * @var Common
     */
    public $common;

    /**
     * TemplateRepository constructor.
     *
     * @param DefectItem $item
     * @param DefectTemplate $template
     * @param TemplateItem $templateItem
     * @param Common $common
     */
    public function __construct(
        DefectItem $item,
        DefectTemplate $template,
        TemplateItem $templateItem,
        Common $common
    ) {
        $this->common = $common;
        $this->item = $item;
        $this->template = $template;
        $this->templateItem = $templateItem;
    }

    /**
     * 取得套板可選項目清單
     *
     * @param $id
     * @return mixed
     */
    public function getNonSelectItem($id)
    {
        return $this->item
            ->whereNotExists(function ($query) use ($id) {
                $query->select(\DB::raw(1))
                    ->from('templateItem')
                    ->whereRaw('defectItem.id = templateItem.itemID COLLATE database_default')
                    ->whereRaw("templateItem.templateID = '$id'");
            })
            ->orderBy('name')
            ->select('defectItem.*');
    }

    /**
     * 取得已選擇的上層項目
     *
     * @param $id
     * @return mixed
     */
    public function getSelectedItem($id)
    {
        return $this->templateItem
            ->where('templateID', $id)
            ->join('defectItem', function ($join) {
                $join->whereRaw('templateItem.itemID = defectItem.id COLLATE database_default');
            })
            ->orderBy('sequence')
            ->select('defectItem.*');
    }

    /**
     * 新增套板
     *
     * @param $main
     * @param $detail
     * @return array
     */
    public function insertTemplate($main, $detail)
    {
        try {
            $this->template->getConnection()->beginTransaction();
            $this->template->insert($main);
            $this->templateItem->insert($detail);
            $this->template->getConnection()->commit();
            return ['success' => true, 'msg' => 'success'];
        } catch (\Exception $e) {
            $this->template->getConnection()->rollback();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 更新套板
     *
     * @param $main
     * @param $detail
     * @return array
     */
    public function updateTemplate($main, $detail)
    {
        try {
            $this->template->getConnection()->beginTransaction();
            $this->template->where('id', $main['id'])->update($main);
            $this->templateItem->where('templateID', $main['id'])->forceDelete();
            $this->templateItem->where('templateID', $main['id'])->insert($detail);
            $this->template->getConnection()->commit();
            return ['success' => true, 'msg' => 'success'];
        } catch (\Exception $e) {
            $this->template->getConnection()->rollback();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 刪除套樣
     *
     * @param $id
     * @return mixed
     */
    public function deleteTemplate($id)
    {
        $result = $this->delete($this->template, $id);
        $this->forceDelete($this->templateItem, $id);
        return $result;
    }

    /**
     * 取得套板清單
     *
     * @return DefectTemplate
     */
    public function getTemplateList()
    {
        return $this->template;
    }

    /**
     * 取得套板資料
     *
     * @param $id
     * @return mixed
     */
    public function getTemplate($id)
    {
        return $this->template->where('id', $id);
    }
}
