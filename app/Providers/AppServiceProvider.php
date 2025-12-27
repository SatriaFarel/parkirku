<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Password::resetLinkUrl(function ($user, string $token) {
        //     return url("/reset-password/{$token}?email=" . urlencode($user->email));
        // });
    }
}
