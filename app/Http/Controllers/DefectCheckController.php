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
        if (count($list) == 0) {
            return $this->setStatusCode(400)->makeResponse('查詢不到[' . $search . ']生產資料');
        }

        return $this->setStatusCode(200)->makeResponse('查詢成功', $list);
    }

    public function getItem()
    {
        $input = request()->input();
        if (isset($input['id']) > 0) {
            $data = $this->defect->getItem($input['id'])->first()->toArray();
            return $data;
        } else if (isset($input['name'])) {
            $name = iconv('utf8', 'big5', $input['name']);
            $list = $this->defect->getItemList()->where('name', 'like', '%' . $name . '%')->get()->toArray();
            return $list;
        } else  {
            $list = $this->defect->getItemList()->get()->toArray();
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
}
