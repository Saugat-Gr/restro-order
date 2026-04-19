<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityService
{
    public function log(
        string $eventType,
        $subject,
        ?string $description = null,
        array $metadata = []
    ): ActivityLog {

        return ActivityLog::create([
            'restaurant_id' => $subject->restaurant_id ?? null,
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}