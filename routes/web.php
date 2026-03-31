<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
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
    });

    //    Restaurant Create and Store Routes:
    Route::get('restaurant/create', [RestaurantController::class, 'create'])->name('restaurant.create');
    Route::post('restaurant', [RestaurantController::class, 'store'])->name('restaurant.store');
});


require __DIR__ . '/auth.php';
