<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name',
    'slug',
    'display_name',
    'region',
    'tagline',
    'hero_subhead',
    'intro',
    'why_local',
    'body',
    'segments',
    'faqs',
    'lat',
    'lng',
    'meta_title',
    'meta_description',
    'og_image_url',
    'sort_order',
    'is_published',
])]
class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory, HasSlug;

    protected function casts(): array
    {
        return [
            'segments' => 'array',
            'faqs' => 'array',
            'lat' => 'float',
            'lng' => 'float',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Location>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * @return BelongsToMany<Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    /**
     * Sibling locations cross-linked from this one.
     *
     * @return BelongsToMany<Location, $this>
     */
    public function nearby(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'location_location', 'location_id', 'nearby_location_id')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}
