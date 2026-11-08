<?php

namespace App\Enums;

enum AssetCategory: string
{
    case Sale = 'sale';
    case Rent = 'rent';
    case Land = 'land';
    case ShortStay = 'short_stay';
    case NewProject = 'new_project';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'For sale',
            self::Rent => 'For rent',
            self::Land => 'Land',
            self::ShortStay => 'Short stay',
            self::NewProject => 'New project',
        };
    }

    /** @return array<string, string> value => label, for form selects / filters */
    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $c) => $carry + [$c->value => $c->label()],
            [],
        );
    }
}
