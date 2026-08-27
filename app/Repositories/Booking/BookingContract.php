<?php

namespace App\Repositories\Booking;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookingContract
{
    public function create(array $data): Booking;

    public function find(int $id): ?Booking;

    public function findWithDetails(int $id): ?Booking;

    public function findWithProvider(int $id): ?Booking;

    public function forCustomer(int $customerId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function forProvider(int $providerId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function save(Booking $booking): bool;
}
