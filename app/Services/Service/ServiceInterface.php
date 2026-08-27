<?php

namespace App\Services\Service;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ServiceInterface
{
    public function list(array $filters): LengthAwarePaginator;

    public function find(int $id);

    public function create(User $provider, array $data);

    public function update(User $provider, int $id, array $data);

    public function delete(User $provider, int $id): bool;

    public function myServices(User $provider, array $filters): LengthAwarePaginator;
}
