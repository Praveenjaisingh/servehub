<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserInterface
{
    protected $userContract;

    public function __construct(UserContract $userContract)
    {
        $this->userContract = $userContract;
    }

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->userContract->paginate($filters, $filters['per_page'] ?? 15);
    }

    public function find(int $id): User
    {
        $user = $this->userContract->find($id);

        if (! $user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userContract->update($user, $data);
    }

    public function updateStatus(int $id, string $status): User
    {
        $user = $this->find($id);

        return $this->userContract->update($user, ['status' => $status]);
    }

    public function delete(int $id): bool
    {
        $user = $this->find($id);

        return $this->userContract->delete($user);
    }
}
