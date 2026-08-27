<?php

namespace App\Repositories\ServiceCategory;

use App\Models\ServiceCategory;
use Illuminate\Support\Collection;

class ServiceCategoryEloquent implements ServiceCategoryContract
{
    protected $serviceCategory;

    public function __construct(ServiceCategory $serviceCategory)
    {
        $this->serviceCategory = $serviceCategory;
    }

    public function all(bool $onlyActive = false): Collection
    {
        $query = $this->serviceCategory->query()->latest();

        if ($onlyActive) {
            $query->active();
        }

        return $query->get();
    }

    public function find(int $id): ?ServiceCategory
    {
        return $this->serviceCategory->query()->find($id);
    }

    public function create(array $data): ServiceCategory
    {
        return $this->serviceCategory->create($data);
    }

    public function update(ServiceCategory $category, array $data): ServiceCategory
    {
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function delete(ServiceCategory $category): bool
    {
        return (bool) $category->delete();
    }
}
