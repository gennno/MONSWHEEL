<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        // Relation
        'unit_id',

        // Header
        'service_date',
        'gl',
        'kapten',
        'bays',
        'backlog_item',

        // Time log (PLAN vs ACTUAL)
        'in_plan',
        'in_actual',

        'qa1_plan',
        'qa1_actual',

        'washing_plan',
        'washing_actual',
        'washing_remark',

        'action_service_plan',
        'action_service_actual',

        'action_backlog_plan',
        'action_backlog_actual',

        'qa7_plan',
        'qa7_actual',

        // Downtime
        'downtime_plan',
        'downtime_actual',
        'downtime_countdown',

        // Notes
        'note_in',
        'note_qa1',
        'note_washing',
        'note_action_service',
        'note_action_backlog',
        'note_qa7',
        'note_downtime',

        // Status
        'remark',
        'status',
        'handover_at',
        'completed_at',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'service_date' => 'date',

        // DATETIME (FULL)
        'in_plan' => 'datetime',
        'in_actual' => 'datetime',

        'qa1_plan' => 'datetime',
        'qa1_actual' => 'datetime',

        'washing_plan' => 'datetime',
        'washing_actual' => 'datetime',

        'action_service_plan' => 'datetime',
        'action_service_actual' => 'datetime',

        'action_backlog_plan' => 'datetime',
        'action_backlog_actual' => 'datetime',

        'qa7_plan' => 'datetime',
        'qa7_actual' => 'datetime',

        'handover_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |-------------------------------------------------------------------------- 
    */

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |-------------------------------------------------------------------------- 
    */

    public function scopeToday($query)
    {
        return $query->whereDate('service_date', now()->toDateString());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['process', 'continue']);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |-------------------------------------------------------------------------- 
    */

    public function isPlanned(): bool
    {
        return $this->status === 'plan';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'process';
    }

    public function isContinue(): bool
    {
        return $this->status === 'continue';
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['process', 'continue']);
    }

    public function isOver(): bool
    {
        return $this->remark === 'over';
    }

    public function isOk(): bool
    {
        return $this->remark === 'ok';
    }

    public function isWashingOver(): bool
    {
        return $this->washing_remark === 'over';
    }

    public function isWashingOk(): bool
    {
        return $this->washing_remark === 'ok';
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNTIME HELPERS
    |-------------------------------------------------------------------------- 
    */

    public function getDowntimePlanFormattedAttribute()
    {
        return $this->formatMinutes($this->downtime_plan);
    }

    public function getDowntimeActualFormattedAttribute()
    {
        return $this->formatMinutes($this->downtime_actual);
    }

    public function getDowntimeCountdownFormattedAttribute()
    {
        return $this->formatMinutes($this->downtime_countdown);
    }

    private function formatMinutes($minutes)
    {
        if (!$minutes) return '-';

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return "{$hours}h {$mins}m";
    }

    /*
    |--------------------------------------------------------------------------
    | DURATION HELPERS (🔥 NEW - VERY USEFUL)
    |-------------------------------------------------------------------------- 
    */

    public function getWashingDurationAttribute()
    {
        return $this->diffInMinutes($this->washing_plan, $this->washing_actual);
    }

    public function getInDurationAttribute()
    {
        return $this->diffInMinutes($this->in_plan, $this->in_actual);
    }

    private function diffInMinutes($start, $end)
    {
        if (!$start || !$end) return null;

        return $start->diffInMinutes($end);
    }
}