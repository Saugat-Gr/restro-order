<?php

namespace App\Listeners\Menu\Category;

use App\Events\Menu\Category\CategoryAdded;
use App\Events\Menu\Category\CategoryRemoved;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCategoryRemoved
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CategoryRemoved $event): void
    {
        $category = $event->category;

        app(ActivityService::class)->log(
             'menu-category.added',
             $category,
             "Category {$category->name} added",
             [
                 'category_name' => $category->name 
             ]
        );
        

    }
}
