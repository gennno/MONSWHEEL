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

        // Time log (plan vs actual)
        'in_plan',
        'in_actual',
        'qa1_plan',
        'qa1_actual',
        'washing_plan',
        'washing_actual',
        'action_service_plan',
        'action_service_actual',
        'action_backlog_plan',
        'action_backlog_actual',
        'qa7_plan',
        'qa7_actual',

        // Downtime (duration)
        'downtime_plan',
        'downtime_actual',

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
        'service_date'   => 'date',
        'handover_at'    => 'datetime',
        'completed_at'   => 'datetime',
        'in_plan' => 'datetime:H:i:s',
        'in_actual' => 'datetime:H:i:s',

        'qa1_plan' => 'datetime:H:i:s',
        'qa1_actual' => 'datetime:H:i:s',

        'washing_plan' => 'datetime:H:i:s',
        'washing_actual' => 'datetime:H:i:s',

        'action_service_plan' => 'datetime:H:i:s',
        'action_service_actual' => 'datetime:H:i:s',

        'action_backlog_plan' => 'datetime:H:i:s',
        'action_backlog_actual' => 'datetime:H:i:s',

        'qa7_plan' => 'datetime:H:i:s',
        'qa7_actual' => 'datetime:H:i:s',
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

    /**
     * Service today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('service_date', now()->toDateString());
    }

    /**
     * Active services (monitoring)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['process', 'continue']);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
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
}
