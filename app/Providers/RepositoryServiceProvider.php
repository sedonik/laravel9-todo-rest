<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TaskInterface;
use App\Repositories\TaskRepository;

/**
 * class RepositoryServiceProvider
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(TaskInterface::class, TaskRepository::class);
    }
}
