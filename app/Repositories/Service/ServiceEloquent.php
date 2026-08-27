<?php

namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceEloquent implements ServiceContract
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->service->query()
            ->with(['provider.user', 'category'])
            ->active()
            ->category($filters['category_id'] ?? null)
            ->search($filters['search'] ?? null)
            ->priceBetween($filters['min_price'] ?? null, $filters['max_price'] ?? null)
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function find(int $id): ?Service
    {
        return $this->service->query()->find($id);
    }

    public function findWithProvider(int $id): ?Service
    {
        return $this->service->query()->with(['provider.user', 'category'])->find($id);
    }

    public function create(array $data): Service
    {
        return $this->service->create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->fill($data);
        $service->save();

        return $service;
    }

    public function delete(Service $service): bool
    {
        return (bool) $service->delete();
    }

    public function forProvider(int $providerId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->service->query()
            ->with('category')
            ->provider($providerId)
            ->search($filters['search'] ?? null)
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }
}
