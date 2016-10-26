<?php
/**
 * order相關資料邏輯處理
 *
 * @version 1.0.2
 * @author spark it@upgi.com.tw
 * @date 16/10/20
 * @since 1.0.0 spark: 於此版本開始編寫註解
 * @since 1.0.1 spark: 初步完成表單接收資料並寫入資料庫
 * @since 1.0.2 spark: 初步完成表單介接寫入資料庫，表單連結已完成
 */
namespace App\Repositories;

use App\Models\OrderProduct;
use App\Models\History;
use App\Models\ProductHistory;
use App\Models\Parameter;
use App\Models\Issue;
use App\Models\Output;
use App\Models\Setup;
use App\Models\FormLink;

/**
 * Class OrderRepository
 *
 * @package App\Repositories
 */
class OrderRepository
{
    /** @var OrderProduct 注入OrderProduct */
    private $order;
    /** @var History 注入History */
    private $history;
    /** @var ProductHistory 注入ProductHistory */
    private $productHistory;
    /** @var Parameter 注入Parameter */
    private $parameter;
    /** @var Issue 注入Issue */
    private $issue;
    /** @var Output 注入Output */
    private $output;
    /** @var Setup 注入Setup */
    private $setup;
    /** @var FormLink 注入FormLink */
    private $formLink;

    /**
     * 建構式
     *
     * @param OrderProduct $order
     * @param History $history
     * @param ProductHistory $productHistory
     * @param Parameter $parameter
     * @param Issue $issue
     * @param Output $output
     * @param Setup $setup
     * @return void
     */
    public function __construct(
        OrderProduct $order,
        History $history,
        ProductHistory $productHistory,
        Parameter $parameter,
        Issue $issue,
        Output $output,
        Setup $setup,
        FormLink $formLink
    ) {
        $this->order = $order;
        $this->history = $history;
        $this->productHistory = $productHistory;
        $this->parameter = $parameter;
        $this->issue = $issue;
        $this->output = $output;
        $this->setup = $setup;
        $this->formLink = $formLink;
    }

    /**
     * 取得所有OrderProduct資料
     * 
     * @return OrderProduct 回傳Module
     */
    public function getOrderList()
    {
        $list = $this->order->get();
        return $list;
    }

    /**
     * 依where條件取得Order資料
     * 
     * @param array $where 注入查詢條件
     * @return OrderProduct 回傳Module
     */
    public function getOrderWhere($where)
    {
        $table = $this->order;
        $obj = $this->getWhere($table, $where);
        return $obj;
    }

    /**
     * 依where條件取得ProductHistory資料
     * 
     * @param array $where 注入查詢條件
     * @return ProductHistory 回傳Module
     */
    public function getProductHistoryWhere($where)
    {
        $table = $this->productHistory;
        $obj = $this->getWhere($table, $where);
        return $obj;
    }

    /**
     * 依where條件取得資料
     * 
     * @param Model $table 傳入Model物件
     * @param array $where 傳入查詢條件
     * @return Model 回傳Module
     */
    private function getWhere($table, $where)
    {
        $obj = $table
            ->where(function ($q) use ($where) {
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
     * 寫入表單資料
     * 
     * @param string $table 資料表名稱
     * @param array $params 傳入寫入參數
     * @return array 回傳新增結果
     */
    public function insertForm($table, $params)
    {
        $getTable = $this->getTable($table);
        if (isset($getTable)) {
            $ins = $this->insertData($getTable, $params);
            return $ins;
        }
        return array(
            'success' => false,
            'msg' => '找不到資料表!',
        );
    }

    /**
     * 回傳tabel物件
     * 
     * @param string $table 資料表名稱
     * @return Models 回傳table Models
     */
    private function getTable($table)
    {
        switch ($table) {
            case 'history':
                return $this->history;
            case 'parameter':
                return $this->parameter;
                break;
            case 'issue':
                return $this->issue;
                break;
            case 'output':
                return $this->output;
                break;
            case 'setup':
                return $this->setup;
                break;
            case 'formLink':
                return $this->formLink;
                break;
            default:
                return null;
        }
    }

    /**
     * 新增資料
     * 
     * @param string $table 資料表名稱
     * @param array $params 傳入寫入參數
     * @return array 回傳新增結果
     * @throw Exception 回傳例外
     */
    private function insertData($table, $params)
    {
        try{
            $table->getConnection()->beginTransaction();
            $table->insert($params);
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => '新增資料成功',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }

    /**
     * 取得表單連結
     * 
     * @param string $table 資料表名稱
     * @param string $unit 單位名稱
     * @return string 回傳表單連結
     */
    public function getFormLink($table, $unit)
    {
        $obj = $this->getTable('formLink');
        $big5Table = iconv("UTF-8", "BIG-5", $table);
        $big5Unit = iconv("UTF-8", "BIG-5", $unit);
        $obj = $obj->where('table', $big5Table)
            ->where('unit', $big5Unit)
            ->first();
        if (isset($obj)) {
            return $obj->link;
        }
        return null;
    }

    /**
     * 將檔案轉成base64編碼
     * 
     * @param File $file 檔案物件
     * @return string 回傳編碼字串
     */
    public function fileEncode($file)
    {
        $MIME = $file->getMimeType();
        $data = file_get_contents($file);
        $code = base64_encode($data);
        $src = "data:$MIME;base64,$code";
        return $src;
    }

    public function getPicSrc($params)
    {
        $table = $this->history;
        $src = $table
            ->where('OS_NO', $params['OS_NO'])
            ->where('ITM', $params['ITM'])
            ->where('department', $params['department'])
            ->where('productionLine', $params['productionLine'])
            ->first()->image;
        return $src;
    }
}