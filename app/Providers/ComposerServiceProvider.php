<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Log;
use Route;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer("layouts.policy", function($view) {

            $route = Route::current();
            $routeNameFractions = explode(".", $route->getName());
            $policyType = last($routeNameFractions);
            
            $view->with("activeness", $policyType);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
