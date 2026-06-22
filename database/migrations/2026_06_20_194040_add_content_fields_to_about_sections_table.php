<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            $table->string('eyebrow')->nullable()->after('id');
            $table->string('title')->nullable()->after('eyebrow');
            $table->text('description')->nullable()->after('title');
            $table->string('media_type')->default('image')->after('description');
            $table->string('image_path')->nullable()->after('media_type');
            $table->string('video_path')->nullable()->after('image_path');
            $table->boolean('is_published')->default(true)->after('video_path');
        });
    }

    public function down(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            $table->dropColumn([
                'eyebrow',
                'title',
                'description',
                'media_type',
                'image_path',
                'video_path',
                'is_published',
            ]);
        });
    }
};