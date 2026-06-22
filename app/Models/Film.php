<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'wedding_date',
        'location',
        'venue',
        'description',
        'video_path',
        'thumbnail_path',
        'thumbnail_alt',
        'sort_order',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByDesc('wedding_date')
            ->orderBy('sort_order');
    }

    public function scopeHomepagePreview(Builder $query): Builder
    {
        return $query
            ->published()
            ->ordered()
            ->limit(3);
    }

    public function scopeArchiveListing(Builder $query): Builder
    {
        $homepageIds = static::query()
            ->homepagePreview()
            ->pluck('id');

        return $query
            ->published()
            ->whereNotIn('id', $homepageIds)
            ->ordered();
    }
}