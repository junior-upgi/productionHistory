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
    public function setTimestamp($type, $params)
    {
        if (isset($params[0])) {
            return $this->setTimestampBy2DArray($type, $params);
        }
        return $this->setTimestampBy1DArray($type, $params);
    }

    /**
     *
     *
     * @param $type
     * @param $params
     * @return mixed
     */
    private function setTimestampBy1DArray($type, $params)
    {
        $params[$type . '_at'] = Carbon::now();
        $params[$type . '_by'] = $this->getErpID();
        return $params;
    }

    /**
     *
     *
     * @param $type
     * @param $params
     * @return array
     */
    public function setTimestampBy2DArray($type, $params)
    {
        $new = [];
        for ($i = 0; $i < count($params); $i++) {
            $set = $params[$i];
            $set[$type . '_at'] = Carbon::now();
            $set[$type . '_by'] = $this->getErpID();
            array_push($new, $set);
        }
        return $new;
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

    /**
     * 轉換為big5
     *
     * @param $params
     * @return mixed
     */
    public function toBig5($params)
    {
        list($key, $value) = array_divide($params);
        for ($i = 0; $i < count($key); $i++) {
            $array[$key[$i]] = mb_convert_encoding($value[$i], "big5", "utf-8");
        }
        return $array;
    }
}