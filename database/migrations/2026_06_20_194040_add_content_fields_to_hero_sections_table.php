<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->string('eyebrow')->nullable()->after('id');
            $table->string('title')->nullable()->after('eyebrow');
            $table->text('description')->nullable()->after('title');
            $table->string('background_media_path')->nullable()->after('description');
            $table->boolean('is_published')->default(true)->after('background_media_path');
        });
    }

    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn([
                'eyebrow',
                'title',
                'description',
                'background_media_path',
                'is_published',
            ]);
        });
    }
};