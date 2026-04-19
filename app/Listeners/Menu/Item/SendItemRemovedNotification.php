<?php

namespace App\Listeners\Menu\Item;

use App\Models\User;
use App\Notifications\Menu\Item\ItemRemovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendItemRemovedNotification
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
        $item = $event->item;

        $users = User::where('restaurant_id', $item->restaurant_id)->get();

        foreach($users as $user){
             $user->notify(new ItemRemovedNotification($item));
        }

    }
}
