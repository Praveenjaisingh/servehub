<?php

namespace App\Services\ProviderProfile;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProviderProfileInterface
{
    public function createOrUpdate(User $user, array $data);

    public function find(int $id);

    public function findByUser(User $user);

    public function search(array $filters): LengthAwarePaginator;

    public function verify(int $id);

    public function delete(User $user): bool;
}
