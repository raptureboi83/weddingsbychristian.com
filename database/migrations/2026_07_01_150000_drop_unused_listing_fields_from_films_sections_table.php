<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('films_sections', function (Blueprint $table) {
            $table->dropColumn([
                'listing_eyebrow',
                'listing_title',
                'listing_description',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('films_sections', function (Blueprint $table) {
            $table->string('listing_eyebrow')->nullable()->after('cta_secondary_url');
            $table->string('listing_title')->nullable()->after('listing_eyebrow');
            $table->text('listing_description')->nullable()->after('listing_title');
        });
    }
};
