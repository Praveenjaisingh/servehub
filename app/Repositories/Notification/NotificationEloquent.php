<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationEloquent implements NotificationContract
{
    protected $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function forUser(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->notification->query()
            ->forUser($userId)
            ->when(! empty($filters['unread_only']), fn ($q) => $q->unread())
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function find(int $id): ?Notification
    {
        return $this->notification->query()->find($id);
    }

    public function markAllAsRead(int $userId): int
    {
        return $this->notification->query()->forUser($userId)->unread()->update(['is_read' => true]);
    }

    public function create(array $data): Notification
    {
        return $this->notification->create($data);
    }

    public function save(Notification $notification): bool
    {
        return $notification->save();
    }
}
