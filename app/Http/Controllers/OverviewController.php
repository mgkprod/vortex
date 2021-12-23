<?php

namespace App\Http\Controllers;

use App\Models\Heartbeat;
use App\Models\Monitor;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    public function __invoke()
    {
        $monitorIds = auth()->user()->monitors()->select('id')->pluck('id');

        $monitorStatuses = collect(
            DB::select('
            select a.monitor_id, a.status
            from heartbeats a
            inner join (
                select monitor_id, max(created_at) as created_at
                from heartbeats
                group by monitor_id
            ) b on a.monitor_id = b.monitor_id and a.created_at = b.created_at')
        )
            ->groupBy('status')
            ->transform(fn ($group) => $group->pluck('monitor_id'));

        $downMonitors = Monitor::query()
            ->whereIn('id', $monitorStatuses[Heartbeat::STATUS_DOWN] ?? [])
            ->get();

        $notifications = Notification::query()
            ->where('notifiable_type', Monitor::class)
            ->whereIn('notifiable_id', $monitorIds)
            ->orderBy('created_at', 'DESC')
            ->take(15)
            ->get();

        return inertia('overview', [
            'monitorStatuses' => $monitorStatuses,
            'downMonitors' => $downMonitors,
            'notifications' => $notifications,
        ]);
    }
}
