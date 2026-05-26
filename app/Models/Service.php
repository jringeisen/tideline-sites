<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name',
    'slug',
    'summary',
    'icon',
    'hero_subhead',
    'body',
    'faqs',
    'meta_title',
    'meta_description',
    'og_image_url',
    'sort_order',
    'is_published',
])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory, HasSlug;

    protected function casts(): array
    {
        return [
            'faqs' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Service>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * @return BelongsToMany<Location, $this>
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}
