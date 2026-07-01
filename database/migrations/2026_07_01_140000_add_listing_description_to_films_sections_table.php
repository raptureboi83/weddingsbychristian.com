<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('films_sections', function (Blueprint $table) {
            $table->text('listing_description')->nullable()->after('listing_title');
        });
    }

    public function down(): void
    {
        Schema::table('films_sections', function (Blueprint $table) {
            $table->dropColumn('listing_description');
        });
    }
};
