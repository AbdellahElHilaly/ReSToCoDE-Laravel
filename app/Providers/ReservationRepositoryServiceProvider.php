<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\Repository\ReservationRepositoryInterface;
use App\Http\Repositories\ReservationRepository;

class ReservationRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
    }

    public function boot(): void
    {
    }
}

