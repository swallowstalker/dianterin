<?php

namespace App\Providers;

use App\Order;
use App\TransactionOrder;
use App\Validator\OrderValidator;
use Illuminate\Support\ServiceProvider;
use Log;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

//        Validator::extend("check_balance", function($attribute, $value, $parameters, $validator) {
//            Log::debug("check_balance was here");
//            return true;
//        });

//        Validator::extend("order", 'App\Validator\OrderValidator@validateOrder');

        Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes) {

            return new OrderValidator($translator, $data, $rules, $messages, $customAttributes);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
