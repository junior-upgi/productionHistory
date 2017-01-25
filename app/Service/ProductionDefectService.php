<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/25
 * Time: 上午8:43
 */

namespace App\Service;

use App\Repositories\ProductionDefectRepository;

/**
 * Class ProductionDefectService
 * @package App\Service
 */
class ProductionDefectService
{
    /**
     * @var ProductionDefectRepository
     */
    public $defect;

    /**
     * ProductionDefectService constructor.
     * @param ProductionDefectRepository $defect
     */
    public function __construct(ProductionDefectRepository $defect)
    {
        $this->defect = $defect;
    }

    /**
     * 取得檢查表生產缺點清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getProductionDefectList($checkID)
    {
        return $this->defect->getProductionDefectList($checkID);
    }

    /**
     * 新增生產缺點
     *
     * @param $input
     * @return array
     */
    public function insertProductionDefect($input)
    {
        $params = array_except($input, ['_token']);
        return $this->defect->insertProductionDefect($params);
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

    public function deleteProductionDefect($id)
    {
        return $this->defect->deleteProductionDefect($id);
    }
}