<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\Repository\MealRepositoryInterface;
use App\Http\Repositories\MealRepository;

class MealRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MealRepositoryInterface::class, MealRepository::class);
    }

    public function boot(): void
    {
    }
}

