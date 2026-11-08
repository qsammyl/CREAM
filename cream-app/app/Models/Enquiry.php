<?php

namespace App\Models;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference', 'type', 'name', 'email', 'phone', 'country', 'message',
        'subject', 'asset_id', 'details', 'newsletter_opt_in', 'status',
        'source_url', 'ip_hash', 'user_agent', 'handled_by', 'handled_at',
    ];

    protected $hidden = ['ip_hash', 'user_agent'];

    protected function casts(): array
    {
        return [
            'type' => EnquiryType::class,
            'status' => EnquiryStatus::class,
            'details' => 'array',
            'newsletter_opt_in' => 'boolean',
            'handled_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeStatus(Builder $q, string $status): Builder
    {
        return $q->where('status', $status);
    }

    public function scopeNew(Builder $q): Builder
    {
        return $q->where('status', EnquiryStatus::New->value);
    }

    /** A WhatsApp deep-link to reply, when a phone number is present. */
    public function whatsappUrl(): ?string
    {
        if (blank($this->phone)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->phone);

        return $digits ? 'https://wa.me/'.$digits : null;
    }

    protected static function booted(): void
    {
        static::creating(function (Enquiry $e) {
            if (blank($e->reference)) {
                do {
                    $ref = 'ENQ-'.Str::upper(Str::random(6));
                } while (static::withTrashed()->where('reference', $ref)->exists());
                $e->reference = $ref;
            }
        });
    }
}
