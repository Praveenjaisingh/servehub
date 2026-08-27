<?php

use App\Helpers\ApiResponse;

/**
 * Global helper functions.
 * Registered via composer.json "autoload.files" (see README_INTEGRATION.md).
 * Extra/reusable functionality that doesn't belong in a Service or Model.
 */

if (! function_exists('api_success')) {
    function api_success($data = null, string $message = 'Success', int $code = 200)
    {
        return ApiResponse::success($data, $message, $code);
    }
}

if (! function_exists('api_error')) {
    function api_error(string $message = 'Something went wrong', int $code = 400, $errors = null)
    {
        return ApiResponse::error($message, $code, $errors);
    }
}

if (! function_exists('generate_unique_slug')) {
    /**
     * Generate a unique slug for a given model/table+column.
     */
    function generate_unique_slug(string $modelClass, string $title, string $column = 'slug'): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $original = $slug;
        $count = 1;

        while ($modelClass::where($column, $slug)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}

if (! function_exists('money_format_inr')) {
    function money_format_inr(float $amount): string
    {
        return '₹' . number_format($amount, 2);
    }
}

if (! function_exists('distance_between_km')) {
    /**
     * Haversine formula - handy for the "location based search" roadmap item.
     */
    function distance_between_km(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}

if (! function_exists('booking_reference')) {
    function booking_reference(): string
    {
        return 'SH-' . strtoupper(uniqid());
    }
}
