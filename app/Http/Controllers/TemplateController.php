<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\TemplateRepository;
use App\Repositories\ItemRepository;

//
class TemplateController extends BaseController
{
    //
    public $template;

    //
    public $item;

    //
    public function __construct(TemplateRepository $template, ItemRepository $item)
    {
        $this->template = $template;
        $this->item = $item;
    }

    //
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

    //
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

    //
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

    //
    public function deleteTemplate()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->template->deleteTemplate($id);
        return $result;
    }
}
