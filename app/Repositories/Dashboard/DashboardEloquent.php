<?php

namespace App\Repositories\Dashboard;

use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use App\Models\ProviderProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardEloquent implements DashboardContract
{
    public function totalCustomers(): int
    {
        return User::customers()->count();
    }

    public function totalProviders(): int
    {
        return User::providers()->count();
    }

    public function totalServices(): int
    {
        return Service::count();
    }

    public function totalBookings(): int
    {
        return Booking::count();
    }

    public function pendingBookings(): int
    {
        return Booking::status(BookingStatusEnum::PENDING)->count();
    }

    public function completedBookingsCount(): int
    {
        return Booking::completed()->count();
    }

    public function verifiedProviders(): int
    {
        return ProviderProfile::verified()->count();
    }

    public function completedBookingsRevenue(): float
    {
        return (float) Booking::completed()->sum('total_amount');
    }

    public function revenueByDate(?string $from, ?string $to): Collection
    {
        return Booking::query()
            ->completed()
            ->betweenDates($from, $to)
            ->selectRaw('DATE(booking_date) as date, SUM(total_amount) as revenue, COUNT(*) as bookings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function bookingCountsByStatus(?string $from, ?string $to): Collection
    {
        return Booking::query()
            ->betweenDates($from, $to)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }
}
