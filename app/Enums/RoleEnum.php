<?php

namespace App\Enums;

class RoleEnum
{
    public const ADMIN = 'admin';
    public const PROVIDER = 'provider';
    public const CUSTOMER = 'customer';

    public static function all(): array
    {
        return [self::ADMIN, self::PROVIDER, self::CUSTOMER];
    }
}
