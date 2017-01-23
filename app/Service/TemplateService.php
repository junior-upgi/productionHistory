<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/23
 * Time: 下午2:05
 */

namespace App\Service;

use App\Repositories\TemplateRepository;
use Carbon\Carbon;

class TemplateService
{
    use UserService;

    /**
     * @var TemplateRepository
     */
    public $template;

    /**
     * TemplateService constructor.
     *
     * @param TemplateRepository $template
     */
    public function __construct(TemplateRepository $template)
    {
        $this->template = $template;
    }

    /**
     *
     *
     * @param $input
     * @return mixed
     */
    public function InsertTemplate($input)
    {
        $mainParams = $this->setInsertMainParams($input['mainData']);
        $detailParams = $this->setInsertDetailParams($input['detailData'], $mainParams['id']);
        return $this->template->insertTemplate($mainParams, $detailParams);
    }

    /**
     *
     *
     * @param $input
     * @return mixed
     */
    public function UpdateTemplate($input)
    {
        $mainParams = $this->setUpdateMainParams($input['mainData']);
        $detailParams = $this->setUpdateDetailParams($input['detailData'], $mainParams['id']);
        return $this->template->updateTemplate($mainParams, $detailParams);
    }

    /**
     *
     *
     * @param $main
     * @return mixed
     */
    private function setInsertMainParams($main)
    {
        $main['id'] = $this->template->common->getNewGUID();
        return $this->setMainParams($main, 'created');
    }

    /**
     *
     *
     * @param $main
     * @return mixed
     */
    private function setUpdateMainParams($main)
    {
        return $this->setMainParams($main, 'updated');
    }

    /**
     * 設定上層項目主表參數
     *
     * @param $main
     * @param $type
     * @return array
     */
    private function setMainParams($main, $type)
    {
        $main = array_except($main, ['type']);
        $main['name'] = iconv('utf8', 'big5', $main['name']);
        $main[$type.'_at'] = Carbon::now();
        $main[$type.'_by'] = $this->getErpID();
        return $main;
    }

    /**
     * 設定新增上層項目已選缺點參數
     *
     * @param $detail
     * @param $id
     * @return mixed
     */
    private function setInsertDetailParams($detail, $id)
    {
        return $this->setDetailParams($detail, $id, 'created');
    }

    /**
     * 設定更新上層項目已選缺點參數
     *
     * @param array $detail
     * @param string $id
     *
     * @return array
     */
    private function setUpdateDetailParams($detail, $id)
    {
        return $this->setDetailParams($detail, $id, 'updated');
    }

    /**
     * 設定上層項目己選缺點參數
     *
     * @param $detail
     * @param $id
     * @param $type
     * @return array
     */
    private function setDetailParams($detail, $id, $type)
    {
        $params = [];
        $now = Carbon::now();
        for ($i = 0; $i < count($detail); $i++) {
            $param['templateID'] = $id;
            $param['itemID'] = $detail[$i]['id'];
            $param['sequence'] = $i;
            $param[$type.'_at'] = $now;
            $param[$type.'_by'] = $this->getErpID();
            array_push($params, $param);
        }
        return $params;
    }
}