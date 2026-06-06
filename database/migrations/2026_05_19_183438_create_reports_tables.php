<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('serial')->nullable();
            $table->string('final_decision')->nullable();
            $table->string('current_mileage')->nullable();
            $table->string('report_date')->nullable();
            $table->string('car_options')->nullable();

            $table->integer('chassis_percent')->nullable();
            $table->integer('exterior_percent')->nullable();
            $table->integer('road_test_percent')->nullable();
            $table->integer('power_train_percent')->nullable();
            $table->integer('electrical_percent')->nullable();
            $table->integer('braking_percent')->nullable();
            $table->integer('suspension_percent')->nullable();
            $table->integer('ac_cooling_percent')->nullable();

            $table->json('exterior')->nullable();
            $table->json('chassis_frame')->nullable();
            $table->json('road_test')->nullable();
            $table->json('power_train')->nullable();
            $table->json('electrical_system')->nullable();
            $table->json('braking_safety')->nullable();
            $table->json('suspension')->nullable();
            $table->json('ac_cooling')->nullable();
            $table->json('all_notes')->nullable();
            $table->json('inspection_systems')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
