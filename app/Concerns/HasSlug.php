<?php

namespace App\Concerns;

use Illuminate\Support\Str;

/**
 * Auto-generates a unique slug from a source attribute whenever the model is
 * saved without one. Using models may override {@see slugSource()} and
 * {@see slugFallback()} to customise the behaviour.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (self $model): void {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug();
            }
        });
    }

    /**
     * The attribute the slug is derived from.
     */
    protected function slugSource(): string
    {
        return 'name';
    }

    /**
     * Base used when the source attribute produces an empty slug.
     */
    protected function slugFallback(): string
    {
        return 'item';
    }

    protected function generateUniqueSlug(): string
    {
        $base = Str::slug((string) $this->{$this->slugSource()}) ?: $this->slugFallback();
        $slug = $base;
        $i = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($this->getKey(), fn ($query) => $query->where($this->getKeyName(), '!=', $this->getKey()))
            ->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
