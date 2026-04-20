<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $logs = ActivityLog::with('user')->where('restaurant_id', null)->paginate(10);


        return Inertia::render('SuperAdmin/ActivityLogs/Index', [
            "app" => [
                "title" => 'Activity Logs'
            ],
            'logs' => $logs
        ]);
    }
}
