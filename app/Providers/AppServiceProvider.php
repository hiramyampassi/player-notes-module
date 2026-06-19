<?php

namespace App\Providers;

use App\Repositories\PlayerNoteRepository;
use App\Repositories\PlayerNoteRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PlayerNote Repository
        $this->app->bind(PlayerNoteRepositoryInterface::class, PlayerNoteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
