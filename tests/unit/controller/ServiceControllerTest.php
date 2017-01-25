<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceController extends TestCase
{
    protected $mock;
    protected $target;

    /**
     * setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->initMock(\App\Service\CheckService::class);
        $this->target = $this->app->make(\App\Http\Controllers\CheckController::class);
    }

    /**
     * tearDown()
     */
    public function tearDown()
    {
        $this->target = null;
        $this->mock = null;
        parent::tearDown();
    }

    public function test_getCustomer()
    {
        /** arrange */
        $mock = Mockery::mock(\App\Repositories\BaseDataRepository::class);
        $this->app->instance(\App\Repositories\BaseDataRepository::class, $mock);
        $target = $this->app->make(\App\Http\Controllers\ServiceController::class);

        /** act */
        $expected = 'success';
        $mock->shouldReceive('getCustomer')
            ->once()
            ->andReturn($expected);
        $actual = $target->getCustomer();

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
