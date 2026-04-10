<?php

namespace App\Providers;

use App\Models\LegalCase;
use App\Observers\LegalCaseObserver;
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
        LegalCase::observe(LegalCaseObserver::class);
    }
}
