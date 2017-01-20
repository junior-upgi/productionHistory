<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class CheckControllerTest
 */
class CheckControllerTest extends TestCase
{
    protected $mock;
    protected $target;

    /**
     * setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->initMock(\App\Repositories\CheckRepository::class);
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

    /**
     *
     */
    public function test_getCheckList()
    {
        /** arrange */
        $table = \App\Models\productionHistory\DefectcCheck::class;
        $expected = $table;

        /** act */
        $this->mock->shouldReceive('getCheckList')
            ->once()
            ->withAnyArgs()
            ->andReturn($table);
        $actual = $this->target->getCheckList();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function test_searchCheck()
    {
        /** arrange */
        $expected = 'success';
        $mock = Mockery::mock(\App\Service\CheckService::class);
        $this->app->instance(\App\Service\CheckService::class, $mock);

        /** act */
        $mock->shouldReceive('searchCheck')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $target = $this->app->make(\App\Http\Controllers\CheckController::class);
        $actual = $target->searchCheck();

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
