<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sale_return_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_return_items', 'reason')) {
                $table->string('reason')->nullable()->after('qty');
            }
            if (!Schema::hasColumn('sale_return_items', 'item_description')) {
                $table->string('item_description')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('sale_return_items', 'is_product')) {
                $table->boolean('is_product')->default(false)->after('barcode');
            }
        });
    }

    public function down()
    {
        Schema::table('sale_return_items', function (Blueprint $table) {
            if (Schema::hasColumn('sale_return_items', 'reason')) {
                $table->dropColumn('reason');
            }
            if (Schema::hasColumn('sale_return_items', 'item_description')) {
                $table->dropColumn('item_description');
            }
            if (Schema::hasColumn('sale_return_items', 'is_product')) {
                $table->dropColumn('is_product');
            }
        });
    }
};
