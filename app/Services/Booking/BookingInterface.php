<?php

namespace App\Services\Booking;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookingInterface
{
    public function create(User $customer, array $data);

    public function find(User $user, int $id);

    public function listForCustomer(User $customer, array $filters): LengthAwarePaginator;

    public function listForProvider(User $provider, array $filters): LengthAwarePaginator;

    public function updateStatus(User $user, int $id, string $status, ?string $reason = null);

    public function cancel(User $user, int $id, ?string $reason = null);
}
