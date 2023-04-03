<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\Repository\GameRepositoryInterface;
use App\Http\Repositories\GameRepository;

class GameRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);
    }

    public function boot(): void
    {
    }
}
