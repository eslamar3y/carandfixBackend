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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('gross_amount', 255)->change();
            $table->string('discount_amount', 255)->change();
            $table->string('net_amount', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('gross_amount', 10, 2)->default(0)->change();
            $table->decimal('discount_amount', 10, 2)->default(0)->change();
            $table->decimal('net_amount', 10, 2)->default(0)->change();
        });
    }
};
