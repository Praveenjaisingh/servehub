<?php

namespace App\Services\ServiceCategory;

use Illuminate\Support\Collection;

interface ServiceCategoryInterface
{
    public function list(bool $onlyActive = false): Collection;

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
}
