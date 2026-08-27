<?php

namespace App\Services\Auth;

use App\Models\User;

interface AuthInterface
{
    public function register(array $data): array;

    public function login(array $credentials): array;

    public function logout(User $user): void;

    public function profile(User $user): User;
}
