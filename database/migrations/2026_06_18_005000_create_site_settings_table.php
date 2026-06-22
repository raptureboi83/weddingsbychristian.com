<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name')->nullable();
            $table->string('site_tagline')->nullable();
            $table->string('logo_text')->nullable();
            $table->string('logo_image_path')->nullable();

            $table->string('nav_facebook_url')->nullable();
            $table->string('nav_instagram_url')->nullable();
            $table->string('nav_youtube_url')->nullable();
            $table->string('nav_tiktok_url')->nullable();

            $table->string('footer_title')->nullable();
            $table->string('footer_copyright_text')->nullable();
            $table->string('footer_facebook_url')->nullable();
            $table->string('footer_instagram_url')->nullable();
            $table->string('footer_youtube_url')->nullable();
            $table->string('footer_tiktok_url')->nullable();

            $table->string('seo_meta_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->string('seo_og_image_path')->nullable();

            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_based_in')->nullable();
            $table->string('contact_form_recipient_email')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};