<?php

use App\Http\Controllers\MenuItemCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Models\MenuItem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
})->name('welcome');


Route::middleware('auth')->group(function () {

    //  Login Routes:
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('ensure.user.has.restaurant')->group(function () {

        //  Dashboard Routes:
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard', [
                'app' => [
                    'title' => 'Dashboard',
                ],
            ]);
        })->name('dashboard');


        //  Restaurant Routes:
        Route::resource('restaurant', RestaurantController::class);

        // Menu: 
        Route::prefix('menu')->name('menu.')->group(function () {

            // Menu Categories:
            Route::get('category', [MenuItemCategoryController::class, 'index'])->name('category');
            Route::patch('category/{menu_item_category}', [MenuItemCategoryController::class, 'update'])->name('category.update');
            Route::delete('category/{menu_item_category}', [MenuItemCategoryController::class, 'destroy'])->name('category.destroy');
            Route::post('category', [MenuItemCategoryController::class, 'store'])->name('category.store');

            // Menu Items Routes:
            Route::resource('menu-items', MenuItemController::class);
            

        });


    });

    //    Restaurant Create and Store Routes:
    Route::get('restaurant/create', [RestaurantController::class, 'create'])->name('restaurant.create');
    Route::post('restaurant', [RestaurantController::class, 'store'])->name('restaurant.store');

});




require __DIR__ . '/auth.php';

