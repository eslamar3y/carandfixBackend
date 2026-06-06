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
        Schema::table('parts', function (Blueprint $table) {
            $table->foreignId('brand_category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropForeign(['brand_category_id']);
            $table->dropColumn('brand_category_id');
            $table->dropColumn('price');
        });
    }
};
