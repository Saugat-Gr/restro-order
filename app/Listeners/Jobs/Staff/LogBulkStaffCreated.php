<?php

namespace App\Listeners\Jobs\Staff;

use App\Models\Restaurant;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class LogBulkStaffCreated
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
        $staffs = $event->staffsIds;

        if (empty($staffIds))
            return;

        $firstUser = User::find($staffIds[0]);

        if (!$firstUser)
            return;


        app(ActivityService::class)->log(
            'bulk-staff.created',
            $firstUser,
            "Staffs Added",
            [
                'action' => "Bulk Staffs Created"
            ]
        );

    }
}
