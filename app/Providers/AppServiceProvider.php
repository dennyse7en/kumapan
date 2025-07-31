<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CreditApplication;
use App\Observers\CreditApplicationObserver;
use Illuminate\Auth\Events\Registered;

use App\Http\Responses\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    // protected $observers = [
    //     CreditApplication::class => [CreditApplicationObserver::class],
    // ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
