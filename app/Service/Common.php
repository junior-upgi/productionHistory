<?php
/**
 * 共用元件
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/11/09
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Service;

use App\Models\upgiSystem\File;
use Auth;
use Carbon\Carbon;

/**
 * Class ProductRepository
 *
 * @package App\Repositories
 */
class Common
{
    use UserService;

    /**
     * 寫入參數處理 
     * 
     * @param array $input 傳入Input
     * @param array $addIgnore 額外的忽略參數
     * @param bool $saveID 是否保留id
     * @return array 回傳結果
     */
    public function params($input, $addIgnore, $saveID = false)
    {
        $input = array_except($input, $this->ignoreParams($addIgnore, $saveID));
        $params = [];
        list($key, $value) = array_divide($input);
        for ($i = 0; $i < count($input); $i++) {
            $params[$key[$i]] = mb_convert_encoding($value[$i], "big5", "utf-8");
        }
        return $params;
    }

    /**
     * 設定忽略參數
     * 
     * @param array $addIgnore 額外的忽略參數
     * @param bool $saveID 是否保留id
     * @return array 回傳結果
     */
    private function ignoreParams($addIgnore, $saveID)
    {
        if ($saveID) {
            return array_merge(['_token', 'type'], $addIgnore);
        }
        return array_merge(['_token', 'type', 'id'], $addIgnore);
    }
    
    /**
     * 解析並回傳查詢後之Module
     * 
     * @param mixed $table 傳入Module
     * @param array $where 查詢條件 
     * @return mixed 回傳結果
     */
    public function where($table, $where = null)
    {
        $obj = $table->where(function ($q) use ($where) {
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
        });
        return $obj;
    }
    
    /**
     * 加入時間戳記
     * 
     * @param mixed $table 傳入Module
     * @param array $params 傳入參數
     * @param string $type 動作狀態
     * @return array 回傳結果
     */
    public function timestamps($table, $params, $type)
    {
        try {
            if ($table->timestamps != false) {
                $params[$type.'_at'] = Carbon::now();
                $params[$type.'_by'] = $this->getErpID();
            }
            return $params;
        } catch(\Exception $e) {
            return $params;
        }
        
    }

    /**
     * 加入UserID
     * 
     * @param array $params 傳入參數
     * @param string $type 動作狀態
     * @return array 回傳結果
     */
    public function setUserID($params, $type)
    {
        $user_id = Auth::user()->erpid;
        $params[$type.'_by'] = $user_id;
        return $params;
    }

    /**
     * 新增Module
     * 
     * @param mixed $table 傳入Module
     * @param array $params 傳入新增資料
     * @return array 回傳結果
     */
    public function insert($table, $params)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->insert($this->timestamps($table, $params, 'created'));
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
     * @param mixed$table 傳入Module
     * @param array $params 傳入更新資料
     * @return array 回傳結果
     */
    public function update($table, $params)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->update($this->timestamps($table, $params, 'updated'));
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
     * @param mixed $table 傳入Module
     * @return array 回傳結果
     */
    public function delete($table)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->delete();
            $table->update(['deleted_by' => $this->getErpID()]);
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
     * @return string
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
        }
        return null;
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