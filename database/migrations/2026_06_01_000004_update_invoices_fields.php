<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('id');
            $table->string('payment_method')->nullable()->after('customer_name');
            $table->decimal('gross_amount', 10, 2)->default(0)->after('items');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('gross_amount');
            $table->decimal('net_amount', 10, 2)->default(0)->after('discount_amount');

            $table->foreignId('user_id')->nullable()->change();
            $table->string('invoice_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'payment_method', 'gross_amount', 'discount_amount', 'net_amount']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->string('invoice_number')->unique()->nullable(false)->change();
        });
    }
};
