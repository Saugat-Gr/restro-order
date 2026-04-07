<?php

namespace App\Providers;

use App\Repositories\MenuItem\MenuItemInterface;
use App\Repositories\MenuItem\MenuItemRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Restaurant\RestaurantInterface;
use App\Repositories\Restaurant\RestaurantRepository;

use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            RestaurantInterface::class,
            RestaurantRepository::class
        );

        $this->app->bind(MenuItemInterface::class, MenuItemRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ]
        ]);
    }
}
