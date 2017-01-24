<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\productionHistory\DefectCheck;

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

    /**
     *
     */
    public function test_getCheckList()
    {
        /** arrange */
        $table = new DefectCheck();
        $expected = $table;

        /** act */
        $mock = Mockery::mock(\App\Service\CheckService::class);
        $this->app->instance(\App\Service\CheckService::class, $mock);

        $mock->shouldReceive('getCheckList')
            ->once()
            ->withAnyArgs()
            ->andReturn($table);
        $target = $this->app->make(\App\Http\Controllers\CheckController::class);
        $actual = $target->getCheckList();

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


    public function test_getScheduleList()
    {
        /** arrange */
        $expected = 'success';
        $mock = Mockery::mock(\App\Service\CheckService::class);
        $this->app->instance(\App\Service\CheckService::class, $mock);

        /** act */
        $mock->shouldReceive('getScheduleList')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $target = $this->app->make(\App\Http\Controllers\CheckController::class);
        $actual = $target->getScheduleList();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_insertCheck()
    {
        /** arrange */
        $expected = 'success';

        /** act */
        $this->mock->shouldReceive('insertCheck')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $actual = $this->target->insertCheck();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_updateCheck()
    {
        /** arrange */
        $input = [];
        $expected = 'success';

        /** act */
        $this->mock->shouldReceive('updateCheck')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);

        $actual = $this->target->updateCheck($input);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_deleteCheck()
    {
        /** arrange */
        $id = request()->input('id');

        /** act */
        $expected = 'success';
        $this->mock->shouldReceive('deleteCheck')
            ->once()
            ->with($id)
            ->andReturn($expected);
        $actual = $this->target->deleteCheck();

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
