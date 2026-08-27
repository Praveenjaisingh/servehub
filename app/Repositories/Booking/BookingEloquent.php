<?php

namespace App\Repositories\Booking;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookingEloquent implements BookingContract
{
    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function create(array $data): Booking
    {
        return $this->booking->create($data);
    }

    public function find(int $id): ?Booking
    {
        return $this->booking->query()->find($id);
    }

    public function findWithDetails(int $id): ?Booking
    {
        return $this->booking->query()
            ->with(['service', 'provider.user', 'customer', 'review'])
            ->find($id);
    }

    public function findWithProvider(int $id): ?Booking
    {
        return $this->booking->query()->with('provider')->find($id);
    }

    public function forCustomer(int $customerId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->booking->query()
            ->with(['service', 'provider.user'])
            ->forCustomer($customerId)
            ->status($filters['status'] ?? null)
            ->betweenDates($filters['from'] ?? null, $filters['to'] ?? null)
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function forProvider(int $providerId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->booking->query()
            ->with(['service', 'customer'])
            ->forProvider($providerId)
            ->status($filters['status'] ?? null)
            ->betweenDates($filters['from'] ?? null, $filters['to'] ?? null)
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function save(Booking $booking): bool
    {
        return $booking->save();
    }
}
