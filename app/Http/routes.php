<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {

    Route::auth();

    Route::group(['middleware' => 'auth'], function() {

        Route::get('/login/google', 'Auth\AuthController@redirectToGoogle');
        Route::get('/login/google/callback', 'Auth\AuthController@handleCallbackGoogle');


        Route::get('/', 'OrderController@index');
        Route::post('/order/add', 'OrderController@add');
        Route::post('/order/change/amount', 'OrderController@changeAmount');
        Route::post('/order/cancel', 'OrderController@cancel');

        Route::post('/order/received', 'OrderController@received');
        Route::post('/order/unreceived', 'OrderController@notReceived');

        Route::get('/admin/order/list', 'AdminOrderController@index');
        Route::get('/admin/order/data', 'AdminOrderController@data');
        Route::post('/admin/order/delete', 'AdminOrderController@delete');
        Route::post('/admin/order/lock', 'AdminOrderController@lock');



        Route::get('/transaction/history', 'TransactionController@history');
        Route::get('/transaction/history/data', 'TransactionController@data');


        Route::get('/restaurant', 'RestaurantController@showList');


        Route::get('/menu/list', 'MenuController@listForOrder');
        Route::get('/courier/list', 'TravelController@getActiveCourierByRestaurant');
    });


});
