<?php

use App\Http\Controllers\CheckController;
use App\Service\CheckService;
use App\Service\ProductionDefectService;
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
        $this->mock = $this->initMock(CheckService::class);
        $this->target = $this->app->make(CheckController::class);
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
        $expected = 'success';

        /** act */
        $mock = Mockery::mock(CheckService::class);
        $this->app->instance(CheckService::class, $mock);

        $mock->shouldReceive('getCheckList')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected   );
        $target = $this->app->make(CheckController::class);
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
        $mock = Mockery::mock(CheckService::class);
        $this->app->instance(CheckService::class, $mock);

        /** act */
        $mock->shouldReceive('searchCheck')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $target = $this->app->make(CheckController::class);
        $actual = $target->searchCheck();

        /** assert */
        $this->assertEquals($expected, $actual);
    }


    public function test_getScheduleList()
    {
        /** arrange */
        $expected = 'success';
        $mock = Mockery::mock(CheckService::class);
        $this->app->instance(CheckService::class, $mock);

        /** act */
        $mock->shouldReceive('getScheduleList')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $target = $this->app->make(CheckController::class);
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

    /**
     *
     */
    public function test_getProductionDefectList()
    {
        /** arrange */
        $checkID = request()->input('id');
        $mock = Mockery::mock(ProductionDefectService::class);
        $this->app->instance(ProductionDefectService::class, $mock);

        /** act */
        $expected = 'success';
        $mock->shouldReceive('getProductionDefectList')
            ->once()
            ->with($checkID)
            ->andReturn($expected);
        $target = $this->app->make(CheckController::class);
        $actual = $target->getProductionDefectList();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_insertProductionDefect()
    {
        /** arrange */
        $input = request()->input();
        $mock = Mockery::mock(ProductionDefectService::class);
        $this->app->instance(ProductionDefectService::class, $mock);

        /** act */
        $expected = [];
        $mock->shouldReceive('insertProductionDefect')
            ->once()
            ->with($input)
            ->andReturn($expected);
        $target = $this->app->make(CheckController::class);
        $actual = $target->insertProductionDefect();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_updateProductionDefect()
    {
        /** arrange */
        $input = request()->input();
        $mock = Mockery::mock(ProductionDefectService::class);
        $this->app->instance(ProductionDefectService::class, $mock);

        /** act */
        $expected = [];
        $mock->shouldReceive('updateProductionDefect')
            ->once()
            ->with($input)
            ->andReturn($expected);
        $target = $this->app->make(CheckController::class);
        $actual = $target->updateProductionDefect();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_deleteProductionDefect()
    {
        /** arrange */
        $input = request()->input('id');
        $mock = Mockery::mock(ProductionDefectService::class);
        $this->app->instance(ProductionDefectService::class, $mock);

        /** act */
        $expected = [];
        $mock->shouldReceive('deleteProductionDefect')
            ->once()
            ->with($input)
            ->andReturn($expected);
        $target = $this->app->make(CheckController::class);
        $actual = $target->deleteProductionDefect($input);

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
