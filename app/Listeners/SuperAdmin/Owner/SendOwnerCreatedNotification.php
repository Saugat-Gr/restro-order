<?php

namespace App\Listeners\SuperAdmin\Owner;

use App\Models\User;
use App\Notifications\SuperAdmin\OwnerCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOwnerCreatedNotification
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
        $owner = $event->owner;
        $users = User::role('super-admin')->get();

        foreach($users as $user){
               $user->notify(new OwnerCreatedNotification($owner));
        }
    }
}
