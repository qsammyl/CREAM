<?php

namespace App\Models;

use App\Enums\TestimonialStatus;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'role', 'photo_path', 'rating', 'body', 'consent',
        'status', 'is_featured', 'sort_order', 'source',
        'ip_hash', 'user_agent', 'moderated_by', 'moderated_at',
    ];

    protected $hidden = ['email', 'ip_hash', 'user_agent'];

    protected function casts(): array
    {
        return [
            'status' => TestimonialStatus::class,
            'consent' => 'boolean',
            'is_featured' => 'boolean',
            'rating' => 'integer',
            'moderated_at' => 'datetime',
        ];
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', TestimonialStatus::Approved->value);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', TestimonialStatus::Pending->value);
    }

    public function scopeArchived(Builder $q): Builder
    {
        return $q->where('status', TestimonialStatus::Archived->value);
    }

    public function scopeSpam(Builder $q): Builder
    {
        return $q->where('status', TestimonialStatus::Spam->value);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    /** First letter of the name, for the avatar chip. */
    public function initial(): string
    {
        return mb_strtoupper(mb_substr(trim($this->name), 0, 1)) ?: '·';
    }

    /** Public URL of the avatar photo, or null to fall back to the initials chip. */
    public function photoUrl(): ?string
    {
        return $this->photo_path ? Storage::disk('uploads')->url($this->photo_path) : null;
    }

    /** A testimonial can only be published if the author consented. */
    public function canPublish(): bool
    {
        return $this->consent;
    }
}
