<?php

namespace App\Providers;

use App\Events\DepositChanged;
use App\GeneralTransaction;
use Event;
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
            'App\Listeners\NotifyDepositChangeToUser',
            'App\Listeners\NotifyDepositIfNegative'
        ],
        'App\Events\TravelProfitChanged' => [
            'App\Listeners\CreateTransactionProfitForCourier'
        ],
        
        'App\Events\Travel\TravelIsClosing' => [
            'App\Listeners\Travel\CloseTravel'
        ],
        'App\Events\Travel\TravelIsFinishing' => [
            'App\Listeners\Travel\FinishTravel'
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

        GeneralTransaction::created(function($transaction) {
            Event::fire(new DepositChanged($transaction));
        });
    }
}
