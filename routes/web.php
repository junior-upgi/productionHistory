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
    return redirect('/order');
});
Route::any('order', 'OrderController@orderSearch');
Route::get('getPic/{no}/{item}/{dep}/{pl}', 'OrderController@getPic');

Route::any('service/formSubmit/{table}', 'OrderController@formSubmit');
Route::any('service/upload/{table}', 'OrderController@productionImage');

Route::any('test', function () {
    return view('order.complete');
});
Route::any('jump', function () {
    return view('welcome');
});
