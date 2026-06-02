<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo_reports', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('url', 2048);
            $table->string('host');
            $table->string('industry');
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('score')->nullable();
            $table->json('report')->nullable();
            $table->json('raw_signals')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_captured_at')->nullable();
            $table->foreignId('contact_inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['host', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_reports');
    }
};
