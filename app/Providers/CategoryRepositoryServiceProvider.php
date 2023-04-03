<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\Repository\CateroyRepositoryInterface;
use App\Http\Repositories\CategoryRepository;

class CategoryRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CateroyRepositoryInterface::class, CategoryRepository::class);
    }


    public function boot(): void
    {
    }
}
