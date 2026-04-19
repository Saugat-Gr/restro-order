<?php

namespace App\Listeners\Table;

use App\Models\User;
use App\Notifications\SuperAdmin\RestaurantRemovedNotification;
use App\Notifications\Table\TableAddedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTableAddedNotification
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
         $table = $event->table;
        $users = User::where('restaurant_id', $table->restaurant_id)->get();

        foreach($users as $user){
               $user->notify(new TableAddedNotification($table));
        }
    }
}
