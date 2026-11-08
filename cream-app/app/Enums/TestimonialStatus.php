<?php

namespace App\Enums;

enum TestimonialStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Archived = 'archived';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Published',
            self::Archived => 'Archived',
            self::Spam => 'Spam',
        };
    }
}
