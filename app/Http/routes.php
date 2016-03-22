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


        Route::get('/admin/order', 'Admin\OverallOrderController@index');
        Route::get('/admin/order/data', 'Admin\OverallOrderController@data');
        Route::post('/admin/order/delete', 'Admin\OverallOrderController@delete');
        Route::post('/admin/order/ordered/lock', 'Admin\OverallOrderController@lock');

        Route::get('/admin/order/processed', 'Admin\ProcessedOrderController@index');
        Route::post('/admin/order/processed/lock', 'Admin\ProcessedOrderController@lock');

        Route::get('/admin/order/unreceived', 'Admin\NotReceivedOrderController@index');
        Route::post('/admin/order/unreceived/lock', 'Admin\NotReceivedOrUsderController@lock');

        Route::get('/admin/order/summary', 'Admin\ProcessedOrderController@showSummary');

        Route::get('/admin/user', 'Admin\UserController@index');
        Route::get('/admin/user/data', 'Admin\UserController@data');

        Route::get('/admin/deposit', 'Admin\UserController@showEditDeposit');
        Route::post('/admin/deposit/edit', 'Admin\UserController@editDeposit');


        Route::get('/admin/transaction', 'Admin\TransactionController@overall');
        Route::get('/admin/transaction/data', 'Admin\TransactionController@overallData');





        Route::get('/transaction/history', 'TransactionController@history');
        Route::get('/transaction/history/data', 'TransactionController@data');


        Route::get('/restaurant', 'RestaurantController@showList');


        Route::get('/menu/list', 'MenuController@listForOrder');
        Route::get('/courier/list', 'TravelController@getActiveCourierByRestaurant');
    });


});
