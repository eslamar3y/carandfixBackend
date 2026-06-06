<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_types', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('car_sub_types', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('engine_types', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('battery_voltages', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('emergencies', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('brand_categories', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('service_categories', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('item_name_ar')->nullable()->after('item_name');
        });
    }

    public function down(): void
    {
        Schema::table('car_types', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('car_sub_types', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('engine_types', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('battery_voltages', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('emergencies', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('brand_categories', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('brands', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('services', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('service_categories', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('parts', fn(Blueprint $t) => $t->dropColumn('name_ar'));
        Schema::table('orders', fn(Blueprint $t) => $t->dropColumn('item_name_ar'));
    }
};
