<?php
/**
 * CheckRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use App\Models\productionHistory\DefectCheck;
use App\Service\DataFormatService;

/**
 * Class CheckRepository
 * @package App\Repositories
 */
class CheckRepository
{
    use DataFormatService;

    /**
     * @var DefectCheck
     */
    public $check;

    /**
     * CheckRepository constructor.
     * @param DefectCheck $check
     */
    public function __construct(DefectCheck $check)
    {
        $this->check = $check;
    }

    /**
     * 取得檢查表資料
     *
     * @return DefectCheck
     */
    public function getCheckList()
    {
        return $this->check
            ->join('UPGWeb.dbo.glass', 'defectCheck.prd_no', 'glass.prd_no')
            ->orderBy('schedate', 'desc')
            ->select('defectCheck.*', 'glass.snm');
    }

    /**
     * 以prd_no 搜尋檢查表
     *
     * @param $prd_no
     * @return mixed
     */
    public function searchCheckByPrdNo($prd_no)
    {
        return $this->check->where('prd_no', $prd_no);
    }

    /**
     * 以schedate 搜尋檢查表
     * 如果start 或end 存在則進行搜尋
     *
     * @param $start
     * @param $end
     * @return mixed
     */
    public function searchCheckBySchedate($start, $end)
    {
        return $this->check->where(function ($q) use ($start, $end) {
            if (isset($start)) {
                $q->where('schedate', '>=', $start);
            }
            if (isset($end)) {
                $q->where('schedate', '<=', $end);
            }
        });
    }

    /**
     * 以ID取得檢查表資料
     *
     * @param $id
     * @return mixed
     */
    public function getCheck($id)
    {
        return $this->check->where('id', $id)
            ->join('UPGWeb.dbo.glass', 'UPGWeb.dbo.glass.prd_no', 'defectCheck.prd_no')
            ->select('defectCheck.*', 'UPGWeb.dbo.glass.snm', 'UPGWeb.dbo.glass.spc');
    }

    /**
     * 新增檢查表資料
     *
     * @param $params
     * @return array
     */
    public function insertCheck($params)
    {
        try {
            $this->insertCheckPrework($params);
            return ['success' => true, 'msg' => '新增檢查表成功', 'id' => $params['id']];
        } catch(\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 新增檢查表前置工作
     * 判斷是否曾經刪過檢查表，進行新增或修改
     *
     * @param $params
     */
    private function insertCheckPrework($params)
    {
        $table = $this->check->withTrashed()->where('id', $params['id']);
        if ($table->exists()) {
            $params['deleted_at'] = null;
            $params['deleted_by'] = null;
            $table->update($this->setTimestamp('updated', $params));
        } else {
            $this->check->insert($this->setTimestamp('created', $params));
        }
    }

    /**
     * 更新檢查表資料
     *
     * @param $id
     * @param $params
     * @return array
     */
    public function updateCheck($id, $params)
    {
        try {
            $this->check->where('id', $id)->update($this->setTimestamp('updated', $params));
            return ['success' => true, 'msg' => '更新檢查表成功'];
        } catch(\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 刪除檢查表
     *
     * @param $id
     * @return array
     */
    public function deleteCheck($id)
    {
        try {
            $this->check->where('id', $id)->update($this->setTimestamp('deleted', []));
            return ['success' => true, 'msg' => '刪除檢查表成功'];
        } catch (\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }
}