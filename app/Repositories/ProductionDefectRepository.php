<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/25
 * Time: 上午8:25
 */

namespace App\Repositories;

use App\Models\productionHistory\ProductionDefect;
use App\Models\productionHistory\ProductionData;
use App\Service\DataFormatService;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductionDefectRepository
 * @package App\Repositories
 */
class ProductionDefectRepository
{
    use DataFormatService;

    /**
     * @var ProductionDefect
     */
    public $defect;
    /**
     * @var ProductionData
     */
    public $data;

    /**
     * ProductionDefectRepository constructor.
     * @param ProductionDefect $defect
     * @param ProductionData $data
     */
    public function __construct(ProductionDefect $defect, ProductionData $data)
    {
        $this->defect = $defect;
        $this->data = $data;
    }

    /**
     * 取得生產資訊清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getProductionDataList($checkID)
    {
        return $this->data->where('checkID', $checkID)->orderBy('prodDate')->orderBy('classType');
    }

    /**
     * 取得生產缺點清單
     *
     * @param $checkID
     * @return mixed
     */
    public function getProductionDefectList($checkID)
    {
        return $this->defect
            ->join('defect', 'defect.id', 'productionDefect.defectID')
            ->where('checkID', $checkID)->orderBy('sequence')
            ->select('productionDefect.*', 'defect.name');
    }

    /**
     * 新增生產缺點
     *
     * @param $dataParams
     * @param $defectParams
     * @return array
     * @internal param $params
     */
    public function insertProductionDefect($dataParams, $defectParams)
    {
        try {
            $this->defect->getConnection()->beginTransaction();
            $this->data->insert($this->setTimestamp('created', $dataParams));
            $this->defect->insert($this->setTimestamp('created', $defectParams));
            $this->defect->getConnection()->commit();
            return ['success' => true, 'msg' => '新增生產缺點成功!'];
        } catch (\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 更新生產缺點
     *
     * @param $dataParams
     * @param $defectParams
     * @return array
     * @internal param $id
     * @internal param $params
     */
    public function updateProductionDefect($dataParams, $defectParams)
    {
        try {
            $this->defect->getConnection()->beginTransaction();
            $this->data->where('id', $dataParams['id'])->update($this->setTimestamp('updated', $dataParams));
            for ($i = 0; $i < count($defectParams); $i++) {
                $this->defect
                    ->where('productionDataID', $defectParams[$i]['productionDataID'])
                    ->where('itemID', $defectParams[$i]['itemID'])
                    ->where('defectID', $defectParams[$i]['defectID'])
                    ->update($this->setTimestamp('updated', $defectParams[$i]));
            }
            $this->defect->getConnection()->commit();
            return ['success' => true, 'msg' => '更新生產缺點成功!'];
        } catch (\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 刪除生產缺點
     *
     * @param $id
     * @return array
     */
    public function deleteProductionDefect($id)
    {
        try {
            $this->data->where('id', $id)->update($this->setTimestamp('deleted'));
            $this->defect->where('productionDataID', $id)->update($this->setTimestamp('deleted'));
            return ['success' => true, 'msg' => '刪除生產缺點成功'];
        } catch (\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 取得缺點平均值
     *
     * @param $checkID
     * @return mixed
     */
    public function getDefectAvg($checkID)
    {
        return $this->data
            ->join('productionDefect', 'productionDefect.productionDataID', 'productionData.id')
            ->join('defect', 'defect.id', 'productionDefect.defectID')
            ->where('productionDefect.checkID', $checkID)
            ->groupBy('productionDefect.itemID', 'productionDefect.defectID', 'productionDefect.sequence',
                'defect.name', 'spotCheck')
            ->having(DB::raw('AVG(value)'), '>' , 0)
            ->orderBy('productionDefect.sequence')
            ->select('productionDefect.itemID', 'productionDefect.defectID', 'productionDefect.sequence',
                'defect.name', DB::raw( 'AVG( productionDefect.value ) as value' ), 'spotCheck')->get();
    }
}