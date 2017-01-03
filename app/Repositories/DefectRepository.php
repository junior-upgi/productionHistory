<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class DefectRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct();
    }

    public function getItemList()
    {
        $list = $this->item;
        return $list;
    }

    public function getItem($id)
    {
        $data = $this->item->where('id', $id);
        return $data;
    }

    public function saveData($table, $input)
    {
        $result = $this->save($table, $input);
        return $result;
    }

    public function deleteData($table, $id)
    {
        $table = $this->getTable($table);
        $result = $this->delete($table ,$id);
        return $result;
    }
}
