<?php

namespace App\Repositories\Availability;

use App\Models\Availability;
use Illuminate\Support\Collection;

class AvailabilityEloquent implements AvailabilityContract
{
    protected $availability;

    public function __construct(Availability $availability)
    {
        $this->availability = $availability;
    }

    public function forProvider(int $providerId): Collection
    {
        return $this->availability->query()
            ->forProvider($providerId)
            ->orderBy('day_of_week')
            ->get();
    }

    public function find(int $providerId, int $id): ?Availability
    {
        return $this->availability->query()->forProvider($providerId)->find($id);
    }

    public function updateOrCreate(int $providerId, int $dayOfWeek, array $data): Availability
    {
        return $this->availability->updateOrCreate(
            [
                'provider_id' => $providerId,
                'day_of_week' => $dayOfWeek,
            ],
            $data
        );
    }

    public function delete(Availability $availability): bool
    {
        return (bool) $availability->delete();
    }
}
