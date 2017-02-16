<?php

use App\Service\UserService;
use App\Models\upgiSystem\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;

class UserServiceTest extends TestCase
{
    use UserService;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_getErpID()
    {
        /** arrange */
        $params = ['id' => '00000000-0000-0000-0000-000000000000',
            'mobileSystemAccount' => 'test', 'erpID' => 'testErpID'];
        User::insert($params);
        Auth::loginUsingId($params['id'], true);

        /** act */
        $expected = $params['erpID'];
        $actual = $this->getErpID();
        Auth::logout();
        $expected2 = null;
        $actual2 = $this->getErpID();

        /** assert */
        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected2, $actual2);
    }
}
