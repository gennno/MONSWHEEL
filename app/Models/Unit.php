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
        'img',
        'status',
    ];

    /**
     * Helper: status
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isService(): bool
    {
        return $this->status === 'service';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Relationship: active service
     */
    public function activeService()
    {
        return $this->hasOne(Service::class)
            ->whereIn('status', ['open', 'handover', 'on_process', 'done'])
            ->latest('created_at');
    }
}
