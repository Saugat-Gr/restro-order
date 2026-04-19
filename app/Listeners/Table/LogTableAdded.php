<?php

namespace App\Listeners\Table;

use App\Events\Table\TableAdded;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTableAdded
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
    public function handle(TableAdded $event): void
    {
        app(ActivityService::class)->log(
            "table.added",
            $event->table,
            "Table {$event->table->table_number} added.",
            [
                "table_capacity" => $event->table->capacity
            ]
        );
    }
}
