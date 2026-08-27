<?php

namespace App\Services\Availability;

use App\Models\ProviderProfile;
use App\Models\User;
use App\Repositories\Availability\AvailabilityContract;
use App\Repositories\ProviderProfile\ProviderProfileContract;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AvailabilityService implements AvailabilityInterface
{
    protected $availabilityContract;
    protected $providerProfileContract;

    public function __construct(AvailabilityContract $availabilityContract, ProviderProfileContract $providerProfileContract)
    {
        $this->availabilityContract = $availabilityContract;
        $this->providerProfileContract = $providerProfileContract;
    }

    public function list(User $provider): Collection
    {
        $profile = $this->resolveProfile($provider);

        return $this->availabilityContract->forProvider($profile->id);
    }

    /**
     * Replace the provider's weekly availability with the given slots.
     * $slots = [['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '17:00', 'is_available' => true], ...]
     */
    public function setAvailability(User $provider, array $slots): Collection
    {
        $profile = $this->resolveProfile($provider);

        $result = collect();

        foreach ($slots as $slot) {
            $result->push(
                $this->availabilityContract->updateOrCreate(
                    $profile->id,
                    $slot['day_of_week'],
                    [
                        'start_time'   => $slot['start_time'],
                        'end_time'     => $slot['end_time'],
                        'is_available' => $slot['is_available'] ?? true,
                    ]
                )
            );
        }

        return $result;
    }

    public function delete(User $provider, int $id): bool
    {
        $profile = $this->resolveProfile($provider);

        $slot = $this->availabilityContract->find($profile->id, $id);

        if (! $slot) {
            throw new NotFoundHttpException('Availability slot not found.');
        }

        return $this->availabilityContract->delete($slot);
    }

    private function resolveProfile(User $provider): ProviderProfile
    {
        $profile = $this->providerProfileContract->findByUserId($provider->id);

        if (! $profile) {
            throw new NotFoundHttpException('Provider profile not found.');
        }

        return $profile;
    }
}
