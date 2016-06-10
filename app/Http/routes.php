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

    Route::group(['middleware' => 'auth', 'as' => 'user.'], function() {

        Route::get('/', 'User\OrderController@index');
        Route::post('/order/cancel', 'User\OrderController@cancel');
        Route::post('/order/received', 'User\OrderController@received');
        Route::post('/order/unreceived', 'User\OrderController@notReceived');


        Route::post('/order/add', ['as' => 'order.element.add', 'uses' => 'User\OrderElementController@add']);
        Route::post('/order/change/amount', ['as' => 'order.element.amount.change', 'uses' => 'User\OrderElementController@changeAmount']);
        Route::post('/order/backup/delete', ['as' => 'order.element.delete', 'uses' => 'User\OrderElementController@delete']);


        Route::get('/transaction/history', 'User\TransactionController@history');
        Route::get('/transaction/history/data', 'User\TransactionController@data');


        Route::get('/menu/list', 'User\MenuController@listForOrder');
        Route::get('/menu/previous/preference', 'User\MenuController@getLastPreference');
        Route::get('/courier/list', 'User\TravelController@getActiveCourierByRestaurant');


        Route::post('/notification/dismiss', 'User\MessageController@dismiss');

        Route::group(['prefix' => 'titip', 'as' => 'titip.'], function() {

            Route::get('/start', ['uses' => 'User\TitipController@showStartPage', 'as' => 'start']);
            Route::post('/restaurant/add', ['uses' => 'User\TitipController@addRestaurant', 'as' => 'restaurant.add']);
            Route::get('/open', ['uses' => 'User\TitipController@open', 'as' => 'open']);

            Route::get('/opened', ['uses' => 'User\TitipController@showOpened', 'as' => 'opened']);
            Route::get('/close', ['uses' => 'User\TitipController@close', 'as' => 'close']);

            Route::get('/closed', ['uses' => 'User\TitipController@showClosed', 'as' => 'closed']);
            Route::post('/finish', ['uses' => 'User\TitipController@finish', 'as' => 'finish']);

            Route::get('/finished', ['uses' => 'User\TitipController@showFinished', 'as' => 'finished']);

        });

        Route::get('/policy/service', ['as' => 'policy.service', 'uses' => 'User\UsagePolicyController@showServicePage', 'policyName' => 'service']);
        Route::get('/policy/user', ['as' => 'policy.user', 'uses' => 'User\UsagePolicyController@showUserPage', 'policyName' => 'user']);
        Route::get('/policy/courier', ['as' => 'policy.courier', 'uses' => 'User\UsagePolicyController@showCourierPage', 'policyName' => 'courier']);
        Route::get('/policy/transaction', ['as' => 'policy.transaction', 'uses' => 'User\UsagePolicyController@showTransactionPage', 'policyName' => 'transaction']);
        Route::get('/policy/sanction', ['as' => 'policy.sanction', 'uses' => 'User\UsagePolicyController@showSanctionPage', 'policyName' => 'sanction']);

    });

    Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {

        Route::group(['prefix' => 'order'], function() {

            Route::get('/', 'Admin\OverallOrderController@index');
            Route::get('/data', 'Admin\OverallOrderController@data');
            Route::post('/delete', 'Admin\OverallOrderController@delete');
            Route::post('/ordered/lock', 'Admin\OverallOrderController@closeTravel');

            Route::get('/processed', 'Admin\ProcessedOrderController@index');
            Route::post('/processed/lock', 'Admin\ProcessedOrderController@lock');

            Route::get('/unreceived', 'Admin\NotReceivedOrderController@index');
            Route::post('/unreceived/lock', 'Admin\NotReceivedOrderController@lock');

            Route::get('/summary', 'Admin\ProcessedOrderController@showSummary');

        });

        Route::get('user', ['as' => 'user', 'uses' => 'Admin\UserController@index']);
        Route::get('user/data', 'Admin\UserController@data');

        Route::get('deposit', ['as' => 'deposit', 'uses' => 'Admin\DepositController@showEditDeposit']);
        Route::post('deposit/edit', ['as' => 'deposit.edit', 'uses' => 'Admin\DepositController@editDeposit']);

        Route::get('transfer', ['as' => 'transfer', 'uses' => 'Admin\DepositController@showTransfer']);
        Route::post('transfer/action', ['as' => 'transfer.action', 'uses' => 'Admin\DepositController@transfer']);

        Route::group(['prefix' => 'transaction'], function() {

            Route::get('/', 'Admin\TransactionController@overall');
            Route::get('/data', 'Admin\TransactionController@overallData');

            Route::get('/order', 'Admin\TransactionController@order');
            Route::get('/order/data', 'Admin\TransactionController@orderData');

            Route::post('/order/revert', 'Admin\TransactionController@revert');
        });



        Route::get('message', 'Admin\MessageController@index');
        Route::post('message/broadcast', 'Admin\MessageController@broadcast');

        Route::get('profit', 'Admin\ProfitController@index');
        Route::get('profit/data', 'Admin\ProfitController@getChartData');

    });


});


//Route::group(['prefix' => 'test'], function() {
//
//    Route::get('/invoices', 'Admin\TestController@testInvoice');
//    Route::get('/invoices/raw', 'Admin\TestController@seeBillingEmail');
//});

//Route::group(['prefix' => 'fix'], function() {
//
//    Route::get('/travel/in/transaction', 'Admin\TestController@fixPendingAndActiveTransactionTravelID');
//    Route::get('/deposit/action', 'Admin\TestController@fixTransactionGeneralAction');
//    Route::get('/deposit/code', 'Admin\TestController@fixDepositCode');
//
//});
