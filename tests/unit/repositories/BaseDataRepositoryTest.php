<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Repositories\BaseDataRepository;
use App\Models\UPGWeb\Staff;
use App\Models\UPGWeb\Customer;
use App\Models\UPGWeb\Glass;

/**
 * Class BaseDataRepositoryTest
 */
class BaseDataRepositoryTest extends TestCase
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
        //$this->mock = $this->initMock();
        $this->target = $this->app->make(BaseDataRepository::class);
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
     *
     */
    public function test_getStaff()
    {
        /** arrange */
        $table = new Staff();
        $expected = $table->where('serving', 1)->count();

        /** act */
        $actual = $this->target->getStaff()->count();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function test_getCustomer()
    {
        /** arrange */
        $table = new Customer();
        $expected = $table->orderBy('name');

        /** act */
        $actual = $this->target->getCustomer();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function test_getGlass()
    {
        /** arrange */
        $table = new Glass();
        $expected = $table->orderBy('snm');

        /** act */
        $actual = $this->target->getGlass();

        /** assert */
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function test_getPrdNo()
    {
        /** arrange */
        $table = new Glass();
        $snm = $table->first()->snm;
        $expected = $table->first()->prd_no;

        /** act */
        $actual = $this->target->getPrdNo($snm);

        /** assert */
        $this->assertEquals($expected, $actual);
    }
}
