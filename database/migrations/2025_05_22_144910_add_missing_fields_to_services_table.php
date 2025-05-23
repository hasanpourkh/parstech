<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'unit_id')) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('service_category_id');
            }
            if (!Schema::hasColumn('services', 'unit')) {
                $table->string('unit', 100)->nullable()->after('unit_id');
            }
            if (!Schema::hasColumn('services', 'service_info')) {
                $table->string('service_info', 255)->nullable()->after('service_code');
            }
            if (!Schema::hasColumn('services', 'short_description')) {
                $table->string('short_description', 255)->nullable()->after('description');
            }
            if (!Schema::hasColumn('services', 'info_link')) {
                $table->text('info_link')->nullable()->after('short_description');
            }
            if (!Schema::hasColumn('services', 'full_description')) {
                $table->text('full_description')->nullable()->after('info_link');
            }
            if (!Schema::hasColumn('services', 'image')) {
                $table->string('image', 255)->nullable()->after('unit');
            }
            if (!Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('image');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'unit_id')) {
                $table->dropColumn('unit_id');
            }
            if (Schema::hasColumn('services', 'unit')) {
                $table->dropColumn('unit');
            }
            if (Schema::hasColumn('services', 'service_info')) {
                $table->dropColumn('service_info');
            }
            if (Schema::hasColumn('services', 'short_description')) {
                $table->dropColumn('short_description');
            }
            if (Schema::hasColumn('services', 'info_link')) {
                $table->dropColumn('info_link');
            }
            if (Schema::hasColumn('services', 'full_description')) {
                $table->dropColumn('full_description');
            }
            if (Schema::hasColumn('services', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('services', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
