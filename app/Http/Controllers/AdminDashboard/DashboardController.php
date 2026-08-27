<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboard\AdminDashboardInterface;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardInterface $dashboardService
    ) {
    }

    public function stats()
    {
        try {
            $stats = $this->dashboardService->stats();

            return api_success($stats, 'Dashboard stats fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function revenue(Request $request)
    {
        try {
            $report = $this->dashboardService->revenueReport(
                $request->query('from'),
                $request->query('to')
            );

            return api_success($report, 'Revenue report fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function bookings(Request $request)
    {
        try {
            $report = $this->dashboardService->bookingReport(
                $request->query('from'),
                $request->query('to')
            );

            return api_success($report, 'Booking report fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
