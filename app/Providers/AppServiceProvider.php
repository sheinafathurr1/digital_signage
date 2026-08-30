<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
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
        // Relative dates in the admin UI ("2 menit yang lalu") are rendered in
        // Indonesian. Only Carbon's locale is switched — the app locale stays
        // English so framework validation messages keep resolving.
        Carbon::setLocale('id');
    }
}
