<?php

namespace App\Providers;

use App\MessageOwnedByUser;
use App\Models\Constants\MessageType;
use App\TransactionOrder;
use Auth;
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

        view()->composer("layouts.main.notifications", function ($view) {

            $messages = MessageOwnedByUser::owner()->newest()
                ->type(MessageType::NotificationBar)->get();

            $view->with("notificationBarMessages", $messages);
        });
        
        view()->composer("layouts.main.info_popup", function ($view) {

            $message = MessageOwnedByUser::owner()->newest()
                ->type(MessageType::Popup)->first();

            $view->with("popupMessage", $message);
        });

        view()->composer("layouts.main.navigation_top", function ($view) {

            $orderTransactionTotal = TransactionOrder::byOwner(Auth::user()->id)->count();
            $view->with("orderTransactionTotal", $orderTransactionTotal);
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
