<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function servicesCreated()
    {
        return $this->hasMany(Service::class, 'created_by');
    }

    /**
     * Service handed over to user (shift 2)
     */
    public function servicesHandover()
    {
        return $this->hasMany(Service::class, 'handover_to');
    }

    /*
    |--------------------------------------------------------------------------
    | SERVICE HISTORY RELATIONS (ARCHIVE)
    |--------------------------------------------------------------------------
    */

    /**
     * Archived service created by user
     */
    public function serviceHistoriesCreated()
    {
        return $this->hasMany(ServiceHistory::class, 'created_by');
    }

    /**
     * Archived service handed over to user
     */
    public function serviceHistoriesHandover()
    {
        return $this->hasMany(ServiceHistory::class, 'handover_to');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS (OPTIONAL BUT USEFUL)
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOffice(): bool
    {
        return $this->role === 'office';
    }

    public function isSite(): bool
    {
        return $this->role === 'site';
    }
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
