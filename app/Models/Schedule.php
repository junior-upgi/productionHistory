<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{   
    protected $connection = 'Z_DB_U105';
    protected $table = "tbmkno";
    public $keyType = 'string';

    public function scheduleList()
    {
        $list = $this
            ->join('DB_U105.dbo.PRDT', 'Z_DB_U105.dbo.tbmkno.prd_no', '=', 'DB_U105.dbo.PRDT.PRD_NO')
            ->join('UPGWeb.dbo.vCustomer', 'Z_DB_U105.dbo.tbmkno.cus_no', 'UPGWeb.dbo.vCustomer.ID');
        return $list;
    }
}