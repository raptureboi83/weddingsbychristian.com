<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'price_label',
        'starting_price',
        'duration_label',
        'deliverables',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'deliverables' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class)->orderBy('sort_order');
    }
}