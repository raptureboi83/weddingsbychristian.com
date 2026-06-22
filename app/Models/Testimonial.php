<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'couple_names',
        'quote',
        'source_label',
        'source_url',
        'wedding_date',
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
            ->orderBy('sort_order')
            ->orderByDesc('wedding_date')
            ->orderByDesc('id');
    }
}