<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('ref_no')->nullable();
            $table->date('generated_at');
            $table->string('attn')->nullable();
            $table->string('from_person')->nullable();
            $table->string('title')->nullable();
            $table->string('fax')->nullable();
            $table->string('your_ref')->nullable();
            $table->string('subject')->nullable();
            $table->json('items')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('total_words')->nullable();
            $table->string('validity')->nullable();
            $table->string('delivery')->nullable();
            $table->text('terms')->nullable();
            $table->text('footer_note')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
