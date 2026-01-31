<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
protected $listen = [
    Registered::class => [
        SendEmailVerificationNotification::class,
    ],
    // TAMBAHKAN INI:
    Login::class => [
        \App\Listeners\LogSuccessfulLogin::class,
    ],
];
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
