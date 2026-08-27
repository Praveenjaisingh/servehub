<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Repositories\Notification\NotificationContract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotificationService implements NotificationInterface
{
    protected $notificationContract;

    public function __construct(NotificationContract $notificationContract)
    {
        $this->notificationContract = $notificationContract;
    }

    public function listForUser(User $user, array $filters): LengthAwarePaginator
    {
        return $this->notificationContract->forUser($user->id, $filters, $filters['per_page'] ?? 15);
    }

    public function markAsRead(User $user, int $id)
    {
        $notification = $this->notificationContract->find($id);

        if (! $notification) {
            throw new NotFoundHttpException('Notification not found.');
        }

        if ($notification->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to update this notification.');
        }

        $notification->is_read = true;
        $this->notificationContract->save($notification);

        return $notification;
    }

    public function markAllAsRead(User $user): int
    {
        return $this->notificationContract->markAllAsRead($user->id);
    }

    public function notify(int $userId, string $title, string $message, string $type = 'general')
    {
        return $this->notificationContract->create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'is_read' => false,
        ]);
    }
}
