<?php
namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use App\Models\UPGWeb\Staff;

//
class User extends Model implements AuthenticatableContract
{   
    //
    use Authenticatable;
    
    //
    protected $connection = 'upgiSystem';
    protected $table = "user";
    protected $primaryKey = 'ID';
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s';

    //
    public function staff()
    {
        return $this->hasOne(Staff::class, 'ID', 'erpID');
    }
}