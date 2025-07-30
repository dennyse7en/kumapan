<?php

namespace App\Providers;

// Import class yang dibutuhkan
use App\Models\CreditApplication;
use App\Observers\CreditApplicationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Event listener mappings untuk aplikasi Anda.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Model observers untuk aplikasi Anda.
     * Di sinilah kita mendaftarkan observer kita.
     *
     * @var array
     */
    protected $observers = [
        CreditApplication::class => [CreditApplicationObserver::class],
    ];

    /**
     * Register event lain untuk aplikasi Anda.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Tentukan apakah event dan listener harus ditemukan secara otomatis.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}