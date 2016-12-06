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


    public function params($input, $addIgnore)
    {
        $ignore = array_merge(['_token', 'type', 'id'], $addIgnore);
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
                'msg' => $e['errorInfo'][2],
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
    public function update($table, $params)
    {
        try {
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
                'msg' => $e['errorInfo'][2],
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
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
}