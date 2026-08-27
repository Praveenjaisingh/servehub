<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationContract
{
    public function forUser(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Notification;

    public function markAllAsRead(int $userId): int;

    public function create(array $data): Notification;

    public function save(Notification $notification): bool;
}
