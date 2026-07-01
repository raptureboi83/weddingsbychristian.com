<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_tagline',
        'logo_text',
        'logo_image_path',

        'nav_facebook_url',
        'nav_instagram_url',
        'nav_youtube_url',
        'nav_tiktok_url',

        'footer_title',
        'footer_copyright_text',
        'footer_facebook_url',
        'footer_instagram_url',
        'footer_youtube_url',
        'footer_tiktok_url',

        'seo_meta_title',
        'seo_meta_description',
        'seo_og_image_path',

        'contact_email',
        'contact_phone',
        'contact_based_in',
        'contact_form_recipient_email',

        'email_logo_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [],
        );
    }
}