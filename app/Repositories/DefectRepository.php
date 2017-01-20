<?php
namespace App\Repositories;

use App\Models\productionHistory\Defect;
use App\Service\Common;

/**
 * Class DefectRepository
 * @package App\Repositories
 */
class DefectRepository extends BaseRepository
{
    /**
     * @var Defect
     */
    private $defect;

    /**
     * @var Common
     */
    public  $common;

    /**
     * DefectRepository constructor.
     * @param Defect $defect
     * @param Common $common
     */
    public function __construct(Defect $defect, Common $common) {
        $this->common = $common;
        $this->defect = $defect;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function searchDefect($name)
    {
        return $this->defect
            ->where('name', 'like', '%' . iconv('utf8', 'big5', $name) . '%');
    }

    /**
     * @return Defect
     */
    public function getDefectList()
    {
        return $this->defect;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDefect($id)
    {
        return $this->defect->where('id', $id);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function saveData($input)
    {
        return $this->save($this->defect, $input);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteDefect($id)
    {
        return $this->delete($this->defect, $id);
    }
}
