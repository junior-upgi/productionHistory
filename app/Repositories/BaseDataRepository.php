<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

use App\Models\UPGWeb\Customer;
use App\Models\UPGWeb\Staff;
use App\Models\UPGWeb\Glass;

//
class BaseDataRepository extends BaseRepository
{
    public $staff;
    public $customer;
    public $glass;
    //
    public function __construct(
        Staff $staff,
        Customer $customer,
        Glass $glass
    ) {
        $this->staff = $staff;
        $this->customer = $customer;
        $this->glass = $glass;
    }

    //******
    public function getStaff()
    {
        $list = $this->staff->where('serving', 1);
        return $list;
    }

    //*******
    public function getCustomer()
    {
        $list = $this->customer->orderby('name');
        return $list;
    }
}