<?php

namespace App\Listeners\Menu\Item;

use App\Models\User;
use App\Notifications\Menu\Item\ItemAddedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendItemAddedNotification
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
             $user->notify(new ItemAddedNotification($item));
        }

    }
}
