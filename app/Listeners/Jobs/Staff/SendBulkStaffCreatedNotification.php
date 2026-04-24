<?php

namespace App\Listeners\Jobs\Staff;

use App\Events\Jobs\BulkStaffCreated;
use App\Models\User;
use App\Notifications\Jobs\Staff\BulkStaffCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class SendBulkStaffCreatedNotification implements ShouldQueue
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
        $staffIds = $event->staffsIds;

        if (empty($staffIds))
            return;

        $firstUser = User::find($staffIds[0]);

        if (!$firstUser)
            return;

        $restaurantId = $firstUser->restaurant_id;

        $users = User::where('restaurant_id', $restaurantId)->get();

        Log::info($users);

       foreach($users as $user){
          $user->notify(new BulkStaffCreatedNotification($user));
       }
    }

}
