<?php

namespace App\Repositories\ProviderProfile;

use App\Models\ProviderProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProviderProfileEloquent implements ProviderProfileContract
{
    protected $providerProfile;

    public function __construct(ProviderProfile $providerProfile)
    {
        $this->providerProfile = $providerProfile;
    }

    public function findByUserId(int $userId): ?ProviderProfile
    {
        return ProviderProfile::findByUserId($userId);
    }

    public function find(int $id): ?ProviderProfile
    {
        return $this->providerProfile->query()
            ->with(['user', 'services', 'availabilities'])
            ->find($id);
    }

    public function search(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->providerProfile->query()
            ->with('user')
            ->verified()
            ->inCity($filters['city'] ?? null)
            ->when(! empty($filters['skill']), function ($q) use ($filters) {
                $q->whereJsonContains('skills', $filters['skill']);
            })
            ->topRated()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function create(array $data): ProviderProfile
    {
        return $this->providerProfile->create($data);
    }

    public function update(ProviderProfile $profile, array $data): ProviderProfile
    {
        $profile->fill($data);
        $profile->save();

        return $profile;
    }

    public function delete(ProviderProfile $profile): bool
    {
        return (bool) $profile->delete();
    }
}
