<?php

use App\Repositories\BaseDataRepository;
use App\Repositories\CheckRepository;
use App\Repositories\ScheduleRepository;
use App\Service\CheckService;
use Illuminate\Database\Eloquent\Builder;
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
        $this->mock = $this->initMock(CheckRepository::class);
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
     *
     */
    public function test_getCheckList()
    {
        /** arrange */
        $expected = new Class{ public function get() {} };

        /** act */
        $mock = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock);
        $target = $this->app->make(CheckService::class);
        $mock->shouldReceive('getCheckList')
            ->once()
            ->andReturn($expected);
        $actual = $target->getCheckList();

        /** assert */
        $this->assertEquals($expected->get(), $actual);
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
        $final = new class { public function get() {} };;

        /** act */
        $mock_base = Mockery::mock(BaseDataRepository::class);
        $this->app->instance(BaseDataRepository::class, $mock_base);

        $mock_data = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock_data);
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
        $this->assertEquals($final->get(), $actual);
    }

    /**
     * 測試根據輸入資料，呼叫以schedate查詢的方法
     *
     */
    public function test_searchCheck_by_schedate()
    {

        /** arrange */
        $input = ['start' => '2016-01-01', 'end' => '2016-12-31'];
        $expected = new class { public function get() {} };

        /** act */
        $mock_data = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock_data);
        $target = $this->app->make(\App\Service\CheckService::class);


        $mock_data->shouldReceive('searchCheckBySchedate')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);

        $actual = $target->searchCheck($input);

        /** assert */
        $this->assertEquals($expected->get(), $actual);
    }

    /**
     *
     */
    public function test_getScheduleList()
    {

        /** arrange */
        $request = [];
        $expected = new class {
            public function whereNotExists() {
                return $this;
            }
            public function get() {
                return 'success';
            }
        };

        /** act */
        $mock = Mockery::mock(ScheduleRepository::class);
        $this->app->instance(ScheduleRepository::class, $mock);
        $target = $this->app->make(\App\Service\CheckService::class);

        $mock->shouldReceive('getScheduleList')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);
        $actual = $target->getScheduleList($request);

        /** assert */
        $this->assertEquals($expected->get(), $actual);
    }

    public function test_insertCheck()
    {
        /** arrange */
        $input = ['id' => '2EEC2F65-EF36-687D-8AFC-CBBB14499500', 'decoration' => array()];
        $params = ['id' => '2EEC2F65-EF36-687D-8AFC-CBBB14499500', 'decoration' => ''];
        $expected = 'success';

        /** act */
        $mock = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock);
        $target = $this->app->make(\App\Service\CheckService::class);

        $mock->shouldReceive('insertCheck')
            ->once()
            ->with($params)
            ->andReturn($expected);

        $actual = $target->insertCheck($input);

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

    public function test_getScheduleCustomer()
    {
        /** arrange */
        $input = [];
        $mock = Mockery::mock(ScheduleRepository::class);
        $this->app->instance(ScheduleRepository::class, $mock);

        /** act */
        $expected = 'success';
        $mock->shouldReceive('getScheduleCustomer')
            ->once()
            ->with($input)
            ->andReturn($expected);
        $target = $this->app->make(CheckService::class);
        $actual = $target->getScheduleCustomer($input);

        /** assert */
        $this->assertEquals($expected, $actual);

    }

    public function test_getCheck()
    {
        /** arrange */
        $id = 'checkID';
        $customer = 'customer';
        $data = ['prd_no' => 'prd_no', 'glassProdLineID' => '1-1', 'schedate' => '2000-01-01'];
        $collection = new class {
            public function first()
            {
                return ['prd_no' => 'prd_no', 'glassProdLineID' => '1-1', 'schedate' => '2000-01-01'];
            }
        };
        $mock_schedule = Mockery::mock(ScheduleRepository::class);
        $this->app->instance(ScheduleRepository::class, $mock_schedule);

        $mock_check = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock_check);

        /** act */
        $mock_schedule->shouldReceive('getScheduleCustomerByParams')
            ->once()
            ->with('run', $data['prd_no'], $data['glassProdLineID'], $data['schedate'])
            ->andReturn($customer);

        $mock_check->shouldReceive('getCheck')
            ->once()
            ->with($id)
            ->andReturn($collection);

        $data['customer'] = $customer;
        $expected = $data;

        $target = $this->app->make(CheckService::class);
        $actual = $target->getCheck($id);

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
