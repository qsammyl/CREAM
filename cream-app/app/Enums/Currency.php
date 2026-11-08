<?php

namespace App\Enums;

enum Currency: string
{
    case GHS = 'GHS';
    case USD = 'USD';
    case GBP = 'GBP';

    public function symbol(): string
    {
        return match ($this) {
            self::GHS => 'GH₵',
            self::USD => '$',
            self::GBP => '£',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return [
            self::GHS->value => 'GHS (Ghana Cedi)',
            self::USD->value => 'USD (US Dollar)',
            self::GBP->value => 'GBP (Pound Sterling)',
        ];
    }
}
