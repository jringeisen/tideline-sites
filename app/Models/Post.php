<?php

namespace App\Models;

use App\Concerns\HasSlug;
use App\Enums\PostStatus;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * @property PostStatus $status
 * @property Carbon|null $published_at
 */
#[Fillable([
    'title',
    'slug',
    'excerpt',
    'content',
    'category_id',
    'author_id',
    'status',
    'published_at',
    'meta_title',
    'meta_description',
    'og_image_url',
    'reading_time_minutes',
])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasSlug, Searchable;

    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            $post->reading_time_minutes = static::computeReadingTime((string) $post->content);
        });
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    protected function slugFallback(): string
    {
        return 'post';
    }

    /**
     * @param  Builder<Post>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', PostStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<Post>  $query
     */
    public function scopeScheduled(Builder $query): void
    {
        $query->where('status', PostStatus::Scheduled->value);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (string) $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => strip_tags((string) $this->content),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === PostStatus::Published;
    }

    public static function computeReadingTime(string $content): int
    {
        $words = str_word_count(strip_tags($content));

        return max(1, (int) ceil($words / 200));
    }
}
