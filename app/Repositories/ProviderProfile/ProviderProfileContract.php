<?php

namespace App\Repositories\ProviderProfile;

use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProviderProfileContract
{
    public function findByUserId(int $userId): ?ProviderProfile;

    public function find(int $id): ?ProviderProfile;

    public function search(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): ProviderProfile;

    public function update(ProviderProfile $profile, array $data): ProviderProfile;

    public function delete(ProviderProfile $profile): bool;
}
