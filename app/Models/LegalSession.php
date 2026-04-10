<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalSession extends Model
{
    protected $table = 'legal_sessions';

    protected $fillable = [
        'case_id',
        'scheduled_at',
        'location',
        'judge',
        'notes',
        'status',
        'next_session_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'next_session_at' => 'datetime',
        ];
    }

    /**
     * Get the legal case associated with this session.
     */
    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    /**
     * Scope to get upcoming sessions (next 7 days).
     */
    public function scopeUpcoming($query)
    {
        return $query
            ->whereDate('scheduled_at', '>=', now())
            ->whereDate('scheduled_at', '<=', now()->addDays(7))
            ->where('status', '!=', 'cancelled')
            ->orderBy('scheduled_at', 'asc');
    }

    /**
     * Scope to get scheduled sessions only.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')->orderBy('scheduled_at', 'asc');
    }

    /**
     * Scope to get completed sessions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')->orderBy('scheduled_at', 'desc');
    }

    /**
     * Check if session is upcoming (hasn't happened yet).
     */
    public function isUpcoming(): bool
    {
        return $this->scheduled_at > now();
    }

    /**
     * Check if session is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->scheduled_at < now() && $this->status === 'scheduled';
    }

    /**
     * Get formatted date for display.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->scheduled_at->format('Y-m-d H:i');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'warning',
            'completed' => 'success',
            'postponed' => 'info',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get status label in Arabic.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'مجدول',
            'completed' => 'مكتمل',
            'postponed' => 'مؤجل',
            'cancelled' => 'ملغى',
            default => 'غير معروف',
        };
    }
}
