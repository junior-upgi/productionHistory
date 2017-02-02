<?php
/**
 * 檢查表controller
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/1/24
 * @since 1.0.0 spark: 於此版本開始編寫註解，已優化程式碼
 */
namespace App\Http\Controllers;

use App\Repositories\TemplateRepository;
use App\Service\CheckService;
use App\Service\ProductionDefectService;

/**
 * Class CheckController
 * @package App\Http\Controllers
 */
class CheckController extends Controller
{
    /**
     * @var CheckService
     */
    public $service;
    /**
     * @var ProductionDefectService
     */
    public $defectService;
    /**
     * @var TemplateRepository
     */
    public $template;

    /**
     * CheckController constructor.
     *
     * @param CheckService $service
     * @param ProductionDefectService $defectService
     * @param TemplateRepository $template
     * @internal param CheckRepository $check
     */
    public function __construct(
        CheckService $service,
        ProductionDefectService $defectService,
        TemplateRepository $template
    ) {
        $this->service = $service;
        $this->defectService = $defectService;
        $this->template = $template;
    }

    /**
     * 取得檢查表清單
     *
     * @return mixed
     */
    public function getCheckList()
    {
        return $this->service->getCheckList();
    }

    /**
     * 搜尋檢查表清單
     *
     * @return mixed
     */
    public function searchCheck()
    {
        return $this->service->searchCheck(request()->input());
    }

    /**
     *  取得排程清單
     *
     * @return mixed
     */
    public function getScheduleList()
    {
        return $this->service->getScheduleList(request());
    }

    /**
     * 新增檢查表資料
     *
     * @return mixed
     */
    public function insertCheck()
    {
        return $this->service->insertCheck(request()->input());
    }

    /**
     * 更新檢查表資料
     *
     * @return mixed
     */
    public function updateCheck()
    {
        return $this->service->updateCheck(request()->input());
    }

    /**
     * 刪除檢查表
     *
     * @return array
     */
    public function deleteCheck()
    {
        return $this->service->deleteCheck(request()->input('id'));
    }

    /**
     * 取得檢查表缺點清單
     *
     * @return mixed
     */
    public function getProductionDefectList()
    {
        return $this->defectService->getProductionDefectList(request()->input('id'));
    }

    /**
     * 新增生產缺點
     *
     * @return mixed
     */
    public function insertProductionDefect()
    {
        return $this->defectService->insertProductionDefect(request()->input());
    }

    /**
     * 更新生產缺點
     *
     * @return mixed
     */
    public function updateProductionDefect()
    {
        return $this->defectService->updateProductionDefect(request()->input());
    }

    /**
     * 刪除生產缺點
     *
     * @return mixed
     */
    public function deleteProductionDefect()
    {
        return $this->defectService->deleteProductionDefect(request()->input());
    }

    /**
     * @return string
     */
    public function getScheduleCustomer()
    {
        return $this->service->getScheduleCustomer(request());
    }
}
