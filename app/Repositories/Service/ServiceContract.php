<?php

namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ServiceContract
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Service;

    public function findWithProvider(int $id): ?Service;

    public function create(array $data): Service;

    public function update(Service $service, array $data): Service;

    public function delete(Service $service): bool;

    public function forProvider(int $providerId, array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
