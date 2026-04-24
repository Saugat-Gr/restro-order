<?php

namespace App\Providers;

use App\Events\Jobs\BulkStaffCreated;
use App\Events\Menu\Category\CategoryAdded;
use App\Events\Menu\Category\CategoryRemoved;
use App\Events\Menu\Item\InStock;
use App\Events\Menu\Item\ItemAdded;
use App\Events\Menu\Item\ItemRemoved;
use App\Events\Menu\Item\OutOfStock;
use App\Events\Order\OrderCancelled;
use App\Events\Order\OrderCompleted;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderReady;
use App\Events\SuperAdmin\Owner\OwnerCreated;
use App\Events\SuperAdmin\Owner\OwnerRemoved;
use App\Events\SuperAdmin\Restaurant\RestaurantCreated;
use App\Events\SuperAdmin\Restaurant\RestaurantRemoved;
use App\Events\Table\TableAdded;
use App\Listeners\Jobs\Staff\LogBulkStaffCreated;
use App\Listeners\Jobs\Staff\SendBulkStaffCreatedNotification;
use App\Listeners\Menu\Category\LogCategoryAdded;
use App\Listeners\Menu\Category\LogCategoryRemoved;
use App\Listeners\Menu\Category\SendCategoryAddedNotification;
use App\Listeners\Menu\Category\SendCategoryRemovedNotification;
use App\Listeners\Menu\Item\LogInStock;
use App\Listeners\Menu\Item\LogItemAdded;
use App\Listeners\Menu\Item\LogItemRemoved;
use App\Listeners\Menu\Item\LogOutOfStock;
use App\Listeners\Menu\Item\SendInStockNotification;
use App\Listeners\Menu\Item\SendItemAddedNotification;
use App\Listeners\Menu\Item\SendItemRemovedNotification;
use App\Listeners\Menu\Item\SendOutOfStockNotification;
use App\Listeners\Order\LogOrderActivity;
use App\Listeners\Order\LogOrderCancelledActivity;
use App\Listeners\Order\LogOrderCreated;
use App\Listeners\Order\LogOrderReady;
use App\Listeners\Order\SendOrderCancelledNotification;
use App\Listeners\Order\SendOrderCompletedNotification;
use App\Listeners\Order\SendOrderCreatedNotification;
use App\Listeners\Order\SendOrderReadyNotification;
use App\Listeners\SuperAdmin\Owner\LogOwnerCreated;
use App\Listeners\SuperAdmin\Owner\LogOwnerRemoved;
use App\Listeners\SuperAdmin\Owner\SendOwnerCreatedNotification;
use App\Listeners\SuperAdmin\Owner\SendOwnerRemovedNotification;
use App\Listeners\SuperAdmin\Restaurant\LogRestaurantCreated;
use App\Listeners\SuperAdmin\Restaurant\LogRestaurantRemoved;
use App\Listeners\SuperAdmin\Restaurant\SendRestaurantCreatedNotification;
use App\Listeners\SuperAdmin\Restaurant\SendRestaurantRemovedNotification;
use App\Listeners\Table\LogTableAdded;
use App\Listeners\Table\SendTableAddedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],


            //  Menu Items:

        ItemAdded::class => [
            LogItemAdded::class,
            SendItemAddedNotification::class,
        ],

        ItemRemoved::class => [
            LogItemRemoved::class,
            SendItemRemovedNotification::class,
        ],

        OutOfStock::class => [
            LogOutOfStock::class,
            SendOutOfStockNotification::class,
        ],

        InStock::class => [
            LogInStock::class,
            SendInStockNotification::class,
        ],

            //  Orders:
        OrderCreated::class => [
            LogOrderCreated::class,
            SendOrderCreatedNotification::class,
        ],

        OrderReady::class => [
            LogOrderReady::class,
            SendOrderReadyNotification::class,
        ],

        OrderCompleted::class => [
            LogOrderActivity::class,
            SendOrderCompletedNotification::class,
        ],

        OrderCancelled::class => [
            LogOrderCancelledActivity::class,
            SendOrderCancelledNotification::class,
        ],


            //  Tables
        TableAdded::class => [
            LogTableAdded::class,
            SendTableAddedNotification::class,
        ],

            // Menu Categories:
        CategoryAdded::class => [
            LogCategoryAdded::class,
            SendCategoryAddedNotification::class,
        ],
        CategoryRemoved::class => [
            LogCategoryRemoved::class,
            SendCategoryRemovedNotification::class,
        ]
        ,
            //  SuperAdmin Owner:
        OwnerCreated::class => [
            LogOwnerCreated::class,
            SendOwnerCreatedNotification::class
        ],

        OwnerRemoved::class => [
            LogOwnerRemoved::class,
            SendOwnerRemovedNotification::class,
        ],

        RestaurantCreated::class => [
            LogRestaurantCreated::class,
            SendRestaurantCreatedNotification::class,
        ],

        RestaurantRemoved::class => [
            LogRestaurantRemoved::class,
            SendRestaurantRemovedNotification::class,
        ],

        // BulkStaff:
        BulkStaffCreated::class => [
             LogBulkStaffCreated::class,
             SendBulkStaffCreatedNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
