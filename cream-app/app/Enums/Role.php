<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Staff => 'Staff',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    /** @return array<string, string> value => label, for form selects */
    public static function options(): array
    {
        return [
            self::Admin->value => self::Admin->label(),
            self::Staff->value => self::Staff->label(),
        ];
    }
}
