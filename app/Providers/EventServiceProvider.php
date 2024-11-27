<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Models\Settings;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Events\LeadStatusChanged;
use App\Observers\SettingObserver;
use App\Listeners\CreateSaleOnLeadStatusChanged;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LeadStatusChanged::class => [
            CreateSaleOnLeadStatusChanged::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Don't run observer when
        // we run command using
        if (!app()->runningInConsole()) {
            User::observe(UserObserver::class);
            Settings::observe(SettingObserver::class);
            Role::observe(RoleObserver::class);
        }
    }
}
