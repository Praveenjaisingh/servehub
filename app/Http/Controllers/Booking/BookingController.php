<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingStatusRequest;
use App\Services\Booking\BookingInterface;
use Illuminate\Http\Request;
use Throwable;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingInterface $bookingService
    ) {
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->create($request->user(), $request->validated());

            return api_success($booking, 'Booking created successfully.', 201);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(Request $request, int $id)
    {
        try {
            $booking = $this->bookingService->find($request->user(), $id);

            return api_success($booking, 'Booking fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function myBookings(Request $request)
    {
        try {
            $bookings = $this->bookingService->listForCustomer(
                $request->user(),
                $request->only(['status', 'from', 'to', 'per_page'])
            );

            return api_success($bookings, 'Bookings fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function providerBookings(Request $request)
    {
        try {
            $bookings = $this->bookingService->listForProvider(
                $request->user(),
                $request->only(['status', 'from', 'to', 'per_page'])
            );

            return api_success($bookings, 'Bookings fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function updateStatus(UpdateBookingStatusRequest $request, int $id)
    {
        try {
            $validated = $request->validated();

            $booking = $this->bookingService->updateStatus(
                $request->user(),
                $id,
                $validated['status'],
                $validated['reason'] ?? null
            );

            return api_success($booking, 'Booking status updated successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function cancel(Request $request, int $id)
    {
        try {
            $data = $request->validate([
                'reason' => ['nullable', 'string', 'max:500'],
            ]);

            $booking = $this->bookingService->cancel($request->user(), $id, $data['reason'] ?? null);

            return api_success($booking, 'Booking cancelled successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
