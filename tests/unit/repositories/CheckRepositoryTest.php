<?php

use App\Models\UPGWeb\Glass;
use App\Service\DataFormatService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\productionHistory\DefectCheck;
use App\Repositories\CheckRepository;
use Illuminate\Support\Facades\App;

class CheckRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    use DataFormatService;

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
        $expected = $table
            ->join('UPGWeb.dbo.glass', 'defectCheck.prd_no', 'glass.prd_no')
            ->orderBy('schedate', 'desc')
            ->select('defectCheck.*', 'glass.snm');

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

    /**
     * 測試以日期搜尋
     *
     */
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

    public function test_getCheck()
    {
        /** arrange */

        $table = new DefectCheck();
        $glass = new Glass();
        $prd_no = $glass->first()->prd_no;
        $data = ['id' => '00000000-0000-0000-0000-000000000000', 'schedate' => '2016/01/01', 'prd_no' => $prd_no];

        /** act */
        $table->insert($data);
        $expected = $data['id'];
        $target = App::make(CheckRepository::class);
        $actual = $target->getCheck($data['id'])->first();

        /** assert */
        $this->assertEquals($expected, $actual->id);
    }

    public function test_insertCheck()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'prd_no' => 'TS999999'
        ];
        $table = new DefectCheck();

        /** act */
        $target = App::make(CheckRepository::class);
        $actual1 = $target->insertCheck($params);
        $actual2 = $table->where('id', $id)->first();
        $expected = $id;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->id);
    }

    public function test_insertCheck_data_existed()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'prd_no' => 'TS999999',
            'deleted_at' => \Carbon\Carbon::now()
        ];
        DefectCheck::insert($params);

        /** act */
        $target = App::make(CheckRepository::class);
        $actual1 = $target->insertCheck($params);
        $actual2 = DefectCheck::where('id', $id)->first();
        $expected = $id;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->id);
    }

    public function test_insertCheck_exception()
    {
        /** arrange */
        $error = ['error' => 'error'];

        /** act */
        $actual = $this->target->insertCheck($error);

        /** assert */
        $this->assertFalse($actual['success']);
    }

    public function test_updateCheck()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'prd_no' => 'TS999999'
        ];
        $table = new DefectCheck();
        $table->insert($params);
        $set = ['prd_no' => 'TS000000'];

        /** act */
        $target = App::make(CheckRepository::class);
        $actual1 = $target->updateCheck($id, $set);
        $actual2 = $table->where('id', $id)->first();
        $expected = $set['prd_no'];

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->prd_no);
    }

    public function test_updateCheck_exception()
    {
        /** arrange */
        $error = ['error' => 'error'];

        /** act */
        $actual = $this->target->updateCheck('', $error);

        /** assert */
        $this->assertFalse($actual['success']);
    }

    public function test_deleteCheck()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'prd_no' => 'TS999999'
        ];
        $table = new DefectCheck();
        $table->insert($params);

        /** act */
        $actual1 = $this->target->deleteCheck($id);
        $actual2 = $table->where('id', $id);
        $actual3 = $table->withTrashed()->where('id', $id);
        $expected = 0;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->count());
        $this->assertNotEquals(null, $actual3->first()->deleted_at);
    }

    public function test_deleteCheck_exception()
    {
        /** arrange */
        $error = new Class () {};

        /** act */
        $actual = $this->target->deleteCheck($error);

        /** assert */
        $this->assertFalse($actual['success']);
    }

    public function test_params()
    {
        $params1 = ['id' => 'test1'];
        $params2 = [['id' => 'test1'], ['id' => 'test2']];

        $actual1 = $this->setTimestamp('created', $params1);
        $actual2 = $this->setTimestamp('created', $params2);

        $this->assertTrue(isset($actual1['created_at']));
        $this->assertTrue(isset($actual2[0]['created_at']));
    }
}
