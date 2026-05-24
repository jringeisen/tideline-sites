<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('email');
            $table->string('website')->nullable()->after('business_name');
            $table->string('source')->default('contact')->index()->after('plan');
            $table->string('phone')->nullable()->change();
            $table->text('message')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->dropIndex(['source']);
            $table->dropColumn(['business_name', 'website', 'source']);
            $table->string('phone')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();
        });
    }
};
