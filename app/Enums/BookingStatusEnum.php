<?php

namespace App\Enums;

class BookingStatusEnum
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::REJECTED,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    /**
     * Which statuses a booking can legally move to from a given status.
     */
    public static function allowedTransitions(string $current): array
    {
        return match ($current) {
            self::PENDING => [self::ACCEPTED, self::REJECTED, self::CANCELLED],
            self::ACCEPTED => [self::IN_PROGRESS, self::CANCELLED],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELLED],
            default => [],
        };
    }
}
