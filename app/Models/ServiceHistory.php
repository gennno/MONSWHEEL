<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    use HasFactory;

    /**
     * Table name (opsional, tapi eksplisit)
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
        'created_by',
        'handover_to',

        // Date & shift
        'service_date',
        'shift',

        // Person in charge
        'kapten',
        'gl',
        'qa1',

        // Notes & actions
        'note1',
        'washing',
        'note2',
        'action_service',
        'note3',

        // Backlog
        'bays',
        'action_backlog',
        'note4',

        // RFU & downtime
        'rfu',
        'downtime_plan',
        'downtime_actual',

        // Final note
        'note5',

        // Flow timestamps
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
        'service_date'    => 'date',
        'downtime_plan'   => 'datetime',
        'downtime_actual' => 'datetime',
        'handover_at'     => 'datetime',
        'completed_at'    => 'datetime',
        'archived_at'     => 'datetime',
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
     * User shift 1 (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User shift 2 (handover)
     */
    public function handoverUser()
    {
        return $this->belongsTo(User::class, 'handover_to');
    }

    /**
     * Reference to original service (optional trace)
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
     * Filter by date
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

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isArchived(): bool
    {
        return ! is_null($this->archived_at);
    }
}
