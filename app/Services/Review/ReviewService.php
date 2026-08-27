<?php

namespace App\Services\Review;

use App\Enums\BookingStatusEnum;
use App\Repositories\Booking\BookingContract;
use App\Repositories\ProviderProfile\ProviderProfileContract;
use App\Repositories\Review\ReviewContract;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewService implements ReviewInterface
{
    protected $reviewContract;
    protected $bookingContract;
    protected $providerProfileContract;

    public function __construct(
        ReviewContract $reviewContract,
        BookingContract $bookingContract,
        ProviderProfileContract $providerProfileContract
    ) {
        $this->reviewContract = $reviewContract;
        $this->bookingContract = $bookingContract;
        $this->providerProfileContract = $providerProfileContract;
    }

    public function create(User $customer, array $data)
    {
        $booking = $this->bookingContract->find($data['booking_id']);

        if (! $booking) {
            throw new NotFoundHttpException('Booking not found.');
        }

        if ($booking->customer_id !== $customer->id) {
            throw new AuthorizationException('You can only review your own bookings.');
        }

        if ($booking->status !== BookingStatusEnum::COMPLETED) {
            throw ValidationException::withMessages([
                'booking_id' => ['Only completed bookings can be reviewed.'],
            ]);
        }

        if ($this->reviewContract->existsForBooking($booking->id)) {
            throw ValidationException::withMessages([
                'booking_id' => ['You have already reviewed this booking.'],
            ]);
        }

        $review = $this->reviewContract->create([
            'booking_id'  => $booking->id,
            'customer_id' => $customer->id,
            'provider_id' => $booking->provider_id,
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
        ]);

        $profile = $this->providerProfileContract->find($booking->provider_id);
        $profile?->refreshRatingAggregates();

        return $review;
    }

    public function forProvider(int $providerId, array $filters): LengthAwarePaginator
    {
        return $this->reviewContract->forProvider($providerId, $filters, $filters['per_page'] ?? 15);
    }

    public function delete(User $user, int $id): bool
    {
        $review = $this->reviewContract->find($id);

        if (! $review) {
            throw new NotFoundHttpException('Review not found.');
        }

        if (! $user->isAdmin() && $review->customer_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to delete this review.');
        }

        $deleted = $this->reviewContract->delete($review);

        $profile = $this->providerProfileContract->find($review->provider_id);
        $profile?->refreshRatingAggregates();

        return $deleted;
    }
}
