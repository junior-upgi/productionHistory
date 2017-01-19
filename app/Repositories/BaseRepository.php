<?php
namespace App\Repositories;

//
class BaseRepository
{   
    //
    public function __construct() {
        
    }
    
    //
    public function getCollection($table, $where = null)
    {
        return $this->common->where($table, $where);
    }

    //
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

    //
    public function insert($table, $params)
    {
        $obj = $this->common->insert($table, $params);
        return $obj;
    }

    //
    public function update($table, $id, $params, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->update($table, $params);
        return $obj;
    }

    //
    public function delete($table, $id, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->delete($table);
        return $obj;
    }
    
    //
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