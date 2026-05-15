<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorCheck extends Model
{
    protected $fillable = [
        'monitor_id',
        'status_code',
        'response_time_ms',
        'is_up',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'is_up' => 'boolean',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}