<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'bio',
        'experience_years',
        'skills',
        'profile_image',
        'city',
        'address',
        'latitude',
        'longitude',
        'is_verified',
        'average_rating',
        'total_reviews',
    ];

    protected $casts = [
        'skills'           => 'array',
        'is_verified'      => 'boolean',
        'average_rating'   => 'float',
        'latitude'         => 'float',
        'longitude'        => 'float',
        'experience_years' => 'integer',
        'total_reviews'    => 'integer',
    ];

    /* ---------------------------------------------------------------
     | Relationships
     |---------------------------------------------------------------*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'provider_id');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'provider_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    /* ---------------------------------------------------------------
     | Query Scopes
     |---------------------------------------------------------------*/

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeInCity($query, ?string $city)
    {
        return $city ? $query->where('city', 'ilike', "%{$city}%") : $query;
    }

    public function scopeTopRated($query)
    {
        return $query->orderByDesc('average_rating');
    }

    /* ---------------------------------------------------------------
     | Thin data helpers
     |---------------------------------------------------------------*/

    public static function findByUserId(int $userId): ?self
    {
        return static::where('user_id', $userId)->first();
    }

    public function refreshRatingAggregates(): void
    {
        $this->average_rating = (float) $this->reviews()->avg('rating');
        $this->total_reviews  = $this->reviews()->count();
        $this->save();
    }
}
