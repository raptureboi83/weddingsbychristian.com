<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagesSection extends Model
{
    protected $fillable = [
        'eyebrow',
        'title',
        'description',
        'intro',
        'bottom_heading',
        'bottom_description',
        'cta_label',
        'cta_url',
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