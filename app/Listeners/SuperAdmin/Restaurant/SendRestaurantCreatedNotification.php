<?php

namespace App\Listeners\SuperAdmin\Restaurant;

use App\Models\User;
use App\Notifications\SuperAdmin\RestaurantCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRestaurantCreatedNotification
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
         $restaurant = $event->restaurant->load('user');
        $users = User::role('super-admin')->get();

        foreach($users as $user){
               $user->notify(new RestaurantCreatedNotification($restaurant));
        }
    }
}
