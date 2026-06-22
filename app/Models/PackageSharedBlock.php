<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageSharedBlock extends Model
{
    protected $fillable = [
        'title',
        'content',
        'block_type',
        'cta_label',
        'cta_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}