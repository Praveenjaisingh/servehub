<?php

namespace App\Repositories\Review;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewContract
{
    public function create(array $data): Review;

    public function find(int $id): ?Review;

    public function existsForBooking(int $bookingId): bool;

    public function forProvider(int $providerId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function delete(Review $review): bool;
}
