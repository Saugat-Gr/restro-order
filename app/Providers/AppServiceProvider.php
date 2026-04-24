<?php

namespace App\Providers;

use App\Http\Controllers\SuperAdmin\FacadeRestaurantController;
use App\Repositories\Dashboard\DashBoardInterface;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\MenuItem\MenuItemInterface;
use App\Repositories\MenuItem\MenuItemRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\Order\OrderRepository;
use App\Services\SuperAdmin\RestaurantService;
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

        $this->app->bind(OrderInterface::class, OrderRepository::class);

        $this->app->singleton(RestaurantService::class);
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
            ],
            'flash' => function () {
                return [
                    'success' => fn() => request()->session()->get('success'),
                    'error' => fn() => request()->session()->get('error'),
                ];
            },
        ]);
    }
}
