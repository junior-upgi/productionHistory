<?php
/**
 * 基本資料存取庫
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories;

use DB;

use App\Models\UPGWeb\Customer;
use App\Models\UPGWeb\Staff;
use App\Models\UPGWeb\Glass;

/**
 * Class BaseDataRepository
 *
 * @package App\Repositories
 */
class BaseDataRepository extends BaseRepository
{
    public $staff;
    public $customer;
    public $glass;

    /**
     * construct
     *
     * @param Staff $staff
     * @param Customer $customer
     * @param Glass $glass
     */
    public function __construct(
        Staff $staff,
        Customer $customer,
        Glass $glass
    ) {
        $this->staff = $staff;
        $this->customer = $customer;
        $this->glass = $glass;
    }

    /**
     * 取得員工
     * 
     * @return Staff 回傳結果
     */
    public function getStaff()
    {
        return $this->staff->where('serving', 1);
    }

    /**
     * 取得客戶資料
     * 
     * @return Customer 回傳結果
     */
    public function getCustomer()
    {
        return $this->customer->orderby('name');
    }

    /**
     * 取得所有瓶號資料
     * 
     * @return Glass 回傳結果
     */
    public function getGlass()
    {
        return $this->glass->orderBy('snm');
    }

    /**
     * 以產品名稱回傳產品資訊
     *
     * @param $snm
     * @return mixed
     */
    public function getPrdNo($snm)
    {
        return $this->glass->where('snm', 'like', "%$snm%")->first()->prd_no;
    }
}