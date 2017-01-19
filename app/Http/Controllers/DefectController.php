<?php
/**
 * 缺點管理controller
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/1/17
 * @since 1.0.0 spark: 於此版本開始編寫註解，已優化程式碼
 */
namespace App\Http\Controllers;

use App\Repositories\DefectRepository;

/**
 * Class DefectController
 *
 * @package App\Http\Controllers
 */
class DefectController extends BaseController
{
    /** DefectRepository $defect */
    public $defect;

    /** 
     * construct
     *
     * @param DefectRepository $defect
     */
    public function __construct(DefectRepository $defect)
    {
        $this->defect = $defect;
    }
    
    /**
     * 以id取得缺點資料
     *
     * @return Array
     */
    public function getDefect()
    {
        return $this->defect->getDefect(request()->input('id'))->first()->toArray();
    }

    /**
     * 以name搜尋缺點清單
     *
     * @return Array
     */
    public function searchDefect()
    {
        return $this->defect->searchDefect(request()->input('name'))->orderBy('created_at')->get()->toArray();
    }

    /**
     * 取得全部缺點清單
     *
     * @return Array
     */
    public function getDefectList()
    {
        return $this->defect->getDefectList()->orderBy('name')->get()->toArray();
    }

    /**
     * 新增缺點資料
     *
     * @return Array
     */
    public function insertDefect()
    {
        return $this->defect->saveData(request()->input());
    }

    /**
     * 更新缺點資料
     *
     * @return Array
     */
    public function updateDefect()
    {
        return $this->defect->saveData(request()->input());
    }

    /**
     * 刪除缺點資料
     *
     * @return Array
     */
    public function deleteDefect()
    {
        return $this->defect->deleteDefect(request()->input());
    }
}
