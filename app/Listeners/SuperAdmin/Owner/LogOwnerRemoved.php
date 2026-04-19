<?php

namespace App\Listeners\SuperAdmin\Owner;

use App\Models\ActivityLog;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOwnerRemoved
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

        app(ActivityService::class)->log(
            "owner.removed",
            $owner,
            "Owner: {$owner->name} removed",
            [
                "owner_name" => $owner->name,
                "owner_email" => $owner->email,
            ]
        );
    }
}
