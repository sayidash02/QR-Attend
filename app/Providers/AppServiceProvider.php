<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Paksa HTTPS jika diakses di luar localhost (misalnya lewat domain tunnel .lhr.life atau pinggy)
        if (!in_array(request()->getHost(), ['127.0.0.1', 'localhost'])) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
