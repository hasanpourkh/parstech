<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'reference')) {
                $table->string('reference')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('sales', 'currency_id')) {
                $table->unsignedBigInteger('currency_id')->nullable()->after('seller_id');
            }
            if (!Schema::hasColumn('sales', 'title')) {
                $table->string('title')->nullable()->after('currency_id');
            }
            if (!Schema::hasColumn('sales', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('title');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'reference')) {
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('sales', 'currency_id')) {
                $table->dropColumn('currency_id');
            }
            if (Schema::hasColumn('sales', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('sales', 'issued_at')) {
                $table->dropColumn('issued_at');
            }
        });
    }
};
