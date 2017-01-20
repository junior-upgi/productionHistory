<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\productionHistory\DefectCheck;
use App\Repositories\CheckRepository;

class CheckRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var
     */
    protected $target;

    /**
     * @var
     */
    protected $mock;

    /**
     * setUP()
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        //$this->mock = $this->initMock(DefectCheck::class);
        $this->target = $this->app->make(CheckRepository::class);
    }

    /**
     * tearDown()
     *
     * @return void
     */
    public function tearDown()
    {
        $this->target = null;
        $this->mock = null;
        parent::tearDown();
    }

    /**
     * test getCheckList()
     *
     * @return void
     */
    public function test_getCheckList()
    {
        /** arrange */
        $table = new DefectCheck();

        /** act */
        $expected = $table;
        $target = App::make(CheckRepository::class);

        $actual = $target->getCheckList();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    /**
     * 測試以瓶號搜尋
     * searchCheck 本身以prd_no搜尋
     *
     */
    public function test_searchCheckByPrdNo()
    {
        /** arrange */
        $testData = [
            'id' => '00000000-0000-0000-0000-00000000000',
            'prd_no' => 'TS999999'
        ];

        $search = 'TS999999';
        $table = new DefectCheck();

        /** act */
        $table->insert($testData);
        $expected = 1;
        $target = App::make(CheckRepository::class);

        $actual = $target->searchCheckByPrdNo($search)->count();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_searchCheckBySchedate()
    {
        /** arrange */
        $data = [
            ['id' => '00000000-0000-0000-0000-000000000000', 'schedate' => '2016/01/01'],
            ['id' => '00000000-0000-0000-0000-000000000001', 'schedate' => '2016/03/01'],
            ['id' => '00000000-0000-0000-0000-000000000002', 'schedate' => '2016/05/01'],
            ['id' => '00000000-0000-0000-0000-000000000003', 'schedate' => '2016/07/01'],
            ['id' => '00000000-0000-0000-0000-000000000004', 'schedate' => '2016/09/01'],
        ];
        $start = '2016/02/01';
        $end = '2016/06/01';

        $table = new DefectCheck();

        /** act */
        $table->insert($data);
        $expected = 2;
        $target = App::make(CheckRepository::class);

        $actual = $target->searchCheckBySchedate($start, $end)->count();

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
