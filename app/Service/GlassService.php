<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/20
 * Time: 下午1:56
 */

namespace App\Service;

use App\Repositories\BaseDataRepository;

/**
 * trait GlassService
 * @package App\Service
 */
trait GlassService
{
    /**
     * @var BaseDataRepository
     */
    public $base;

    /**
     * GlassService constructor.
     * @param BaseDataRepository $base
     */
    /*
    public function __construct(BaseDataRepository $base)
    {
        $this->base = $base;
    }
    */

    /**
     * @param BaseDataRepository $base
     * @param $snm
     * @return mixed
     */
    public function getGlassBySnm(BaseDataRepository $base, $snm)
    {
        return $base->getGlassBySnm($snm);
    }
}