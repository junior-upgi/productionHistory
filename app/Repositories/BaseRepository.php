<?php
namespace App\Repositories;


/**
 * Class BaseRepository
 * @package App\Repositories
 */
class BaseRepository
{
    /**
     * @param $table
     * @param null $where
     * @return mixed
     */
    public function getCollection($table, $where = null)
    {
        return $this->common->where($table, $where);
    }

    /**
     * @param $table
     * @param $input
     * @param array $addIgnore
     * @param string $pk
     * @param bool $saveID
     * @return mixed
     */
    public function save($table, $input, $addIgnore = [], $pk = 'id', $saveID = false)
    {   
        $id = $input['id'];
        $type = $input['type'];
        switch ($type) {
            case 'add':
                $params = $this->common->params($input, $addIgnore, $saveID);
                $tran = $this->insert($table, $params);
                break;
            case 'edit':
                $params = $this->common->params($input, $addIgnore);
                $tran = $this->update($table, $id, $params, $pk);
                break;
            case 'delete':
                 $tran = $this->delete($table, $id, $pk);
                 break;
            default:
                throw new Exception('no action Exception');
        }
        return $tran;
    }

    /**
     * @param $table
     * @param $params
     * @return mixed
     */
    public function insert($table, $params)
    {
        $obj = $this->common->insert($table, $params);
        return $obj;
    }

    /**
     * @param $table
     * @param $id
     * @param $params
     * @param string $pk
     * @return mixed
     */
    public function update($table, $id, $params, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->update($table, $params);
        return $obj;
    }

    /**
     * @param $table
     * @param $id
     * @param string $pk
     * @return mixed
     */
    public function delete($table, $id, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->delete($table);
        return $obj;
    }

    /**
     * @param $table
     * @param $id
     * @param string $pk
     * @return array
     */
    public function forceDelete($table, $id, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $table->forceDelete();
        return [
            'success' => true,
            'msg' => 'success'
        ];
    }
}