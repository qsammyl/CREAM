<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'cover_path', 'cover_path_sm',
        'author_id', 'author_name', 'category', 'status', 'allow_comments',
        'source', 'source_url', 'legacy_path', 'published_at', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'allow_comments' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved')->oldest();
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', ArticleStatus::Published->value)
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function authorLabel(): string
    {
        return $this->author?->name ?: ($this->author_name ?: 'Credence');
    }

    /** Public URL — hand-written legacy posts keep their original path. */
    public function url(): string
    {
        return $this->legacy_path ? '/'.ltrim($this->legacy_path, '/') : '/blog/'.$this->slug;
    }

    public function coverUrl(): ?string
    {
        return $this->cover_path ? Storage::disk('uploads')->url($this->cover_path) : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Article $a) {
            if (blank($a->slug)) {
                $base = Str::slug($a->title) ?: 'article';
                $slug = $base;
                $i = 2;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $a->slug = $slug;
            }
        });
    }
}
