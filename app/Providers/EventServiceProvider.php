<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Observers\AccountObserver;
use App\Observers\RoleObserver;
use App\Observers\TransactionObserver;
use App\Observers\TransferObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Transfer::observe(TransferObserver::class);
        Account::observe(AccountObserver::class);
        Transaction::observe(TransactionObserver::class);
        Role::observe(RoleObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
