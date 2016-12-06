<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('nav/{view}', function($view) {
    return view($view);
});



Route::group(['prefix' => 'Duty'], function() {
    Route::get('ScheduleList', 'ProductionController@scheduleList');
    Route::get('DutyList', 'ProductionController@dutyList');
    Route::post('GetSchedule', 'ProductionController@getSchedule');
    Route::post('GetDuty', 'ProductionController@getDuty');
    Route::get('GetStaff', 'ProductionController@getStaff');
    Route::post('SaveDuty', 'ProductionController@saveDuty');
});