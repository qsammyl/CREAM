<?php

namespace App\Models;

use App\Enums\CommentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'article_id', 'name', 'email', 'body', 'status',
        'ip_hash', 'user_agent', 'moderated_by', 'moderated_at',
    ];

    protected $hidden = ['email', 'ip_hash', 'user_agent'];

    protected function casts(): array
    {
        return [
            'status' => CommentStatus::class,
            'moderated_at' => 'datetime',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', CommentStatus::Approved->value);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', CommentStatus::Pending->value);
    }

    public function scopeSpam(Builder $q): Builder
    {
        return $q->where('status', CommentStatus::Spam->value);
    }
}
