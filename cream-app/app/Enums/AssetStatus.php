<?php

namespace App\Enums;

enum AssetStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case UnderOffer = 'under_offer';
    case Sold = 'sold';
    case Let = 'let';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::UnderOffer => 'Under offer',
            self::Sold => 'Sold',
            self::Let => 'Let',
            self::Archived => 'Archived',
        };
    }

    /**
     * Statuses visible on the public site.
     */
    public function isPublic(): bool
    {
        return in_array($this, [self::Published, self::UnderOffer, self::Sold, self::Let], true);
    }

    /**
     * Statuses shown in the default listing grids (sold/let are excluded —
     * they appear only under a "Recently sold / let" filter).
     */
    public function inDefaultListings(): bool
    {
        return in_array($this, [self::Published, self::UnderOffer], true);
    }

    /**
     * The overlay badge shown on public cards/pages, or null for none.
     */
    public function publicBadge(): ?string
    {
        return match ($this) {
            self::UnderOffer => 'Under offer',
            self::Sold => 'Sold',
            self::Let => 'Let',
            default => null,
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $c) => $carry + [$c->value => $c->label()],
            [],
        );
    }
}
