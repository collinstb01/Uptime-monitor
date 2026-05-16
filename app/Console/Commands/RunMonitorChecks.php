<?php

namespace App\Console\Commands;

use App\Jobs\CheckMonitor;
use App\Models\Monitor;
use Illuminate\Console\Command;

class RunMonitorChecks extends Command
{
    protected $signature = 'monitors:check';
    protected $description = 'Run checks for all due monitors';

    public function handle(): void
    {
        $monitors = Monitor::all();

        foreach ($monitors as $monitor) {
            $lastChecked = $monitor->last_checked_at;
            $intervalMinutes = $monitor->check_interval;

            if (!$lastChecked || now()->diffInMinutes($lastChecked) >= $intervalMinutes) {
                CheckMonitor::dispatch($monitor);
            }
        }

        $this->info('Monitor checks dispatched.');
    }
}