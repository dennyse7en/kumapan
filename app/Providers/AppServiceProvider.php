<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CreditApplication;
use App\Observers\CreditApplicationObserver;
use Illuminate\Auth\Events\Registered;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    protected $observers = [
        CreditApplication::class => [CreditApplicationObserver::class],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
