<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_location', function (Blueprint $table): void {
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->foreignId('nearby_location_id')->constrained('locations')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->primary(['location_id', 'nearby_location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_location');
    }
};
