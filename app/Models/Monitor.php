<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitor extends Model
{
    protected $fillable = [
        'url',
        'check_interval',
        'threshold',
        'status',
        'last_checked_at',
        'uptime_percentage',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'uptime_percentage' => 'float',
    ];

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class);
    }

    public function calculateUptime(): float|null
    {
        $total = $this->checks()->count();
        if ($total === 0)
            return null;
        $up = $this->checks()->where('is_up', true)->count();
        return round(($up / $total) * 100, 2);
    }
}