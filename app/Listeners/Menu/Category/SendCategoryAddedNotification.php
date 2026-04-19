<?php

namespace App\Listeners\Menu\Category;

use App\Models\User;
use App\Notifications\Menu\Category\CategoryAddedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCategoryAddedNotification
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
    public function handle(object $event): void
    {
        $category = $event->category;

        $users = User::where('restaurant_id', $category->restaurant_id)->get();

        foreach ($users as $user) {
            $user->notify(new CategoryAddedNotification($category));
        }
    }
}
