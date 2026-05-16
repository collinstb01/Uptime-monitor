<?php

namespace App\Jobs;

use App\Mail\SiteDownMail;
use App\Mail\SiteUpMail;
use App\Models\Monitor;
use App\Models\MonitorCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CheckMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Monitor $monitor)
    {
    }

    public function handle(): void
    {
        $statusCode = 0;
        $responseTime = null;
        $isUp = false;

        try {
            $start = microtime(true);
            $response = Http::timeout(10)->get($this->monitor->url);
            $responseTime = (int) round((microtime(true) - $start) * 1000);
            $statusCode = $response->status();
            $isUp = $statusCode >= 200 && $statusCode < 400;
        } catch (\Exception $e) {
            $statusCode = 0;
            $responseTime = null;
            $isUp = false;
        }

        MonitorCheck::create([
            'monitor_id' => $this->monitor->id,
            'status_code' => $statusCode,
            'response_time_ms' => $responseTime,
            'is_up' => $isUp,
            'checked_at' => now(),
        ]);

        $previousStatus = $this->monitor->status;

        $this->monitor->update([
            'last_checked_at' => now(),
            'uptime_percentage' => $this->monitor->calculateUptime(),
        ]);

        $this->handleThreshold($isUp, $previousStatus);
    }

    private function handleThreshold(bool $isUp, string $previousStatus): void
    {
        if (!$isUp) {
            $recentChecks = $this->monitor->checks()
                ->latest('checked_at')
                ->take($this->monitor->threshold)
                ->get();

            $allDown = $recentChecks->count() === $this->monitor->threshold
                && $recentChecks->every(fn($c) => !$c->is_up);

            if ($allDown && $previousStatus !== 'down') {
                $this->monitor->update(['status' => 'down']);
                Mail::to('admin@example.com')->send(new SiteDownMail($this->monitor));
            }
        } else {
            if ($previousStatus === 'down') {
                $this->monitor->update(['status' => 'up']);
                Mail::to('admin@example.com')->send(new SiteUpMail($this->monitor));
            } elseif ($previousStatus === 'pending') {
                $this->monitor->update(['status' => 'up']);
            }
        }
    }
}