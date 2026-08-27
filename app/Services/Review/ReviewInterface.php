<?php

namespace App\Services\Review;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewInterface
{
    public function create(User $customer, array $data);

    public function forProvider(int $providerId, array $filters): LengthAwarePaginator;

    public function delete(User $user, int $id): bool;
}
