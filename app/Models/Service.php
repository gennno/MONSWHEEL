<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Service extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
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

        // Flow
        'status',
        'handover_at',
        'completed_at',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'service_date'   => 'date',
        'downtime_plan'  => 'datetime',
        'downtime_actual'=> 'datetime',
        'handover_at'    => 'datetime',
        'completed_at'   => 'datetime',
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
        return $query->whereIn('status', [
            'open',
            'handover',
            'on_process',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC (FLOW HANDLER)
    |--------------------------------------------------------------------------
    */

    /**
     * Start service (Shift 1)
     */
    public function start()
    {
        $this->update([
            'status' => 'open',
            'shift'  => 1,
        ]);

        $this->unit->startService($this);
    }

    /**
     * Handover to shift 2
     */
    public function handoverTo(User $user)
    {
        $this->update([
            'handover_to' => $user->id,
            'status'      => 'handover',
            'shift'       => 2,
            'handover_at' => Carbon::now(),
        ]);

        $this->unit->handoverToShift2($this);
    }

    /**
     * Start shift 2 work
     */
    public function startShift2()
    {
        $this->update([
            'status' => 'on_process',
            'shift'  => 2,
        ]);

        $this->unit->update([
            'service_status' => 'on_service',
            'current_shift'  => 2,
        ]);
    }

    /**
     * Finish service (end job)
     */
    public function complete()
    {
        $this->update([
            'status'       => 'done',
            'completed_at' => Carbon::now(),
        ]);

        $this->unit->finishService();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    public function isHandover(): bool
    {
        return $this->status === 'handover';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['open', 'handover', 'on_process']);
    }
}
