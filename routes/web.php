<?php

use App\Enums\UserRole;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SuperAdmin\ActivityLogsController;
use App\Http\Controllers\SuperAdmin\AnalyticsController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\FacadeRestaurantController;
use App\Http\Controllers\SuperAdmin\RestaurantController as SuperAdminRestaurantController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuItemCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SuperAdmin\OwnerController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\SimpleExcel\SimpleExcelReader;

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


// Super-Admin: 

Route::middleware(['auth', 'role:super-admin'])->prefix('super-admin')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index']);
    Route::resource('/owners', OwnerController::class);
    Route::resource('/restaurants', FacadeRestaurantController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('superadmin.analytics');

    Route::get('/activity-logs', ActivityLogsController::class);

});


Route::middleware(['auth'])->group(function () {
    //  Login Routes:
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    //   Notification Seen: 
    Route::post('/notifications/{id}/read', function ($id, Request $request) {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    });

    Route::post('/notification/read-all', function () {
        $user = auth()->user();

        $user->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.all');
});


//  Owner and Staffs
Route::middleware(['auth', 'role:owner|staff', 'ensure.restaurant.is.active'])->group(function () {


    //  Activity Log: 
    Route::get('/activity-logs', ActivityLogController::class);

    // Analytics Log:

    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');

    //  Dashboard Routes:
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::middleware('ensure.user.has.restaurant')->group(function () {




        //  Restaurant Routes:
        Route::resource('restaurant', RestaurantController::class)->only(['index', 'edit', 'update']);

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

        // Tables Routes:
        Route::resource('tables', TableController::class)->only(['index', 'create', 'edit', 'update', 'destroy', 'store']);

        // Order Routes:
        Route::get('/orders/search', [OrderController::class, 'searchOrder'])->name('orders.search');
        Route::resource('orders', OrderController::class);

        // Tranasctions: 
        Route::resource('transactions', TransactionController::class)->only('index');

        // Staffs:
        Route::resource('staffs', StaffController::class);

    });

    //    Restaurant Create and Store Routes:

});
// Route::post('restaurant', [RestaurantController::class, 'store'])->name('restaurant.store');
// Route::get('restaurant/create', [RestaurantController::class, 'create'])->name('restaurant.create');



Route::fallback(function () {
    return redirect()->route('welcome')->with('error', 'Page Not Found');
});

Route::post('/test-import', [StaffController::class, 'import'])->name('staffs.import');

require __DIR__ . '/auth.php';

