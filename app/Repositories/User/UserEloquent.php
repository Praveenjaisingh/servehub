<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserEloquent implements UserContract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->user->query()
            ->when(! empty($filters['role']), fn ($q) => $q->role($filters['role']))
            ->search($filters['search'] ?? null)
            ->latest()
            ->paginate($filters['per_page'] ?? $perPage);
    }

    public function find(int $id): ?User
    {
        return $this->user->query()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::findByEmail($email);
    }

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
