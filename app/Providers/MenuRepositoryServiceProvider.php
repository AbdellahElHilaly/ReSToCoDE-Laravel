<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\Repository\MenuRepositoryInterface;
use App\Http\Repositories\MenuRepository;

class MenuRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
    }

    public function boot(): void
    {
    }
}

