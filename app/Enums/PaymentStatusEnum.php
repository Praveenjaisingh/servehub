<?php

namespace App\Enums;

class PaymentStatusEnum
{
    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const FAILED = 'failed';
    public const REFUNDED = 'refunded';

    public static function all(): array
    {
        return [self::PENDING, self::PAID, self::FAILED, self::REFUNDED];
    }
}
