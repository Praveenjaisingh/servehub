<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationInterface;
use Illuminate\Http\Request;
use Throwable;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationInterface $notificationService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $notifications = $this->notificationService->listForUser(
                $request->user(),
                $request->only(['unread_only', 'per_page'])
            );

            return api_success($notifications, 'Notifications fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function markAsRead(Request $request, int $id)
    {
        try {
            $notification = $this->notificationService->markAsRead($request->user(), $id);

            return api_success($notification, 'Notification marked as read.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $count = $this->notificationService->markAllAsRead($request->user());

            return api_success(['updated' => $count], 'All notifications marked as read.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
