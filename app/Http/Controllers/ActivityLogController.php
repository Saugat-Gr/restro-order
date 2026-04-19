<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $logs = ActivityLog::query()
            ->with(['user', 'restaurant'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Restaurant/ActivityLogs/Index', [
            'logs' => $logs,
        ]);
    }
}
