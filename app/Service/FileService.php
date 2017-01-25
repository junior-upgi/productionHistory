<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/25
 * Time: 下午2:07
 */

namespace App\Service;
use App\Models\upgiSystem\File as FileModule;
use App\Service\Common;

/**
 * Class FileService
 * @package App\Service
 */
trait FileService
{
    /**
     * 取得檔案base64編碼
     *
     * @param string $id 檔案id
     * @return string base64編碼
     */
    public function getFile($id)
    {
        return $this->file->getFileCode($id);
    }

    /**
     * 取得檔案資訊
     *
     * @param string $id 檔案id
     * @return string
     */
    public function getFileInfo($id)
    {
        return $this->file->getFile($id);
    }

    /**
     * 將檔案進行base64轉碼存入資料庫中，並回傳對應id
     *
     * @param object $data 檔案物件
     * @return string 檔案id
     */
    public function saveFile($data)
    {
        try {
            $common = new Common();
            $file = new FileModule();
            $name = $data->getClientOriginalName();
            $fe = $data->getClientOriginalExtension();
            $type = $data->getMimeType();
            $id = $common->getNewGUID();
            return $file->saveFile($data, $type, $name, $fe, $id);
        } catch (\Exception $e) {
            return null;
        }
    }
}