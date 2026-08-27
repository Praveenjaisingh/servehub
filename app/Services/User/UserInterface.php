<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserInterface
{
    public function list(array $filters): LengthAwarePaginator;

    public function find(int $id): User;

    public function update(int $id, array $data): User;

    public function updateStatus(int $id, string $status): User;

    public function delete(int $id): bool;
}
