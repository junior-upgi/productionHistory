<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\TemplateRepository;
use App\Repositories\ItemRepository;

/**
 * Class TemplateController
 * @package App\Http\Controllers
 */
class TemplateController extends BaseController
{
    /**
     * @var TemplateRepository
     */
    public $template;

    /**
     * @var ItemRepository
     */
    public $item;

    /**
     * TemplateController constructor.
     * @param TemplateRepository $template
     * @param ItemRepository $item
     */
    public function __construct(TemplateRepository $template, ItemRepository $item)
    {
        $this->template = $template;
        $this->item = $item;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        $input = request()->input();
        if (isset($input['id']) > 0) {
            $data = $this->template->getTemplate($input['id'])->first()->toArray();
            return $data;
        } else if (isset($input['name'])) {
            $name = iconv('utf8', 'big5', $input['name']);
            $list = $this->template->getTemplateList()
                ->where('name', 'like', '%' . $name . '%')
                ->orderBy('name')->get()->toArray();
            return $list;
        } else  {
            $list = $this->template->getTemplateList()
                ->orderBy('name')->get()->toArray();
            return $list;
        }
    }

    /**
     * @return array
     */
    public function getTemplateItem()
    {
        $input = request()->input();
        if (isset($input['id'])) {
            $id = $input['id'];
            $template = $this->template->getTemplate($id)->first()->toArray();
            $itemList = $this->template->getNonSelectItem($id)->get()->toArray();
            $selectList = $this->template->getSelectedItem($id)->get()->toArray();
        } else {
            $template = [];
            $itemList = $this->item->getItemList()->orderBy('name')->get()->toArray();
            $selectList = [];
        }
        return [
            'template' => $template,
            'itemList' => $itemList,
            'selectList' => $selectList,
        ];
    }

    /**
     * @return array
     */
    public function saveTemplate()
    {
        $input = request()->input();
        $main = $input['mainData'];
        $detail = $input['detailData'];
        if ($main['type'] == 'add') {
            $result = $this->template->insertTemplate($main, $detail);
        } else if ($main['type'] == 'edit') {
            $result = $this->template->updateTemplate($main, $detail);
        } else {
            return ['success' => false, 'msg' => '參數錯誤!'];
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function deleteTemplate()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->template->deleteTemplate($id);
        return $result;
    }
}
