<?php
/**
 * 共用元件
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/11/09
 * @since 1.0.0 spark: 於此版本開始編寫註解，完成單一登入功能
 */
namespace App\Service;

use App\Models\upgiSystem\File;
use Auth;

/**
 * Class ProductRepository
 *
 * @package App\Repositories
 */
class Common
{
    /**
     * Common 建構式
     *
     * @return void
     */
    public function __construct() {
        //
    }


    public function params($input, $addIgnore, $saveID = false)
    {
        $ignore = array_merge(['_token', 'type'], $addIgnore);
        ($saveID == false) ? $ignore = array_merge(['_token', 'type', 'id'], $addIgnore) : true;
        
        $input = array_except($input, $ignore);
        $params = array();
        $countInput = count($input);
        list($key, $value) = array_divide($input);
        for ($i = 0; $i < $countInput; $i++) {
            $big5 = iconv('utf8', 'big5', $value[$i]);
            $params[$key[$i]] = $big5;
        }
        return $params;
    }
    
    public function where($table, $where = null)
    {
        $obj = $table->where(function ($q) use ($where) {
            if (isset($where)) {
                foreach ($where as $w) {
                    $key = $w['key'];
                    $op = (!isset($w['op'])) ? '=' : $w['op'];
                    $value = $w['value'];
                    $or = (!isset($w['or'])) ? false : $w['or'];
                    if ($or) {
                        $q->orWhere($key, $op, $value);
                    } else {
                        $q->where($key, $op, $value);
                    }
                }
            }
        });
        return $obj;
    }

    public function timestamps($params, $type)
    {
        $params[$type.'_at'] = \Carbon\Carbon::now();
        if (isset(Auth::user()->erpid)) {
            $user_id = Auth::user()->erpid;
            $params[$type.'_by'] = $user_id;
        }
        return $params;
    }

    /**
     * 新增Module
     * 
     * @param Module $table 傳入Module
     * @param array $params 傳入新增資料
     * @return array 回傳結果
     */
    public function insert($table, $params)
    {
        try {
            if ($table->timestamps != false) {
                $params = $this->timestamps($params, 'created');
            }
            $table->getConnection()->beginTransaction();
            $table->insert($params);
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e->getMessage(),
            );
        }
    }

    /**
     * 更新Module
     * 
     * @param Module $table 傳入Module
     * @param array $params 傳入更新資料
     * @return array 回傳結果
     */
    public function update($table, $params, $timestamps = false)
    {
        try {
            if ($timestamps) {
                $params = $this->timestamps($params, 'updated');
            }
            $table->getConnection()->beginTransaction();
            $table->update($params);
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e->getMessage(),
            );
        }
    }

    /**
     * 刪除module
     * 
     * @param Module $table 傳入Module
     * @return array 回傳結果
     */
    public function delete($table)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->delete();
            if (isset(Auth::user()->erpid)) {
                $user_id = Auth::user()->erpid;
                $table->update(['deleted_by' => $user_id]);
            }
            $table->getConnection()->commit();
            
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e->getMessage(),
            );
        }
    }

    /**
     * 取得檔案base64編碼
     * 
     * @param string $id 檔案id
     * @return string base64編碼
     */
    public function getFile($id)
    {
        $file = new File();
        return $file->getFileCode($id);
    }

    /**
     * 取得檔案資訊
     * 
     * @param string $id 檔案id
     * @return File Module
     */
    public function getFileInfo($id)
    {
        $file = new File();
        return $file->getFile($id);
    }
    
    /**
     * 將檔案進行base64轉碼存入資料庫中，並回傳對應id
     * 
     * @param object $data 檔案物件
     * @return string 檔案id
     */
    public function saveFile($data)
    {
        $name = $data->getClientOriginalName();
        $fe = $data->getClientOriginalExtension();
        $type = $data->getMimeType();
        $file = new file();
        $id = $this->getNewGUID();
        $result = $file->saveFile($data, $type, $name, $fe, $id);
        if ($result) {
            return $id;
        } else {
            return null;
        }
    }

    /**
     * 產生GUID
     *
     * @return string 回傳GUID
     */
    public static function getNewGUID()
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