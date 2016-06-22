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
            $color = "white";

            if (100 <= $orderTransactionTotal && $orderTransactionTotal < 200) {
                $color = "lightgrey";
            } else if ($orderTransactionTotal >= 200) {
                $color = "#FFC335";
            }

            $view->with("orderTransactionTotal", $orderTransactionTotal);
            $view->with("color", $color);
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
