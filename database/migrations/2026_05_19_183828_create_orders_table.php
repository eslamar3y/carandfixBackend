<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('phone')->nullable();
            $table->string('manufactory')->nullable();
            $table->string('battery_voltage_id')->nullable();
            $table->string('with_service')->nullable();
            $table->string('car_license')->nullable();
            $table->string('with_filter')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('pick_date')->nullable();
            $table->text('note')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('type')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('item_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
