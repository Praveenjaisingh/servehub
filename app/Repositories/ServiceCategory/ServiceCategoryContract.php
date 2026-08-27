<?php

namespace App\Repositories\ServiceCategory;

use App\Models\ServiceCategory;
use Illuminate\Support\Collection;

interface ServiceCategoryContract
{
    public function all(bool $onlyActive = false): Collection;

    public function find(int $id): ?ServiceCategory;

    public function create(array $data): ServiceCategory;

    public function update(ServiceCategory $category, array $data): ServiceCategory;

    public function delete(ServiceCategory $category): bool;
}
