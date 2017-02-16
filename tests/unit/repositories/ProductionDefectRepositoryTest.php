<?php

use App\Models\productionHistory\Defect;
use App\Models\productionHistory\ProductionData;
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

    public function test_yes()
    {

    }

    public function test_getProductionDataList()
    {
        /** arrange */
        $table = new productionData();
        $checkID = '00000000-0000-0000-0000-000000000000';
        $checkID2 = '00000000-0000-0000-2222-000000000000';
        $params = [
            ['checkID' => $checkID, 'prodDate' => '2000-01-02', 'classType' => '1'],
            ['checkID' => $checkID, 'prodDate' => '2000-01-01', 'classType' => '2'],
            ['checkID' => $checkID, 'prodDate' => '2000-01-01', 'classType' => '3'],
            ['checkID' => $checkID2, 'prodDate' => '2000-01-01', 'classType' => '2'],
            ['checkID' => $checkID2, 'prodDate' => '2000-01-01', 'classType' => '3'],
            ['checkID' => $checkID2, 'prodDate' => '2000-01-02', 'classType' => '1'],
        ];
        $table->insert($params);

        /** act */
        $expected1 = $table->where('checkID', $checkID)->get()->count();
        $actual1 = $this->target->getProductionDataList($checkID)->get()->count();

        $expected2 = [
            ['checkID' => $checkID, 'prodDate' => '2000-01-01 00:00:00', 'classType' => '2'],
            ['checkID' => $checkID, 'prodDate' => '2000-01-01 00:00:00', 'classType' => '3'],
            ['checkID' => $checkID, 'prodDate' => '2000-01-02 00:00:00', 'classType' => '1'],
        ];
        $actual2 = $this->target->getProductionDataList($checkID)->select('checkID', 'prodDate', 'classType')->get()->toArray();

        /** assert */
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }

    public function test_getProductionDefectList()
    {
        /** arrange */
        $table = new ProductionDefect();
        $checkID = '00000000-0000-0000-0000-000000000000';
        $params = [
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000003', 'sequence' => 3],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000004', 'sequence' => 4],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000005', 'sequence' => 5],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000001', 'sequence' => 1],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000002', 'sequence' => 2],
        ];
        $table->insert($params);
        
        $defect = new Defect();
        $defect->insert([
            ['id' => '00000000-0000-0000-0000-000000000001', 'name' => 'name1'],
            ['id' => '00000000-0000-0000-0000-000000000002', 'name' => 'name2'],
            ['id' => '00000000-0000-0000-0000-000000000003', 'name' => 'name3'],
            ['id' => '00000000-0000-0000-0000-000000000004', 'name' => 'name4'],
            ['id' => '00000000-0000-0000-0000-000000000005', 'name' => 'name5'],
        ]);

        /** act */
        $expected1 = $table->where('checkID', $checkID)->get()->count();
        $actual1 = $this->target->getProductionDefectList($checkID)->get()->count();

        $expected2 = [
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000001' , 'sequence' => 1, 'name' => 'name1'],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000002' , 'sequence' => 2, 'name' => 'name2'],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000003' , 'sequence' => 3, 'name' => 'name3'],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000004' , 'sequence' => 4, 'name' => 'name4'],
            ['checkID' => $checkID, 'defectID' => '00000000-0000-0000-0000-000000000005' , 'sequence' => 5, 'name' => 'name5'],
        ];
        $actual2 = $this->target->getProductionDefectList($checkID)->select('checkID', 'defectID', 'sequence', 'defect.name')->get()->toArray();

        /** assert */
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }

    public function test_insertProductionDefect()
    {
        /** arrange */
        $productionDataID = '99999999-9999-9999-9999-999999999999';
        $checkID = '33333333-3333-3333-3333-333333333333';
        $dataParams = [
            'id' => $productionDataID,
            'checkID' => $checkID,
        ];
        $defectParams = [
            ['productionDataID' => $productionDataID, 'checkID' => $checkID],
            ['productionDataID' => $productionDataID, 'checkID' => $checkID],
        ];
        $defect = new ProductionDefect();
        $data = new ProductionData();

        /** act */
        $actual1 = $this->target->insertProductionDefect($dataParams, $defectParams);
        $actual2 = $data->where('id', $productionDataID);
        $actual3 = $defect->where('productionDataID', $productionDataID);
        $expected2 = $checkID;

        $dataParams['error'] = 'error';
        $defectParams['error'] = 'error';

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected2, $actual2->first()->checkID);
        $this->assertEquals(1, $actual2->count());
        $this->assertEquals(2, $actual3->count());
    }

    public function test_insertProductionDefect_exception()
    {
        /** arrange */
        $dataParams = ['nonExistsField' => '1'];
        $defectParams = [
            ['nonExistsField' => '1'],
            ['nonExistsField' => '2'],
        ];

        /** act */
        $actual = $this->target->insertProductionDefect($dataParams, $defectParams);

        /** assert */
        $this->assertFalse($actual['success']);
    }

    public function test_updateProductionDefect()
    {
        /** arrange */
        $productionDataID = '99999999-9999-9999-9999-999999999999';
        $checkID = '33333333-3333-3333-3333-333333333333';
        $dataParams = [
            'id' => $productionDataID,
            'checkID' => $checkID,
            'classRemark' => 'old'
        ];
        $defectParams = [
            ['productionDataID' => $productionDataID, 'checkID' => $checkID,
                'itemID' => '55555555-5555-5555-5555-555555555555',
                'defectID' => '44444444-4444-4444-4444-444444444444', 'value' => 1],
            ['productionDataID' => $productionDataID, 'checkID' => $checkID,
                'itemID' => '55555555-5555-5555-5555-555555555555',
                'defectID' => '44444444-4444-4444-4444-444444444445', 'value' => 1],
        ];
        $defect = new ProductionDefect();
        $data = new ProductionData();

        /** act */
        $data->insert($dataParams);
        $defect->insert($defectParams);
        $dataParams['classRemark'] = 'new';
        $defectParams[0]['value'] = 2;
        $defectParams[1]['value'] = 3;
        $actual1 = $this->target->updateProductionDefect($dataParams, $defectParams);
        $actual2 = $data->where('id', $productionDataID);
        $actual3 = $defect->where('productionDataID', $productionDataID);
        $actual4 = $defect->where('productionDataID', $productionDataID);
        $expected2 = $checkID;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected2, $actual2->first()->checkID);
        $this->assertEquals('new', $actual2->first()->classRemark);
        $this->assertEquals(2, $actual3->where('defectID', '44444444-4444-4444-4444-444444444444')->first()->value);
        $this->assertEquals(3, $actual4->where('defectID', '44444444-4444-4444-4444-444444444445')->first()->value);
    }

    public function test_updateProductionDefect_exception()
    {
        /** arrange */
        $dataParams = ['nonExistsField' => '1'];
        $defectParams = [
            ['nonExistsField' => '1'],
            ['nonExistsField' => '2'],
        ];

        /** act */
        $actual = $this->target->updateProductionDefect($dataParams, $defectParams);

        /** assert */
        $this->assertFalse($actual['success']);
    }

    public function test_deleteProductionDefect()
    {
        /** arrange */
        $productionDataID = '99999999-9999-9999-9999-999999999999';
        $checkID = '33333333-3333-3333-3333-333333333333';
        $dataParams = [
            'id' => $productionDataID,
            'checkID' => $checkID,
        ];
        $defectParams = [
            ['productionDataID' => $productionDataID, 'checkID' => $checkID],
            ['productionDataID' => $productionDataID, 'checkID' => $checkID],
        ];
        $defect = new ProductionDefect();
        $data = new ProductionData();

        /** act */
        $data->insert($dataParams);
        $defect->insert($defectParams);
        $actual1 = $this->target->deleteProductionDefect($productionDataID);
        $actual2 = $data->where('id', $productionDataID);
        $actual3 = $defect->where('productionDataID', $productionDataID);
        $expected = 0;

        /** assert */
        $this->assertTrue($actual1['success']);
        $this->assertEquals($expected, $actual2->count());
        $this->assertEquals($expected, $actual3->count());
    }

    public function test_deleteProductionDefect_exception()
    {
        /** arrange */
        $error = new Class() {};

        /** act */
        $actual = $this->target->deleteProductionDefect($error);

        /** assert */
        $this->assertFalse($actual['success']);
    }

    public function test_getDefectAvg()
    {
        /** arrange */
        $checkID = '99999999-9999-9999-9999-999999999999';
        $productionData =[
            ['id' => '00000000-0000-0000-0000-000000000000',
                'checkID' => $checkID],
            ['id' => '00000000-0000-0000-2222-000000000000',
                'checkID' => $checkID]
        ];
        $productionDefect = [
            ['productionDataID' => '00000000-0000-0000-0000-000000000000',
                'checkID' => $checkID,
                'defectID' => '00000000-1111-0000-0000-000000000000',
                'itemID' => '00000000-2222-0000-0000-000000000000',
                'value' => 10],
            ['productionDataID' => '00000000-0000-0000-2222-000000000000',
                'checkID' => $checkID,
                'defectID' => '00000000-1111-0000-0000-000000000000',
                'itemID' => '00000000-2222-0000-0000-000000000000',
                'value' => 20]
        ];
        $defect = ['name' => 'name1', 'id' => '00000000-1111-0000-0000-000000000000'];

        /** act */
        ProductionData::insert($productionData);
        ProductionDefect::insert($productionDefect);
        Defect::insert($defect);
        $actual = $this->target->getDefectAvg($checkID);
        $expected = 15;

        /** assert */
        $this->assertEquals($expected, $actual[0]['value']);
    }
}
