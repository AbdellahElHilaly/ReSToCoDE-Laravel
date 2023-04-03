<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\PermissionRepository;
use App\Http\Interfaces\Repository\PermissionRepositoryInterface;

class PermissionRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }


    public function boot(): void
    {
    }
}
