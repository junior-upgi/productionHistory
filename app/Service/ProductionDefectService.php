<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/25
 * Time: 上午8:43
 */

namespace App\Service;

use App\Repositories\CheckRepository;
use App\Repositories\ProductionDefectRepository;
use App\Repositories\TemplateRepository;

/**
 * Class ProductionDefectService
 * @package App\Service
 */
class ProductionDefectService
{
    use DataFormatService;
    /**
     * @var ProductionDefectRepository
     */
    public $defect;
    /**
     * @var CheckRepository
     */
    public $check;
    /**
     * @var TemplateRepository
     */
    public $template;

    /**
     * ProductionDefectService constructor.
     * @param CheckRepository $check
     * @param ProductionDefectRepository $defect
     * @param TemplateRepository $template
     */
    public function __construct(CheckRepository $check, ProductionDefectRepository $defect, TemplateRepository $template)
    {
        $this->check = $check;
        $this->defect = $defect;
        $this->template = $template;
    }

    /**
     * 取得套板項目清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getCheckTemplateItem($checkID)
    {
        return $this->template->getTemplateItem($this->check->getCheck($checkID)->first()->templateID)->get();
    }

    /**
     * 取得套板缺點清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getCheckTemplateDefect($checkID)
    {
        return $this->template->getTemplateDefect($this->check->getCheck($checkID)->first()->templateID)->get();
    }

    /**
     * 取得檢查表生產資訊清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getProductionDataList($checkID)
    {
        return $this->defect->getProductionDataList($checkID)->get();
    }

    /**
     * 取得檢查表生產缺點清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getProductionDefectList($checkID)
    {
        return $this->defect->getProductionDefectList($checkID)->get();
    }

    /**
     * 新增生產缺點
     *
     * @param $input
     * @return array
     */
    public function insertProductionDefect($input)
    {
        $input = array_except($input, ['_token']);
        return $this->insertProductionDefectPrework($input);
    }

    private function insertProductionDefectPrework($input)
    {
        /** set productionData params */
        $dataParams = $this->setProductionDataParams($input);

        /** set productionDefect params */
        $defectParams = $this->setProductionDefectParams($input, $dataParams['id']);

        return $this->defect->insertProductionDefect($dataParams, $defectParams);
    }

    /**
     *
     *
     * @param $input
     * @return mixed
     */
    private function setProductionDataParams($input)
    {
        if (strlen($input['id']) != 36) {
            $input['id'] = $this->getNewGUID();
        }
        $params['id'] = $input['id'];
        $params['checkID'] = $input['checkID'];
        $params['prodDate'] = $input['prodDate'];
        $params['classType'] = $input['classType'];
        $params['spotCheck'] = $input['spotCheck'];
        $params['classRemark'] = $input['classRemark'];
        $params['minute'] = $input['minute'];
        $params['speed'] = $input['speed'];
        $params['checkRate'] = $input['checkRate'];
        $params['actualQuantity'] = $input['actualQuantity'];
        $params['actualMinWeight'] = $input['actualMinWeight'];
        $params['actualMaxWeight'] = $input['actualMaxWeight'];
        $params['stressLevel'] = $input['stressLevel'];
        $params['thermalShock'] = $input['thermalShock'];
        return $params;
    }

    /**
     *
     *
     * :WARNING: 針對套板的修改可能會造成寫入資料的問題
     *
     * @param $input
     * @param $dataID
     * @return array
     */
    private function setProductionDefectParams($input, $dataID)
    {
        $params = [];
        $defect = $this->template->getTemplateDefect($this->check->getCheck($input['checkID'])->first()->templateID)->get();
        for ($i = 0; $i < count($defect); $i++) {
            $item['productionDataID'] = $dataID;
            $item['checkID'] = $input['checkID'];
            $item['itemID'] = $defect[$i]['itemID'];
            $item['defectID'] = $defect[$i]['defectID'];
            if (isset($input[$defect[$i]['itemID'] . $defect[$i]['defectID']])) {
                $item['value'] = $input[$defect[$i]['itemID'] . $defect[$i]['defectID']];
            }
            $item['sequence'] = $i + 1;
            array_push($params, $item);
        }
        return $params;
    }

    /**
     * 更新生產缺點
     *
     * @param $input
     * @return array
     */
    public function updateProductionDefect($input)
    {
        $id = $input['id'];
        $params =  array_except($input, ['_token', 'id']);
        return $this->defect->updateProductionDefect($id, $params);
    }

    /**
     * 刪除生產缺點
     *
     * @param $id
     * @return array
     */
    public function deleteProductionDefect($id)
    {
        return $this->defect->deleteProductionDefect($id);
    }
}