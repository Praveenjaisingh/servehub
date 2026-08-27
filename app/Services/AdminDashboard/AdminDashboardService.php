<?php

namespace App\Services\AdminDashboard;

use App\Repositories\Dashboard\DashboardContract;

class AdminDashboardService implements AdminDashboardInterface
{
    protected $dashboardContract;

    public function __construct(DashboardContract $dashboardContract)
    {
        $this->dashboardContract = $dashboardContract;
    }

    public function stats(): array
    {
        return [
            'total_customers'    => $this->dashboardContract->totalCustomers(),
            'total_providers'    => $this->dashboardContract->totalProviders(),
            'total_services'     => $this->dashboardContract->totalServices(),
            'total_bookings'     => $this->dashboardContract->totalBookings(),
            'pending_bookings'   => $this->dashboardContract->pendingBookings(),
            'completed_bookings' => $this->dashboardContract->completedBookingsCount(),
            'verified_providers' => $this->dashboardContract->verifiedProviders(),
            'total_revenue'      => $this->dashboardContract->completedBookingsRevenue(),
        ];
    }

    public function revenueReport(?string $from, ?string $to): array
    {
        $revenue = $this->dashboardContract->revenueByDate($from, $to);

        return [
            'total_revenue'  => (float) $revenue->sum('revenue'),
            'total_bookings' => (int) $revenue->sum('bookings'),
            'breakdown'      => $revenue,
        ];
    }

    public function bookingReport(?string $from, ?string $to): array
    {
        $byStatus = $this->dashboardContract->bookingCountsByStatus($from, $to);

        return [
            'by_status' => $byStatus,
            'total'     => (int) $byStatus->sum(),
        ];
    }
}
