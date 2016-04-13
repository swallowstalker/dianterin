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
        Route::get('/menu/previous/preference', 'User\MenuController@getLastPreference');
        Route::get('/courier/list', 'User\TravelController@getActiveCourierByRestaurant');

        Route::post('/notification/dismiss', 'User\MessageController@dismiss');


        Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {

            Route::group(['prefix' => 'order'], function() {

                Route::get('/', 'Admin\OverallOrderController@index');
                Route::get('data', 'Admin\OverallOrderController@data');
                Route::post('delete', 'Admin\OverallOrderController@delete');
                Route::post('ordered/lock', 'Admin\OverallOrderController@lock');

                Route::get('processed', 'Admin\ProcessedOrderController@index');
                Route::post('processed/lock', 'Admin\ProcessedOrderController@lock');

                Route::get('unreceived', 'Admin\NotReceivedOrderController@index');
                Route::post('unreceived/lock', 'Admin\NotReceivedOrderController@lock');

                Route::get('summary', 'Admin\ProcessedOrderController@showSummary');

            });

            Route::get('user', 'Admin\UserController@index');
            Route::get('user/data', 'Admin\UserController@data');

            Route::get('deposit', 'Admin\UserController@showEditDeposit');
            Route::post('deposit/edit', 'Admin\UserController@editDeposit');

            Route::group(['prefix' => 'transaction'], function() {

                Route::get('/', 'Admin\TransactionController@overall');
                Route::get('data', 'Admin\TransactionController@overallData');

                Route::get('order', 'Admin\TransactionController@order');
                Route::get('order/data', 'Admin\TransactionController@orderData');

                Route::post('order/revert', 'Admin\TransactionController@revert');
            });



            Route::get('message', 'Admin\MessageController@index');
            Route::post('message/broadcast', 'Admin\MessageController@broadcast');

        });

    });


});
