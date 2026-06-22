<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages_sections', function (Blueprint $table) {
            $table->id();

            $table->string('eyebrow')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('intro')->nullable();

            $table->string('bottom_heading')->nullable();
            $table->text('bottom_description')->nullable();

            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();

            $table->boolean('is_published')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages_sections');
    }
};