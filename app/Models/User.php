<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'avatar',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /* ---------------------------------------------------------------
     | Relationships
     |---------------------------------------------------------------*/

    public function providerProfile(): HasOne
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function bookingsAsCustomer(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function reviewsWritten(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /* ---------------------------------------------------------------
     | Query Scopes (DB access only - business rules stay in Service)
     |---------------------------------------------------------------*/

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', RoleEnum::CUSTOMER);
    }

    public function scopeProviders($query)
    {
        return $query->where('role', RoleEnum::PROVIDER);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'ilike', "%{$term}%")
              ->orWhere('email', 'ilike', "%{$term}%");
        });
    }

    /* ---------------------------------------------------------------
     | Direct data-access helpers (thin, no business logic)
     |---------------------------------------------------------------*/

    public static function findByEmail(string $email): ?self
    {
        return static::where('email', $email)->first();
    }

    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::ADMIN;
    }

    public function isProvider(): bool
    {
        return $this->role === RoleEnum::PROVIDER;
    }

    public function isCustomer(): bool
    {
        return $this->role === RoleEnum::CUSTOMER;
    }
}
