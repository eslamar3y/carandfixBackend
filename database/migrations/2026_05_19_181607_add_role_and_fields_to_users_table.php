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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('image')->nullable()->after('phone');
            $table->string('role')->default('customer')->after('image');
            $table->boolean('is_verified')->default(false)->after('role');
            $table->boolean('is_active')->default(false)->after('is_verified');
            $table->string('fcm_token')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'image', 'role', 'is_verified', 'is_active', 'fcm_token']);
        });
    }
};
