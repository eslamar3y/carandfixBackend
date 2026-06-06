<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['emergencies', 'services', 'parts', 'brands', 'service_categories', 'orders'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->default(null)->change();
            });
        }
    }

    public function down(): void
    {
        $tables = ['emergencies', 'services', 'parts', 'brands', 'service_categories', 'orders'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->decimal('price', 10, 2)->default(0)->change();
            });
        }
    }
};
