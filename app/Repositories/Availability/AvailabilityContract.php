<?php

namespace App\Repositories\Availability;

use App\Models\Availability;
use Illuminate\Support\Collection;

interface AvailabilityContract
{
    public function forProvider(int $providerId): Collection;

    public function find(int $providerId, int $id): ?Availability;

    public function updateOrCreate(int $providerId, int $dayOfWeek, array $data): Availability;

    public function delete(Availability $availability): bool;
}
