<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilmsSection extends Model
{
    protected $fillable = [
        'eyebrow',
        'title',
        'description',
        'cta_title',
        'cta_copy',
        'cta_primary_label',
        'cta_primary_url',
        'cta_secondary_label',
        'cta_secondary_url',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            ['is_published' => true],
        );
    }
}
