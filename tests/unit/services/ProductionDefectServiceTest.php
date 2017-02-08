<?php

use App\Repositories\CheckRepository;
use App\Repositories\TemplateRepository;
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

    public function test_getCheckTemplateItem()
    {
        /** arrange */
        $checkID = '';
        $mock = Mockery::mock(TemplateRepository::class);
        $this->app->instance(TemplateRepository::class, $mock);

        $mock_check = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock_check);

        /** act */
        $return1 = new Class {
            public $templateID;
            public function first () {
                return $this;
            }
        };

        $expected = new Class {
          public function get () {

          }
        };

        $mock_check->shouldReceive('getCheck')
            ->once()
            ->with($checkID)
            ->andReturn($return1);
        $template = $return1->first();
        $mock->shouldReceive('getTemplateItem')
            ->once()
            ->with($template->templateID)
            ->andReturn($expected);
        $target = $this->app->make(ProductionDefectService::class);
        $actual = $target->getCheckTemplateItem($checkID);

        /** assert */
        $this->assertEquals($expected->get(), $actual);
    }

    public function test_getCheckTemplateDefect()
    {
        /** arrange */
        $checkID = '';
        $mock = Mockery::mock(TemplateRepository::class);
        $this->app->instance(TemplateRepository::class, $mock);

        $mock_check = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock_check);

        /** act */
        $return1 = new Class {
            public $templateID;
            public function first () {
                return $this;
            }
        };

        $expected = new Class {
            public function get () {

            }
        };

        $mock_check->shouldReceive('getCheck')
            ->once()
            ->with($checkID)
            ->andReturn($return1);
        $template = $return1->first();
        $mock->shouldReceive('getTemplateDefect')
            ->once()
            ->with($template->templateID)
            ->andReturn($expected);
        $target = $this->app->make(ProductionDefectService::class);
        $actual = $target->getCheckTemplateDefect($checkID);

        /** assert */
        $this->assertEquals($expected->get(), $actual);
    }

    public function _test_getProductionDataList()
    {
        /** arrange */


        /** act */


        /** assert */
    }

    public function test_getProductionDefectList()
    {
        /** arrange */
        $checkID = '00000000-0000-0000-0000-000000000000';
        $mock = Mockery::mock(ProductionDefectRepository::class);
        $this->app->instance(ProductionDefectRepository::class, $mock);

        /** act */
        $expected = new class{ public function get () {}};
        $mock->shouldReceive('getProductionDefectList')
            ->once()
            ->with($checkID)
            ->andReturn($expected);
        $target = $this->app->make(ProductionDefectService::class);
        $actual = $target->getProductionDefectList($checkID);

        /** assert */
        $this->assertEquals($expected->get(), $actual);
    }

    public function test_getProductionDataList()
    {
        /** arrange */
        $checkID = '00000000-0000-0000-0000-000000000000';
        $mock = Mockery::mock(ProductionDefectRepository::class);
        $this->app->instance(ProductionDefectRepository::class, $mock);

        /** act */
        $expected = new class{ public function get () {}};
        $mock->shouldReceive('getProductionDataList')
            ->once()
            ->with($checkID)
            ->andReturn($expected);
        $target = $this->app->make(ProductionDefectService::class);
        $actual = $target->getProductionDataList($checkID);

        /** assert */
        $this->assertEquals($expected->get(), $actual);
    }

    public function test_insertProductionDefect()
    {
        /** arrange */
        $mock_check = Mockery::mock(CheckRepository::class);
        $this->app->instance(CheckRepository::class, $mock_check);

        $mock = Mockery::mock(TemplateRepository::class);
        $this->app->instance(TemplateRepository::class, $mock);

        $mock_defect = Mockery::mock(ProductionDefectRepository::class);
        $this->app->instance(ProductionDefectRepository::class, $mock_defect);

        $input['id'] = '32EF7C89-2303-62E7-5878-417D61E9296F';
        $input['checkID'] = '32EF7C89-2303-62E7-1111-417D61E9296F';
        $input['prodDate'] = 'prodDate';
        $input['classType'] = 'classType';
        $input['spotCheck'] = 'spotCheck';
        $input['classRemark'] = 'classRemark';
        $input['minute'] = 'minute';
        $input['speed'] = 'speed';
        $input['checkRate'] = 'checkRate';
        $input['actualQuantity'] = 'actualQuantity';
        $input['actualMinWeight'] = 'actualMinWeight';
        $input['actualMaxWeight'] = 'actualMaxWeight';
        $input['stressLevel'] = 'stressLevel';
        $input['thermalShock'] = 'thermalShock';
        $params1 = $input;
        $params2 = [];
        $return1 = new Class {
            public $templateID;
            public function first () {
                return $this;
            }
        };
        $result = new Class {
            public function get () {
                return [];
            }
        };

        /** act */
        $mock_check->shouldReceive('getCheck')
            ->once()
            ->with($input['checkID'])
            ->andReturn($return1);
        $template = $return1->first();

        $mock->shouldReceive('getTemplateDefect')
            ->once()
            ->with($template->templateID)
            ->andReturn($result);

        $expected = ['success' => true, 'msg' => 'success'];

        $mock_defect->shouldReceive('insertProductionDefect')
            ->once()
            ->with($params1, $params2)
            ->andReturn($expected);
        $target = $this->app->make(ProductionDefectService::class);
        $actual = $target->insertProductionDefect($input);

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
