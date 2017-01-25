<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class CheckServiceTest extends TestCase
{
    use \App\Service\GlassService;

    protected $mock;
    protected $target;

    /**
     * setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->initMock(\App\Repositories\CheckRepository::class);
        $this->target = $this->app->make(\App\Service\CheckService::class);
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
 * 測試根據輸入資料，呼叫以ID查詢的方法
 *
 */
    public function test_searchCheck_by_id()
    {
        /** arrange */
        $input = ['snm' => '123456'];
        $expected = 'TS123456';
        $final = 'success';

        /** act */
        $mock_base = Mockery::mock(\App\Repositories\BaseDataRepository::class);
        $this->app->instance(\App\Repositories\BaseDataRepository::class, $mock_base);

        $mock_data = Mockery::mock(\App\Repositories\CheckRepository::class);
        $this->app->instance(\App\Repositories\CheckRepository::class, $mock_data);
        $target = $this->app->make(\App\Service\CheckService::class);

        $mock_base->shouldReceive('getPrdNo')
            ->once()
            ->with($input['snm'])
            ->andReturn($expected);

        $mock_data->shouldReceive('searchCheckByPrdNo')
            ->once()
            ->withAnyArgs()
            ->andReturn($final);

        $actual = $target->searchCheck($input);

        /** assert */
        $this->assertEquals($final, $actual);
    }

    /**
     * 測試根據輸入資料，呼叫以schedate查詢的方法
     *
     */
    public function test_searchCheck_by_schedate()
    {

        /** arrange */
        $input = ['start' => '2016-01-01', 'end' => '2016-12-31'];
        $expected = 'success';

        /** act */
        $mock_data = Mockery::mock(\App\Repositories\CheckRepository::class);
        $this->app->instance(\App\Repositories\CheckRepository::class, $mock_data);
        $target = $this->app->make(\App\Service\CheckService::class);


        $mock_data->shouldReceive('searchCheckBySchedate')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);

        $actual = $target->searchCheck($input);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_getScheduleList()
    {
        /** arrange */
        $request = [];
        $expected = 'success';

        /** act */
        $mock = Mockery::mock(\App\Repositories\ScheduleRepository::class);
        $this->app->instance(\App\Repositories\ScheduleRepository::class, $mock);
        $target = $this->app->make(\App\Service\CheckService::class);

        $mock->shouldReceive('getScheduleList')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $actual = $target->getScheduleList($request);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_insertCheck()
    {
        /** arrange */
        $params = [];
        $expected = 'success';

        /** act */
        $mock = Mockery::mock(\App\Repositories\CheckRepository::class);
        $this->app->instance(\App\Repositories\CheckRepository::class, $mock);
        $target = $this->app->make(\App\Service\CheckService::class);

        $mock->shouldReceive('insertCheck')
            ->once()
            ->with($params)
            ->andReturn($expected);

        $actual = $target->insertCheck($params);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_updateCheck()
    {
        /** arrange */
        $input = [
            'id' => '99999999-9999-9999-9999-999999999999',
            'prd_no' => 'TS999999'
        ];
        $params = ['prd_no' => 'TS999999'];
        $expected = 'success';

        /** act */
        $this->mock->shouldReceive('updateCheck')
            ->once()
            ->with($input['id'], $params)
            ->andReturn($expected);
        $actual = $this->target->updateCheck($input);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_deleteCheck()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';

        /** act */
        $expected = 'success';
        $this->mock->shouldReceive('deleteCheck')
            ->once()
            ->with($id)
            ->andReturn($expected);
        $actual = $this->target->deleteCheck($id);

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
