<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('vin_number')->nullable();
            $table->foreignId('car_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('car_sub_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('engine_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->string('color')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('year_of_production')->nullable();
            $table->string('engine_power')->nullable();
            $table->string('last_oil_change_date')->nullable();
            $table->timestamps();
        });

        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('type')->default('car'); // car or gallery
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_images');
        Schema::dropIfExists('cars');
    }
};
