<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('gross_amount', 255)->nullable()->change();
            $table->string('discount_amount', 255)->nullable()->change();
            $table->string('net_amount', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('gross_amount', 255)->nullable(false)->change();
            $table->string('discount_amount', 255)->nullable(false)->change();
            $table->string('net_amount', 255)->nullable(false)->change();
        });
    }
};
