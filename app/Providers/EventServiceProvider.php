<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'App\Events\OrderDelivered' => [
            'App\Listeners\CreatePendingTransaction'
        ],
        'App\Events\OrderReceived' => [
            'App\Listeners\CreateRealTransaction',
            'App\Listeners\DebitUserBalance',
            'App\Listeners\DeletePendingTransaction'
        ],
        'App\Events\OrderNotReceived' => [
            'App\Listeners\DeletePendingTransaction'
        ],
        'App\Events\OrderLocked' => [
            'App\Listeners\NotifyTransactionToUser'
        ],
        'App\Events\ProfitChanged' => [
            'App\Listeners\UpdateProfit'
        ],
        'App\Events\DepositChanged' => [
            'App\Listeners\NotifyDepositChangeToUser'
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
