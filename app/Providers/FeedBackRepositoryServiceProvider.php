<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\Repository\FeedBackRepositoryInterface;
use App\Http\Repositories\FeedBackRepository;

class FeedBackRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FeedBackRepositoryInterface::class, FeedBackRepository::class);
    }

    public function boot(): void
    {
    }
}

