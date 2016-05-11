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
