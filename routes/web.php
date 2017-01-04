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

Route::get('nav/{view}', function ($view) {
    return view($view);
});

Route::group(['prefix' => 'Duty'], function() {
//Route::group(['middleware' => 'sso', 'prefix' => 'Duty'], function() {
    Route::get('ScheduleList', 'ProductionController@dutySchedule');
    Route::get('DutyList', 'ProductionController@dutyList');
    Route::post('GetSchedule', 'ProductionController@getSchedule');
    Route::post('GetDuty', 'ProductionController@getDuty');
    Route::get('GetStaff', 'ProductionController@getStaff');
    Route::post('SaveDuty', 'ProductionController@saveDuty');
});

Route::group(['prefix' => 'History'], function () {
//Route::group(['middleware' => 'sso', 'prefix' => 'History'], function () {
    Route::get('GetCustomer', 'ProductionController@getCustomer');
    Route::get('GetGlass', 'ProductionController@getGlass');
    Route::get('ScheduleList', 'ProductionController@historySchedule');
    Route::get('HistoryList', 'ProductionController@historyList');
    Route::post('GetSchedule', 'ProductionController@getSchedule');
    Route::post('GetHistory', 'ProductionController@getHistory');
    Route::get('GetStaff', 'ProductionController@getStaff');
    Route::post('SaveHistory', 'ProductionController@saveHistory');
    Route::delete('DeleteHistory', 'ProductionController@deleteData');
});

Route::group(['prefix' => 'QC'], function () {
//Route::group(['middleware' => 'sso', 'prefix' => 'QC'], function () {
    Route::get('GetCustomer', 'ProductionController@getCustomer');
    Route::get('ScheduleList', 'ProductionController@qcSchedule');
    Route::get('QCList', 'ProductionController@qcList');
    Route::post('GetSchedule', 'ProductionController@getQCSchedule');
    Route::post('GetQC', 'ProductionController@getQC');
    Route::get('GetStaff', 'ProductionController@getStaff');
    Route::post('SaveQC', 'ProductionController@saveQC');
    Route::delete('DeleteQC', 'ProductionController@deleteData');
});

Route::group(['prefix' => 'Report'], function () {
//Route::group(['middleware' => 'sso', 'prefix' => 'Report'], function () {
    Route::get('ProductionMeeting', 'ReportController@productionMeeting');
    Route::post('GetHistory', 'ReportController@getHistory');
    Route::get('QCForm/{id}', 'ReportController@qcForm');
    Route::get('HistoryForm/{id}', 'ReportController@historyForm');
});

Route::group(['prefix' => 'Service'], function () {
//Route::group(['middleware' => 'sso', 'prefix' => 'Service'], function () {
    Route::get('GetPic/{id}', 'ServiceController@getPic');
    Route::get('BlankPic/{id}', 'ServiceController@blankPic');
    Route::post('DeleteTask', 'ServiceController@deleteTask');
    Route::post('SaveTask', 'ServiceController@saveTask');
});

Route::group(['prefix' => 'defect'], function() {
    Route::get('getItem', 'DefectCheckController@getItem');
    Route::post('saveItem', 'DefectCheckController@saveItem');
    Route::delete('deleteItem', 'DefectCheckController@deleteItem');

    Route::get('getTemplate', 'DefectCheckController@getTemplate');
    Route::get('getTemplateItem', 'DefectCheckController@getTemplateItem');
    Route::post('saveTemplate', 'DefectCheckController@saveTemplate');
    Route::delete('deleteTemplate', 'DefectCheckController@deleteTemplate');
});