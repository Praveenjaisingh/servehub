<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Collection;

interface DashboardContract
{
    public function totalCustomers(): int;

    public function totalProviders(): int;

    public function totalServices(): int;

    public function totalBookings(): int;

    public function pendingBookings(): int;

    public function completedBookingsCount(): int;

    public function verifiedProviders(): int;

    public function completedBookingsRevenue(): float;

    public function revenueByDate(?string $from, ?string $to): Collection;

    public function bookingCountsByStatus(?string $from, ?string $to): Collection;
}
