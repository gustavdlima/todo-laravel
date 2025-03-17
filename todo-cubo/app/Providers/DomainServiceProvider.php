<?php

namespace App\Providers;

use App\Domain\TaskManagement\Repositories\TaskRepositoryInterface;
use App\Infrastructure\Repositories\EloquentTaskRepository;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
    }
}
