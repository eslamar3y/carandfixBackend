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
            $table->json('items')->nullable()->after('amount');
            $table->string('discount_type')->nullable()->after('items');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            $table->decimal('subtotal', 10, 2)->default(0)->after('discount_value');
            $table->decimal('total', 10, 2)->default(0)->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['items', 'discount_type', 'discount_value', 'subtotal', 'total']);
        });
    }
};
