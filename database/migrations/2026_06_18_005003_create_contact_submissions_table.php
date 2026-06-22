<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('partner_name')->nullable();

            $table->date('wedding_date')->nullable();
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            $table->string('budget_range')->nullable();

            $table->json('services_requested')->nullable();
            $table->string('how_did_you_hear')->nullable();
            $table->longText('message');

            $table->string('status')->default('new');
            $table->longText('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};