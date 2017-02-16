<?php

use App\Service\DataFormatService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DataFormatServiceTest extends TestCase
{
    use DataFormatService;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_formatSchedate()
    {
        /** arrange */
        $date1 = '2016-12-31';
        $date2 = '';

        /** act */
        $expected1 = ['op' => '=', 'date' => date('Y-m-d', strtotime($date1))];
        $expected2 = ['op' => 'like', 'date' => '%%'];
        $actual1 = $this->formatSchedate($date1);
        $actual2 = $this->formatSchedate($date2);

        /** assert */
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }
}
