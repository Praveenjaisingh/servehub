<?php

namespace App\Services\Availability;

use App\Models\User;
use Illuminate\Support\Collection;

interface AvailabilityInterface
{
    public function list(User $provider): Collection;

    public function setAvailability(User $provider, array $slots): Collection;

    public function delete(User $provider, int $id): bool;
}
