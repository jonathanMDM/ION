<?php

namespace App\Providers;

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
        \App\Models\Asset::observe(\App\Observers\AssetObserver::class);
        
        // Force HTTPS in production and when using ngrok
        if ($this->app->environment('production') || 
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            str_contains(request()->server('HTTP_HOST', ''), 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
