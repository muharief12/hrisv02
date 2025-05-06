<?php

namespace App\Providers;

use App\Models\DailyTask;
use App\Observers\DailyTaskObserver;
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
        DailyTask::observe(DailyTaskObserver::class);
    }
}
