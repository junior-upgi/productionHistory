<?php
/**
 * Model File
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/14
 * @since 1.0.0 spark: 於此版本開始編寫註解
*/
namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *Class File
 *
 * @package App\Models\upgiSystem
*/
class File extends Model
{
    use SoftDeletes;
    
    /** @var string 改寫連線參數 */
    protected $connection = 'upgiSystem';
    /** @var string 指定table名稱 */
    protected $table = "file";
    /** @var bool 開啟軟刪除 */
    protected $softDelete = true;


    /**
    * 取得檔案base64編碼
    * 
    * @param string $id 檔案id
    * @return string string 回傳base64編碼
    * @throw Exception 所有例外
    */
    public function getFileCode($id)
    {
        try{
            $data = $this->where('ID', $id)->first();
            $file = "data:$data->type;base64,$data->code";
            return $file; 
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
    * 取得檔案資訊
    * 
    * @param string $id 檔案id
    * @return string string 回傳Model
    * @throw Exception 所有例外
    */
    public function getFile($id)
    {
        try{
            $data = $this->where('ID', $id)->first();
            return $data; 
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
    * 將檔案以base64進行轉碼並存入，回傳編碼
    * 
    * @param object $file 檔案物件
    * @param string $type 檔案MIME
    * @param string $name 檔名
    * @param string $fe 副檔名
    * @param string $id GUID
    * @return string string 回傳base64編碼
    * @throw Exception 所有例外
    */
    public function saveFile($file, $type, $name, $fe, $id)
    {
        try {
            $conn = $this->getConnection();
            $conn->beginTransaction();
            $data = file_get_contents($file);
            $code = base64_encode($data);
            $params =  array(
                'id' => $id,
                'type' => $type,
                'name' => iconv('utf8', 'big5', $name),
                'fe' => $fe,
                'code' => $code,
            );
            $this->insert($params);
            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollback();
            return false;
        }
        return true;
    }
}