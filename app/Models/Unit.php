<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'shift',
        'status',
    ];

    /**
     * Helper: shift
     */
    public function isShift1(): bool
    {
        return $this->shift === 1;
    }

    public function isShift2(): bool
    {
        return $this->shift === 2;
    }

    /**
     * Helper: status
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'Inactive';
    }
    public function activeService()
    {
        return $this->hasOne(Service::class)
            ->whereIn('status', ['open', 'handover', 'on_process', 'done'])
            ->latest('created_at'); // ambil yang terakhir dibuat
    }

}
