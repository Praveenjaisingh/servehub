<?php

namespace App\Services\AdminDashboard;

interface AdminDashboardInterface
{
    public function stats(): array;

    public function revenueReport(?string $from, ?string $to): array;

    public function bookingReport(?string $from, ?string $to): array;
}
