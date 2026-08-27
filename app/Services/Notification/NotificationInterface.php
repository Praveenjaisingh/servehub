<?php

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationInterface
{
    public function listForUser(User $user, array $filters): LengthAwarePaginator;

    public function markAsRead(User $user, int $id);

    public function markAllAsRead(User $user): int;

    public function notify(int $userId, string $title, string $message, string $type = 'general');
}
