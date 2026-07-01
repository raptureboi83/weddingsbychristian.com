<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'partner_name',
        'wedding_date',
        'location',
        'venue',
        'budget_range',
        'services_requested',
        'how_did_you_hear',
        'message',
        'status',
        'admin_notes',
        'submitted_at',
    ];

    protected $casts = [
        'services_requested' => 'array',
        'wedding_date' => 'date',
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(ContactSubmissionReply::class, 'contact_submission_id');
    }

    public function scopeNewestFirst(Builder $query): Builder
    {
        return $query
            ->orderByDesc('submitted_at')
            ->orderByDesc('id');
    }
}