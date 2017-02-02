<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/24
 * Time: 下午4:03
 */
namespace App\Service;

use Carbon\Carbon;

/**
 * Class DataFormatService
 * @package App\Service
 */
trait DataFormatService
{
    use UserService;
    /**
     * 格式化排程時間
     *
     * @param $date
     * @return mixed
     */
    public function formatSchedate($date)
    {
        $set['op'] = 'like';
        $set['date'] = '%' . $date . '%';
        if ($set['date'] != '%%') {
            $set['op'] = '=';
            $set['date'] = date('Y-m-d', strtotime($date));
        }
        return $set;
    }

    /**
     * 設定時間戳記
     *
     * @param $params
     * @param $type
     * @return mixed
     */
    public function setTimestamp($type, $params = [])
    {
        $params[$type . '_at'] = Carbon::now();
        $params[$type . '_by'] = $this->getErpID();
        return $params;
    }

    /**
     * 產生GUID
     *
     * @return string 回傳GUID
     */
    public function getNewGUID()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = ""
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);

        return $uuid;
    }
}