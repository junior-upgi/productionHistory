<?php
/**
 * CheckRepository
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use App\Models\productionHistory\DefectCheck;

/**
 * Class CheckRepository
 * @package App\Repositories
 */
class CheckRepository
{
    /**
     * @var DefectCheck
     */
    public $check;

    /**
     * CheckRepository constructor.
     * @param DefectCheck $check
     */
    public function __construct(DefectCheck $check)
    {
        $this->check = $check;
    }

    /**
     * @return DefectCheck
     */
    public function getCheckList()
    {
        return $this->check;
    }

    /**
     * search Check by prd_no and return collection
     *
     * @param $prd_no
     * @return mixed
     */
    public function searchCheckByPrdNo($prd_no)
    {
        return $this->check->where('prd_no', $prd_no);
    }

    /**
     * search Check by schedate and return collection
     * start, end is set than search
     *
     * @param $start
     * @param $end
     * @return mixed
     */
    public function searchCheckBySchedate($start, $end)
    {
        return $this->check->where(function ($q) use ($start, $end) {
            if (isset($start)) {
                $q->where('schedate', '>=', $start);
            }
            if (isset($end)) {
                $q->where('schedate', '<=', $end);
            }
        });
    }
}