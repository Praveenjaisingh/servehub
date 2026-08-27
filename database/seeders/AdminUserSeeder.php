<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@servehub.test'],
            [
                'name'     => 'ServeHub Admin',
                'password' => Hash::make('password'),
                'role'     => RoleEnum::ADMIN,
                'status'   => 'active',
            ]
        );
    }
}
