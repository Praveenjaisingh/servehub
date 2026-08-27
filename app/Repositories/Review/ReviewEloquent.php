<?php

namespace App\Repositories\Review;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewEloquent implements ReviewContract
{
    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function create(array $data): Review
    {
        return $this->review->create($data);
    }

    public function find(int $id): ?Review
    {
        return $this->review->query()->find($id);
    }

    public function existsForBooking(int $bookingId): bool
    {
        return Review::existsForBooking($bookingId);
    }

    public function forProvider(int $providerId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->review->query()
            ->with('customer')
            ->forProvider($providerId)
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function delete(Review $review): bool
    {
        return (bool) $review->delete();
    }
}
