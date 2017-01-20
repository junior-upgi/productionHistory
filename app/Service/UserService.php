<?php
namespace App\Service;

use Illuminate\Support\Facades\Auth;

/**
 * Class User
 * @package App\Service
 */
trait UserService
{
    /**
     * @return null
     */
    public function getErpID()
    {
        if (Auth::check()) {
            return Auth::user()->erpID;
        }
        return null;
    }
}