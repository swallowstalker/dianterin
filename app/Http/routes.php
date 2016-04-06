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

Route::get('/order/sweep', 'Admin\CronController@lockDeliveredOrder');


Route::group(['middleware' => 'web'], function () {

    Route::auth();

    Route::get('/login/google', 'Auth\AuthController@redirectToGoogle');
    Route::get('/login/google/callback', 'Auth\AuthController@handleCallbackGoogle');

    Route::group(['middleware' => 'auth'], function() {


        Route::get('/', 'User\OrderController@index');
        Route::post('/order/add', 'User\OrderController@add');
        Route::post('/order/change/amount', 'User\OrderController@changeAmount');
        Route::post('/order/cancel', 'User\OrderController@cancel');

        Route::post('/order/received', 'User\OrderController@received');
        Route::post('/order/unreceived', 'User\OrderController@notReceived');

        Route::get('/transaction/history', 'User\TransactionController@history');
        Route::get('/transaction/history/data', 'User\TransactionController@data');

        Route::get('/restaurant', 'User\RestaurantController@showList');

        Route::get('/menu/list', 'User\MenuController@listForOrder');
        Route::get('/courier/list', 'User\TravelController@getActiveCourierByRestaurant');

        Route::post('/notification/dismiss', 'User\MessageController@dismiss');


        Route::group(['middleware' => 'admin'], function() {

            Route::get('/admin/order', 'Admin\OverallOrderController@index');
            Route::get('/admin/order/data', 'Admin\OverallOrderController@data');
            Route::post('/admin/order/delete', 'Admin\OverallOrderController@delete');
            Route::post('/admin/order/ordered/lock', 'Admin\OverallOrderController@lock');

            Route::get('/admin/order/processed', 'Admin\ProcessedOrderController@index');
            Route::post('/admin/order/processed/lock', 'Admin\ProcessedOrderController@lock');

            Route::get('/admin/order/unreceived', 'Admin\NotReceivedOrderController@index');
            Route::post('/admin/order/unreceived/lock', 'Admin\NotReceivedOrderController@lock');

            Route::get('/admin/order/summary', 'Admin\ProcessedOrderController@showSummary');

            Route::get('/admin/user', 'Admin\UserController@index');
            Route::get('/admin/user/data', 'Admin\UserController@data');

            Route::get('/admin/deposit', 'Admin\UserController@showEditDeposit');
            Route::post('/admin/deposit/edit', 'Admin\UserController@editDeposit');


            Route::get('/admin/transaction', 'Admin\TransactionController@overall');
            Route::get('/admin/transaction/data', 'Admin\TransactionController@overallData');

            Route::get('/admin/transaction/order', 'Admin\TransactionController@order');
            Route::get('/admin/transaction/order/data', 'Admin\TransactionController@orderData');

            Route::post('/admin/transaction/order/revert', 'Admin\TransactionController@revert');

            Route::get('/admin/message', 'Admin\MessageController@index');
            Route::post('/admin/message/broadcast', 'Admin\MessageController@broadcast');

        });

    });


});
