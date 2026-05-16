<?php

namespace App\Http\Controllers\Monitor;

use App\Http\Controllers\Controller;

use App\Models\Monitor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MonitorController extends Controller
{
    public function index(): JsonResponse
    {
        $monitors = Monitor::all();
        return response()->json([
            'data' => $monitors->map(fn($m) => $this->formatMonitor($m))
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'url' => 'required|url|unique:monitors,url',
            'check_interval' => 'sometimes|integer|min:1|max:60',
            'threshold' => 'sometimes|integer|min:1',
        ]);

        $validated['check_interval'] = $validated['check_interval'] ?? 5;
        $validated['threshold'] = $validated['threshold'] ?? 3;

        $monitor = Monitor::create($validated);

        return response()->json(['data' => $this->formatMonitor($monitor)], 201);
    }


    public function history(Request $request, $id): JsonResponse
    {
        $monitor = Monitor::find($id);
        if (!$monitor) {
            return response()->json(['message' => 'Monitor not found.'], 404);
        }

        $perPage = min($request->get('per_page', 15), 100);
        $checks = $monitor->checks()
            ->orderBy('checked_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $checks->map(fn($c) => [
                'id' => $c->id,
                'monitor_id' => $c->monitor_id,
                'status_code' => $c->status_code,
                'response_time_ms' => $c->response_time_ms,
                'is_up' => $c->is_up,
                'checked_at' => $c->checked_at,
            ]),
            'page_metadata' => [
                'current_page' => $checks->currentPage(),
                'per_page' => $checks->perPage(),
                'total' => $checks->total(),
            ]
        ]);
    }

    private function formatMonitor(Monitor $monitor): array
    {
        return [
            'id' => $monitor->id,
            'url' => $monitor->url,
            'check_interval' => $monitor->check_interval,
            'threshold' => $monitor->threshold,
            'status' => $monitor->status,
            'last_checked_at' => $monitor->last_checked_at,
            'uptime_percentage' => $monitor->uptime_percentage,
            'created_at' => $monitor->created_at,
        ];
    }
}