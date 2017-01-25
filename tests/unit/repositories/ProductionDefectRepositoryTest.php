<?php

use App\Models\productionHistory\ProductionDefect;
use App\Repositories\ProductionDefectRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductionDefectRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $mock;
    protected $target;

    /**
     * setUp()
     */
    public function setUp()
    {
        parent::setUp();
        //$this->mock = $this->initMock(ProductionDefect::class);
        $this->target = $this->app->make(ProductionDefectRepository::class);
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

    public function test_getProductionDefectList()
    {
        /** arrange */
        $table = new ProductionDefect();
        $checkID = '00000000-0000-0000-0000-000000000000';
        $params = [
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000003' , 'sequence' => 3],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000004' , 'sequence' => 4],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000005' , 'sequence' => 5],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000001' , 'sequence' => 1],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000002' , 'sequence' => 2],
        ];
        $table->insert($params);

        /** act */
        $expected1 = $table->get()->count();
        $actual1 = $this->target->getProductionDefectList($checkID)->get()->count();

        $expected2 = [
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000001' , 'sequence' => 1],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000002' , 'sequence' => 2],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000003' , 'sequence' => 3],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000004' , 'sequence' => 4],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000005' , 'sequence' => 5],
        ];
        $actual2 = $this->target->getProductionDefectList($checkID)->select('checkID', 'defectID', 'sequence')->get()->toArray();

        /** assert */
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }

    public function test_insertProductionDefect()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'defectID' => '11111111-1111-1111-1111-111111111111',
            'checkID' => '22222222-2222-2222-2222-222222222222',
            'itemID' => '33333333-3333-3333-3333-333333333333',
        ];
        $table = new ProductionDefect();

        /** act */
        $actual1 = $this->target->insertProductionDefect($params);
        $actual2 = $table->where('id', $id)->first();
        $expected = $id;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->id);
    }

    public function test_updateProductionDefect()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'defectID' => '11111111-1111-1111-1111-111111111111',
            'checkID' => '22222222-2222-2222-2222-222222222222',
            'itemID' => '33333333-3333-3333-3333-333333333333',
            'value' => 'old value'
        ];
        $table = new ProductionDefect();
        $table->insert($params);
        $set = ['value' => 'new value'];

        /** act */
        $actual1 = $this->target->updateProductionDefect($id, $set);
        $actual2 = $table->where('id', $id)->first();
        $expected = $set['value'];

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->value);
    }

    public function test_deleteProductionDefect()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';
        $params = [
            'id' => $id,
            'defectID' => '11111111-1111-1111-1111-111111111111',
            'checkID' => '22222222-2222-2222-2222-222222222222',
            'itemID' => '33333333-3333-3333-3333-333333333333',
            'value' => 'old value'
        ];
        $table = new ProductionDefect();
        $table->insert($params);

        /** act */
        $actual1 = $this->target->deleteProductionDefect($id);
        $actual2 = $table->where('id', $id);
        $actual3 = $table->withTrashed()->where('id', $id);
        $expected = 0;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->count());
        $this->assertNotEquals(null, $actual3->first()->deleted_at);
    }
}
