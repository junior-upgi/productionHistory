<?php

use App\Service\ProductionDefectService;
use App\Repositories\ProductionDefectRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductionDefectServiceTest extends TestCase
{
    protected $mock;
    protected $target;

    /**
     * setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->initMock(ProductionDefectRepository::class);
        $this->target = $this->app->make(ProductionDefectService::class);
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
        $checkID = '00000000-0000-0000-0000-000000000000';

        /** act */
        $expected = 'success';
        $this->mock->shouldReceive('getProductionDefectList')
            ->once()
            ->with($checkID)
            ->andReturn($expected);
        $actual = $this->target->getProductionDefectList($checkID);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_insertProductionDefect()
    {
        /** arrange */
        $input = [];
        $params = [];

        /** act */
        $expected = 'success';
        $this->mock->shouldReceive('insertProductionDefect')
            ->once()
            ->with($params)
            ->andReturn($expected);
        $actual = $this->target->insertProductionDefect($input);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_updateProductionDefect()
    {
        /** arrange */
        $input = ['id' => '00000000-0000-0000-0000-000000000000'];
        $params = [];

        /** act */
        $expected = 'success';
        $this->mock->shouldReceive('updateProductionDefect')
            ->once()
            ->with($input['id'], $params)
            ->andReturn($expected);
        $actual = $this->target->updateProductionDefect($input);

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    public function test_deleteProductionDefect()
    {
        /** arrange */
        $id = '99999999-9999-9999-9999-999999999999';

        /** act */
        $expected = 'success';
        $this->mock->shouldReceive('deleteProductionDefect')
            ->once()
            ->with($id)
            ->andReturn($expected);
        $actual = $this->target->deleteProductionDefect($id);

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
