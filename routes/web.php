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
    Route::get('ScheduleList', 'DutyController@dutySchedule');
    Route::get('DutyList', 'DutyController@dutyList');
    Route::post('GetSchedule', 'DutyController@getSchedule');
    Route::post('GetDuty', 'DutyController@getDuty');
    Route::get('GetStaff', 'DutyController@getStaff');
    Route::post('SaveDuty', 'DutyController@saveDuty');
});

Route::group(['prefix' => 'History'], function () {
//Route::group(['middleware' => 'sso', 'prefix' => 'History'], function () {
    Route::get('GetCustomer', 'HistoryController@getCustomer');
    Route::get('GetGlass', 'HistoryController@getGlass');
    Route::get('ScheduleList', 'HistoryController@historySchedule');
    Route::get('HistoryList', 'HistoryController@historyList');
    Route::post('GetSchedule', 'HistoryController@getSchedule');
    Route::post('GetHistory', 'HistoryController@getHistory');
    Route::get('GetStaff', 'HistoryController@getStaff');
    Route::post('SaveHistory', 'HistoryController@saveHistory');
    Route::delete('DeleteHistory', 'HistoryController@deleteHistory');
});

Route::group(['prefix' => 'QC'], function () {
//Route::group(['middleware' => 'sso', 'prefix' => 'QC'], function () {
    Route::get('GetCustomer', 'QCController@getCustomer');
    Route::get('ScheduleList', 'QCController@qcSchedule');
    Route::get('QCList', 'QCController@qcList');
    Route::post('GetSchedule', 'QCController@getQCSchedule');
    Route::post('GetQC', 'QCController@getQC');
    Route::get('GetStaff', 'QCController@getStaff');
    Route::post('SaveQC', 'QCController@saveQC');
    Route::delete('DeleteQC', 'QCController@deleteQC');
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
    Route::get('getDefect', 'DefectController@getDefect');
    Route::get('searchDefect', 'DefectController@searchDefect');
    Route::get('getDefectList', 'DefectController@getDefectList');
    Route::post('insertDefect', 'DefectController@insertDefect');
    Route::put('updateDefect', 'DefectController@updateDefect');
    Route::delete('deleteDefect', 'DefectController@deleteDefect');

    Route::get('getItem', 'ItemController@getItem');
    Route::get('getItemList', 'ItemController@getItemList');
    Route::get('searchItem', 'ItemController@searchItem');
    Route::get('getDefectGroup', 'ItemController@getDefectGroup');
    Route::post('insertItem', 'ItemController@insertItem');
    Route::put('updateItem', 'ItemController@updateItem');
    Route::delete('deleteItem', 'ItemController@deleteItem');

    Route::get('getTemplate', 'TemplateController@getTemplate');
    Route::get('searchTemplate', 'TemplateController@searchTemplate');
    Route::get('getTemplateList', 'TemplateController@getTemplateList');
    Route::get('getTemplateItem', 'TemplateController@getTemplateItem');
    Route::post('insertTemplate', 'TemplateController@insertTemplate');
    Route::put('updateTemplate', 'TemplateController@updateTemplate');
    Route::delete('deleteTemplate', 'TemplateController@deleteTemplate');

    Route::get('getCheck', 'CheckController@getCheck');
    Route::get('getCheckList', 'CheckController@getCheckList');
    Route::get('searchCheck', 'CheckController@searchCheck');
    Route::get('scheduleList', 'CheckController@getScheduleList');
    Route::get('scheduleCustomer', 'CheckController@getScheduleCustomer');
    Route::post('insertCheck', 'CheckController@insertCheck');
    Route::put('updateCheck', 'CheckController@updateCheck');
    Route::delete('deleteCheck', 'CheckController@deleteCheck');

    Route::get('getCheckTemplate', 'CheckController@getCheckTemplate');
    Route::get('getProductionDefect', 'CheckController@getProductionDefect');
    Route::get('getProductionDefectList', 'CheckController@getProductionDefectList');
    Route::post('insertProductionDefect', 'CheckController@insertProductionDefect');
    Route::put('updateProductionDefect', 'CheckController@updateProductionDefect');
    Route::delete('deleteProductionDefect', 'CheckController@deleteProductionDefect');
});