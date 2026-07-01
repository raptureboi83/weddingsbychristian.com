<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('films_sections', function (Blueprint $table) {
            $table->string('cta_title')->nullable()->after('description');
            $table->text('cta_copy')->nullable()->after('cta_title');
            $table->string('cta_primary_label')->nullable()->after('cta_copy');
            $table->string('cta_primary_url', 2048)->nullable()->after('cta_primary_label');
            $table->string('cta_secondary_label')->nullable()->after('cta_primary_url');
            $table->string('cta_secondary_url', 2048)->nullable()->after('cta_secondary_label');
        });
    }

    public function down(): void
    {
        Schema::table('films_sections', function (Blueprint $table) {
            $table->dropColumn([
                'cta_title',
                'cta_copy',
                'cta_primary_label',
                'cta_primary_url',
                'cta_secondary_label',
                'cta_secondary_url',
            ]);
        });
    }
};
