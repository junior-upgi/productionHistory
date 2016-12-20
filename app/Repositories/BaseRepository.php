<?php
namespace App\Repositories;

use App\Service\Common;

class BaseRepository
{
    public $common;
    
    public function __construct() {
        $this->common = new Common();
    }
    
    public function getCollection($table, $where = null)
    {
        $table = $this->getTable($table);
        $obj = $this->common->where($table, $where);
        return $obj;
    }

    public function save($table, $input, $addIgnore = [], $pk = 'id')
    {   
        $table = $this->getTable($table);
        $id = $input['id'];
        $type = $input['type'];
        switch ($type) {
            case 'add':
                $params = $this->common->params($input, $addIgnore);
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

    public function insert($table, $params)
    {
        $obj = $this->common->insert($table, $params);
        return $obj;
    }

    public function update($table, $id, $params, $pk)
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->update($table, $params);
        return $obj;
    }

    public function delete($table, $id, $pk)
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->delete($table);
        return $obj;
    }

    protected function getTable($table)
    {
        switch ($table) {
            default:
                throw new Exception('No model exception');
        }
    }
}