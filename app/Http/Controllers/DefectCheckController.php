<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\DefectRepository;

class DefectCheckController extends BaseController
{
    public $defect;

    public function __construct(DefectRepository $defect)
    {
        $this->defect = $defect;
    }

    public function getTemplate()
    {
        $input = request()->input();
        if (isset($input['id']) > 0) {
            $data = $this->defect->getTemplate($input['id'])->first()->toArray();
            return $data;
        } else if (isset($input['name'])) {
            $name = iconv('utf8', 'big5', $input['name']);
            $list = $this->defect->getTemplateList()
                ->where('name', 'like', '%' . $name . '%')
                ->orderBy('name')->get()->toArray();
            return $list;
        } else  {
            $list = $this->defect->getTemplateList()
                ->orderBy('name')->get()->toArray();
            return $list;
        }
    }

    public function getTemplateItem()
    {
        $input = request()->input();
        if (isset($input['id'])) {
            $id = $input['id'];
            $template = $this->defect->getTemplate($id)->first()->toArray();
            $itemList = $this->defect->getNonSelectItem($id)->get()->toArray();
            $selectList = $this->defect->getSelectedItem($id)->get()->toArray();
        } else {
            $template = [];
            $itemList = $this->defect->getItemList()->orderBy('name')->get()->toArray();
            $selectList = [];
        }
        return [
            'template' => $template,
            'itemList' => $itemList,
            'selectList' => $selectList,
        ];
    }

    public function getItem()
    {
        $input = request()->input();
        if (isset($input['id']) > 0) {
            $data = $this->defect->getItem($input['id'])->first()->toArray();
            return $data;
        } else if (isset($input['name'])) {
            $name = iconv('utf8', 'big5', $input['name']);
            $list = $this->defect->getItemList()->where('name', 'like', '%' . $name . '%')
                ->orderBy('name')->get()->toArray();
            return $list;
        } else  {
            $list = $this->defect->getItemList()
                ->orderBy('name')->get()->toArray();
            return $list;
        }
    }

    public function saveItem()
    {
        $input = request()->input();
        $result = $this->defect->saveData('item', $input);
        return $result;
    }

    public function deleteItem()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->defect->deleteData('item', $id);
        return $result;
    }

    public function saveTemplate()
    {
        $input = request()->input();
        $main = $input['mainData'];
        $detail = $input['detailData'];
        if ($main['type'] == 'add') {
            $result = $this->defect->insertTemplate($main, $detail);
        } else if ($main['type'] == 'edit') {
            $result = $this->defect->updateTemplate($main, $detail);
        } else {
            return ['success' => false, 'msg' => '參數錯誤!'];
        }
        return $result;
    }

    public function deleteTemplate()
    {
        $input = request()->input();
        $id = $input['id'];
        $result = $this->defect->deleteData('template', $id);
        return $result;
    }
}