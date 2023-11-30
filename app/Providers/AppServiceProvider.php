<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TaskService;
use App\Services\TaskServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
