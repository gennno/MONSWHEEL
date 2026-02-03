<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    use HasFactory;

    /**
     * Table name (explicit)
     */
    protected $table = 'service_histories';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        // Trace
        'service_id',

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

        // Downtime
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

        // Final snapshot
        'remark',
        'status',
        'handover_at',
        'completed_at',

        // Archive metadata
        'archived_at',
        'archived_by',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'service_date'  => 'date',
        'handover_at'   => 'datetime',
        'completed_at'  => 'datetime',
        'archived_at'   => 'datetime',
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

    /**
     * Reference to original service (trace)
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Filter by service date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('service_date', $date);
    }

    /**
     * Filter by unit
     */
    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /**
     * Only archived records
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isArchived(): bool
    {
        return ! is_null($this->archived_at);
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
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
