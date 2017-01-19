<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\productionHistory\Defect;
use App\Service\Common;

//
class DefectRepository extends BaseRepository
{
    //
    private $defect;
    public  $common;

    //
    public function __construct(Defect $defect, Common $common) {
        $this->common = $common;
        $this->defect = $defect;
    }

    //
    public function searchDefect($name)
    {
        return $this->defect
            ->where('name', 'like', '%' . iconv('utf8', 'big5', $name) . '%');
    }

    //
    public function getDefectList()
    {
        return $this->defect;
    }

    //
    public function getDefect($id)
    {
        return $this->defect->where('id', $id);
    }

    //
    public function saveData($input)
    {
        return $this->save($this->defect, $input);
    }

    //
    public function deleteDefect($id)
    {
        return $this->delete($this->defect, $id);
    }
}
