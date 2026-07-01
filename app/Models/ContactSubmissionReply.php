<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactSubmissionReply extends Model
{
    protected $fillable = [
        'contact_submission_id',
        'user_id',
        'sender_type',
        'sender_name',
        'sender_email',
        'body',
        'message_id',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ContactSubmission::class, 'contact_submission_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
