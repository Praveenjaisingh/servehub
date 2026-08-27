<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'customer_id',
        'provider_id',
        'service_id',
        'booking_date',
        'booking_time',
        'address',
        'notes',
        'status',
        'total_amount',
        'cancelled_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_amount' => 'float',
    ];

    /* ---------------------------------------------------------------
     | Relationships
     |---------------------------------------------------------------*/

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /* ---------------------------------------------------------------
     | Query Scopes
     |---------------------------------------------------------------*/

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeForProvider($query, int $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeBetweenDates($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('booking_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('booking_date', '<=', $to);
        }

        return $query;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', BookingStatusEnum::COMPLETED);
    }
}
