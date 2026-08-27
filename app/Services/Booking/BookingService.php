<?php

namespace App\Services\Booking;

use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use App\Models\User;
use App\Repositories\Booking\BookingContract;
use App\Repositories\ProviderProfile\ProviderProfileContract;
use App\Repositories\Service\ServiceContract;
use App\Services\Notification\NotificationInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingService implements BookingInterface
{
    protected $bookingContract;
    protected $serviceContract;
    protected $providerProfileContract;
    protected $notificationInterface;

    public function __construct(
        BookingContract $bookingContract,
        ServiceContract $serviceContract,
        ProviderProfileContract $providerProfileContract,
        NotificationInterface $notificationInterface
    ) {
        $this->bookingContract = $bookingContract;
        $this->serviceContract = $serviceContract;
        $this->providerProfileContract = $providerProfileContract;
        $this->notificationInterface = $notificationInterface;
    }

    public function create(User $customer, array $data)
    {
        $service = $this->serviceContract->findWithProvider($data['service_id']);

        if (! $service || $service->status !== 'active') {
            throw new NotFoundHttpException('Selected service is not available.');
        }

        $booking = $this->bookingContract->create([
            'reference'    => booking_reference(),
            'customer_id'  => $customer->id,
            'provider_id'  => $service->provider_id,
            'service_id'   => $service->id,
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time'],
            'address'      => $data['address'],
            'notes'        => $data['notes'] ?? null,
            'status'       => BookingStatusEnum::PENDING,
            'total_amount' => $service->price,
        ]);

        $this->notificationInterface->notify(
            $service->provider->user_id,
            'New booking request',
            "You have a new booking request for \"{$service->title}\".",
            'booking'
        );

        return $booking->load(['service', 'provider.user']);
    }

    public function find(User $user, int $id)
    {
        $booking = $this->bookingContract->findWithDetails($id);

        if (! $booking) {
            throw new NotFoundHttpException('Booking not found.');
        }

        $this->authorizeAccess($user, $booking);

        return $booking;
    }

    public function listForCustomer(User $customer, array $filters): LengthAwarePaginator
    {
        return $this->bookingContract->forCustomer($customer->id, $filters, $filters['per_page'] ?? 15);
    }

    public function listForProvider(User $provider, array $filters): LengthAwarePaginator
    {
        $profile = $this->providerProfileContract->findByUserId($provider->id);

        if (! $profile) {
            throw new NotFoundHttpException('Provider profile not found.');
        }

        return $this->bookingContract->forProvider($profile->id, $filters, $filters['per_page'] ?? 15);
    }

    public function updateStatus(User $user, int $id, string $status, ?string $reason = null)
    {
        $booking = $this->bookingContract->findWithProvider($id);

        if (! $booking) {
            throw new NotFoundHttpException('Booking not found.');
        }

        $this->authorizeProviderAccess($user, $booking);

        if (! in_array($status, BookingStatusEnum::allowedTransitions($booking->status), true)) {
            throw ValidationException::withMessages([
                'status' => ["Cannot change booking status from {$booking->status} to {$status}."],
            ]);
        }

        $booking->status = $status;

        if ($status === BookingStatusEnum::CANCELLED) {
            $booking->cancelled_reason = $reason;
            $booking->cancelled_by = 'provider';
        }

        $this->bookingContract->save($booking);

        $this->notificationInterface->notify(
            $booking->customer_id,
            'Booking status updated',
            "Your booking #{$booking->reference} is now {$status}.",
            'booking'
        );

        return $booking;
    }

    public function cancel(User $user, int $id, ?string $reason = null)
    {
        $booking = $this->bookingContract->findWithProvider($id);

        if (! $booking) {
            throw new NotFoundHttpException('Booking not found.');
        }

        $this->authorizeAccess($user, $booking);

        if (! in_array(BookingStatusEnum::CANCELLED, BookingStatusEnum::allowedTransitions($booking->status), true)) {
            throw ValidationException::withMessages([
                'status' => ['This booking can no longer be cancelled.'],
            ]);
        }

        $booking->status = BookingStatusEnum::CANCELLED;
        $booking->cancelled_reason = $reason;
        $booking->cancelled_by = $user->isCustomer() ? 'customer' : 'provider';
        $this->bookingContract->save($booking);

        return $booking;
    }

    private function authorizeAccess(User $user, Booking $booking): void
    {
        if ($user->isAdmin()) {
            return;
        }

        $isCustomer = $user->isCustomer() && $booking->customer_id === $user->id;
        $isProvider = $user->isProvider() && $booking->provider->user_id === $user->id;

        if (! $isCustomer && ! $isProvider) {
            throw new AuthorizationException('You are not authorized to view this booking.');
        }
    }

    private function authorizeProviderAccess(User $user, Booking $booking): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if (! $user->isProvider() || $booking->provider->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to update this booking.');
        }
    }
}
